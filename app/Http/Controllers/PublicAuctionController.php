<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\AuctionCar;
use App\Models\Bid;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PublicAuctionController extends Controller
{
    protected $shopId;

    protected $apiKey;

    public function __construct()
    {
        $this->shopId = config('services.yookassa.shop_id');
        $this->apiKey = config('services.yookassa.api_key');
    }

   public function index()
{
    $cars = AuctionCar::whereHas('auctions', function ($query) {
        $query->where('end_date', '>', now());
    })
    ->with(['auctions' => function ($query) {
        $query->where('end_date', '>', now())
              ->latest('start_date');
    }])
    ->orderBy('created_at', 'desc')
    ->get();

    $brands = $cars->map(function ($car) {
        return explode(' ', $car->model)[0] ?? '';
    })->unique()->values();

    return view('auction.index', compact('cars', 'brands'));
}

    public function show(Request $request, AuctionCar $car)
    {
       $auction = $car->auctions()
        ->where('end_date', '>', now())
        ->latest('start_date')
        ->first();

    if (!$auction) {
        return redirect()->route('auction.index')
            ->with('error', 'Аукцион для этого автомобиля ещё не создан или уже завершён.');
    }

        $allPhotos = [];
        if ($car->photo) {
            $allPhotos[] = $car->photo;
        }
        if ($car->additional_photos && is_array($car->additional_photos)) {
            $allPhotos = array_merge($allPhotos, $car->additional_photos);
        }

        $user = Auth::user();
        $userPaid = false;
        $userCurrentBid = null;
        $userHasActiveBids = false;
        $currentMaxBid = null;
        $isUserCurrentMaxBidder = false;

        if ($user && $auction) {
            $userPaid = Payment::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->where('status', 'succeeded')
                ->exists();

            $userCurrentBid = Bid::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->latest()
                ->first();

            $userHasActiveBids = Bid::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->exists();

            $currentMaxBid = Bid::where('auction_id', $auction->id)
                ->orderBy('amount', 'desc')
                ->first();

            if ($currentMaxBid && $currentMaxBid->user_id === $user->id) {
                $isUserCurrentMaxBidder = true;
            }
        }

        $now = now();
        $auctionStatus = 'upcoming';
        if ($auction) {
            if ($auction->start_date <= $now && $auction->end_date > $now) {
                $auctionStatus = 'active';
            } elseif ($auction->end_date <= $now) {
                $auctionStatus = 'ended';
            }
        }

        $bidTitle = 'Начальная ставка';
        $displayCurrentBid = $auction->starting_price ?? 0;
        if ($auction && $auction->current_bid > 0) {
            $bidTitle = 'Текущая ставка';
            $displayCurrentBid = $auction->current_bid;
        }

        $bidCount = $auction ? $auction->bid_count : 0;
        $onePercent = $auction ? $auction->starting_price * 0.01 : 0;
        $nextBidAmount = $displayCurrentBid + 150000;

        if ($request->has('paid') && $request->paid == '1' && $user && $auction) {
            $updated = Payment::where('user_id', $user->id)
                ->where('auction_id', $auction->id)
                ->where('status', 'pending')
                ->update(['status' => 'succeeded']);

            if ($updated) {
                session(['auction_paid_'.$auction->id => true]);

                return redirect()->route('auction.show', ['car' => $car->id])
                    ->with('success', 'Оплата прошла успешно! Теперь вы можете участвовать в торгах.');
            }

            return redirect()->route('auction.show', ['car' => $car->id])
                ->with('error', 'Не удалось подтвердить оплату. Обратитесь в поддержку.');
        }

        return view('auction.show', compact(
            'car', 'auction', 'allPhotos', 'userPaid', 'userCurrentBid',
            'userHasActiveBids', 'currentMaxBid', 'isUserCurrentMaxBidder',
            'auctionStatus', 'bidTitle', 'displayCurrentBid', 'bidCount',
            'onePercent', 'nextBidAmount'
        ));
    }

    public function processPayment(Request $request, Auction $auction)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($auction->end_date <= now()) {
            return back()->with('error', 'Аукцион уже завершён.');
        }

        $existingPayment = Payment::where('user_id', $user->id)
            ->where('auction_id', $auction->id)
            ->where('status', 'succeeded')
            ->exists();

        if ($existingPayment) {
            return back()->with('error', 'Вы уже оплатили участие в этом аукционе.');
        }

        $amount = $auction->starting_price * 0.01;
        $idempotenceKey = (string) Str::uuid();

        $paymentData = [
            'amount' => [
                'value' => number_format($amount, 2, '.', ''),
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => route('auction.show', ['car' => $auction->car_id]).'?paid=1',
            ],
            'capture' => true,
            'description' => "Взнос за участие в аукционе {$auction->car->model}",
            'metadata' => [
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'car_id' => $auction->car_id,
            ],
        ];

        $jsonData = json_encode($paymentData, JSON_UNESCAPED_UNICODE);

        $url = 'https://api.yookassa.ru/v3/payments';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, $this->shopId.':'.$this->apiKey);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Idempotence-Key: '.$idempotenceKey,
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false) {
            Log::error('YooKassa cURL error: '.$error);

            return back()->with('error', 'Ошибка соединения с платёжным шлюзом: '.$error);
        }

        if ($httpCode !== 200) {
            Log::error('YooKassa HTTP error '.$httpCode.': '.$response);

            return back()->with('error', 'Ошибка платёжного шлюза (HTTP '.$httpCode.'). Попробуйте позже.');
        }

        $result = json_decode($response, true);

        if (! isset($result['confirmation']['confirmation_url'])) {
            Log::error('YooKassa invalid response: '.$response);

            return back()->with('error', 'Не удалось получить ссылку на оплату. Пожалуйста, попробуйте позже.');
        }

        Payment::create([
            'user_id' => $user->id,
            'auction_id' => $auction->id,
            'amount' => $amount,
            'status' => 'pending',
            'payment_id' => $result['id'],
        ]);

        return redirect()->away($result['confirmation']['confirmation_url']);
    }

    public function storeBid(Request $request, Auction $auction)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if ($auction->start_date > now() || $auction->end_date <= now()) {
            return back()->with('error', 'Аукцион не активен в данный момент.');
        }

        $hasPaid = Payment::where('user_id', $user->id)
            ->where('auction_id', $auction->id)
            ->where('status', 'succeeded')
            ->exists();

        if (! $hasPaid) {
            return back()->with('error', 'Для участия необходимо оплатить взнос.');
        }

        $currentMaxBid = $auction->bids()->orderBy('amount', 'desc')->first();
        $currentPrice = $currentMaxBid ? $currentMaxBid->amount : $auction->starting_price;
        $bidAmount = $currentPrice + 150000;

        DB::beginTransaction();
        try {
            Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $user->id,
                'amount' => $bidAmount,
                'is_winner' => false,
            ]);

            $auction->update([
                'current_bid' => $bidAmount,
                'bid_count' => $auction->bids()->count(),
            ]);

            DB::commit();

            return redirect()->route('auction.show', ['car' => $auction->car_id])
                ->with('success', 'Ставка успешно размещена!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bid error: '.$e->getMessage());

            return back()->with('error', 'Ошибка при размещении ставки: '.$e->getMessage());
        }
    }

    public function destroyBid(Bid $bid)
    {
        $user = Auth::user();
        if (! $user || $bid->user_id !== $user->id) {
            abort(403);
        }

        $auction = $bid->auction;

        if ($auction->end_date <= now()) {
            return back()->with('error', 'Аукцион завершён, ставку отменить нельзя.');
        }

        DB::beginTransaction();
        try {
            $bid->delete();

            $newMaxBid = $auction->bids()->orderBy('amount', 'desc')->first();
            $newCurrentBid = $newMaxBid ? $newMaxBid->amount : $auction->starting_price;

            $auction->update([
                'current_bid' => $newCurrentBid,
                'bid_count' => $auction->bids()->count(),
            ]);

            DB::commit();

            return redirect()->route('auction.show', ['car' => $auction->car_id])
                ->with('success', 'Ставка отменена.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel bid error: '.$e->getMessage());

            return back()->with('error', 'Ошибка при отмене ставки.');
        }
    }

    public function checkMyBids(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'error' => 'Not authorized']);
        }

        $auctionId = $request->input('auction_id');

        $hasBids = Bid::where('user_id', $user->id)
            ->where('auction_id', $auctionId)
            ->exists();

        $bidCount = Bid::where('user_id', $user->id)
            ->where('auction_id', $auctionId)
            ->count();

        return response()->json([
            'success' => true,
            'has_bids' => $hasBids,
            'bid_count' => $bidCount,
        ]);
    }

    public function checkPayment(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['success' => false, 'paid' => false]);
        }

        $auctionId = $request->input('auction_id');

        $paid = Payment::where('user_id', $user->id)
            ->where('auction_id', $auctionId)
            ->where('status', 'succeeded')
            ->exists();

        return response()->json([
            'success' => true,
            'paid' => $paid,
        ]);
    }

    public function getAuctionData(Request $request)
    {
        $auction = Auction::with('bids.user')->find($request->input('auction_id'));

        if (! $auction) {
            return response()->json(['success' => false]);
        }

        $currentMaxBid = $auction->bids()->orderBy('amount', 'desc')->first();
        $currentPrice = $currentMaxBid ? $currentMaxBid->amount : $auction->starting_price;
        $bidCount = $auction->bids()->count();
        $bidStep = 150000;
        $nextBid = $currentPrice + $bidStep;

        $user = Auth::user();
        $userActiveBid = null;
        $userHasBids = false;
        $isUserMaxBidder = false;
        $userCurrentBid = null;

        if ($user) {
            $userActiveBid = $auction->bids()->where('user_id', $user->id)->latest()->first();
            $userHasBids = ! is_null($userActiveBid);
            $isUserMaxBidder = $userActiveBid && $currentMaxBid && $userActiveBid->id == $currentMaxBid->id;
            $userCurrentBid = $userActiveBid ? $userActiveBid->amount : null;
        }

        return response()->json([
            'success' => true,
            'current_price' => $currentPrice,
            'bid_count' => $bidCount,
            'next_bid' => $nextBid,
            'user_has_bids' => $userHasBids,
            'is_user_max_bidder' => $isUserMaxBidder,
            'user_current_bid' => $userCurrentBid,
            'current_max_bidder_name' => $currentMaxBid && $currentMaxBid->user ? $currentMaxBid->user->name : null,
        ]);
    }
}
