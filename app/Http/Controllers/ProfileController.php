<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFavorite;
use App\Models\UserConfig;
use App\Models\Bid;
use App\Models\Auction;
use App\Models\AuctionCar;
use App\Models\Car;
use App\Models\CarConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $favorites = UserFavorite::where('user_id', $user->id)
            ->with('car')
            ->orderBy('id', 'desc')
            ->get();

        $configurations = UserConfig::where('user_id', $user->id)
            ->with('carConfig')
            ->orderBy('created_at', 'desc')
            ->get();

        $auctionIds = Bid::where('user_id', $user->id)
            ->distinct()
            ->pluck('auction_id')
            ->toArray();

        $bids = [];
        
        if (!empty($auctionIds)) {
            $auctions = Auction::with('car')
                ->whereIn('id', $auctionIds)
                ->get();
            
            foreach ($auctions as $auction) {
                $userBids = Bid::where('auction_id', $auction->id)
                    ->where('user_id', $user->id)
                    ->orderBy('amount', 'desc')
                    ->get();
                
                $maxUserBid = $userBids->max('amount');
                $minUserBid = $userBids->min('amount');
                $bidCount = $userBids->count();
                
                $currentMaxBid = Bid::where('auction_id', $auction->id)
                    ->orderBy('amount', 'desc')
                    ->first();
                
                $auctionCurrentBid = $currentMaxBid ? $currentMaxBid->amount : $auction->starting_price;
                
                $now = Carbon::now();
                $startDate = Carbon::parse($auction->start_date);
                $endDate = Carbon::parse($auction->end_date);
                
                if ($now->lt($startDate)) {
                    $auctionStatus = 'upcoming';
                } elseif ($now->gte($startDate) && $now->lte($endDate)) {
                    $auctionStatus = 'active';
                } else {
                    $auctionStatus = 'ended';
                }
                
                if ($auctionStatus == 'ended') {
                    if ($auction->winner_email == $user->email) {
                        $overallStatus = 'won';
                    } elseif ($auction->winner_email !== null) {
                        $overallStatus = 'lost';
                    } else {
                        $overallStatus = 'no_winner';
                    }
                } elseif ($auctionStatus == 'active') {
                    if ($maxUserBid == $auctionCurrentBid) {
                        $overallStatus = 'leading';
                    } elseif ($maxUserBid < $auctionCurrentBid) {
                        $overallStatus = 'outbid';
                    } else {
                        $overallStatus = 'active';
                    }
                } else {
                    $overallStatus = 'upcoming';
                }
                
               $bids[] = (object)[
    'auction_id' => $auction->id,
    'auction_current_bid' => $auctionCurrentBid,
    'end_date' => $auction->end_date,
    'winner_name' => $auction->winner_name,
    'winner_email' => $auction->winner_email,
    'winner_notes' => $auction->winner_notes,
    'final_price' => $auction->final_price,
    'car_id' => $auction->car_id,
    'model' => $auction->car->model ?? 'Автомобиль',
    'photo' => $auction->car->photo ?? null,
    'engine' => $auction->car->engine ?? null,
    'drive' => $auction->car->drive ?? null,
    'body' => $auction->car->body ?? null,
    'bid_count' => $bidCount,
    'max_user_bid' => $maxUserBid,
    'min_user_bid' => $minUserBid,
    'overall_status' => $overallStatus,
    'auction_status' => $auctionStatus,
    'start_date' => $auction->start_date,
];
            }
            
            usort($bids, function($a, $b) {
                $lastBidA = Bid::where('auction_id', $a->auction_id)
                    ->where('user_id', Auth::id())
                    ->latest('created_at')
                    ->first();
                $lastBidB = Bid::where('auction_id', $b->auction_id)
                    ->where('user_id', Auth::id())
                    ->latest('created_at')
                    ->first();
                
                if (!$lastBidA) return 1;
                if (!$lastBidB) return -1;
                
                return $lastBidB->created_at <=> $lastBidA->created_at;
            });
        }

        $wonCars = [];
        $allAuctions = Auction::with('car')->get();
        
        foreach ($allAuctions as $auction) {
            $endDate = Carbon::parse($auction->end_date);
            $isEnded = Carbon::now()->gt($endDate);
            
            if ($isEnded && $auction->winner_email == $user->email) {
                $wonCars[] = (object)[
                    'id' => $auction->car_id,
                    'auction_title' => $auction->car->model ?? 'Автомобиль',
                    'win_price' => $auction->final_price ?? $auction->current_bid,
                    'model' => $auction->car->model ?? null,
                    'photo' => $auction->car->photo ?? null,
                    'engine' => $auction->car->engine ?? null,
                    'drive' => $auction->car->drive ?? null,
                    'body' => $auction->car->body ?? null,
                ];
            }
        }

        return view('profile.index', compact('user', 'favorites', 'configurations', 'bids', 'wonCars'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_message', 'Пожалуйста, исправьте ошибки в форме');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success_message', 'Профиль успешно обновлён');
    }

  
    public function ajaxAction(Request $request)
    {
        $user = Auth::user();

        if (!$request->has('action')) {
            return response()->json(['success' => false, 'error' => 'Action not specified'], 400);
        }

        $action = $request->input('action');

        switch ($action) {
            case 'remove_favorite':
                return $this->removeFavorite($request, $user->id);
            case 'cancel_bid':
                return $this->cancelBid($request, $user->id);
            case 'cancel_all_bids':
                return $this->cancelAllBids($request, $user->id);
            case 'delete_config':
                return $this->deleteConfig($request, $user->id);
            case 'toggle_favorite':
                return $this->toggleFavorite($request, $user->id);
            case 'get_bid_details':
                return $this->getBidDetails($request, $user->id);
            default:
                return response()->json(['success' => false, 'error' => 'Unknown action'], 400);
        }
    }


    private function removeFavorite(Request $request, $userId)
    {
        $favoriteId = $request->input('favorite_id');
        $deleted = UserFavorite::where('id', $favoriteId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }

 
    private function cancelBid(Request $request, $userId)
    {
        $bidId = $request->input('bid_id');
        $deleted = Bid::where('id', $bidId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }


    private function cancelAllBids(Request $request, $userId)
    {
        $auctionId = $request->input('auction_id');

        DB::beginTransaction();
        try {
            $currentMax = Bid::where('auction_id', $auctionId)->max('amount');

            $bids = Bid::where('auction_id', $auctionId)
                ->where('user_id', $userId)
                ->orderBy('amount', 'desc')
                ->get();

            $userHadMax = $bids->isNotEmpty() && $bids->first()->amount == $currentMax;

            $deleted = Bid::where('auction_id', $auctionId)
                ->where('user_id', $userId)
                ->delete();

            if ($deleted === 0) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => 'No bids found']);
            }

            $newMaxBid = Bid::where('auction_id', $auctionId)->max('amount');
            $auction = Auction::find($auctionId);

            if (!$auction) {
                DB::rollBack();
                return response()->json(['success' => false, 'error' => 'Auction not found']);
            }

            $startingPrice = $auction->starting_price ?? 0;
            $newCurrentBid = $newMaxBid ?: $startingPrice;

            $auction->update([
                'current_bid' => $newCurrentBid,
                'bid_count' => Bid::where('auction_id', $auctionId)->count(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ставки отменены',
                'new_current_bid' => $newCurrentBid,
                'user_had_max_bid' => $userHadMax
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    private function deleteConfig(Request $request, $userId)
    {
        $configId = $request->input('config_id');
        $deleted = UserConfig::where('id', $configId)
            ->where('user_id', $userId)
            ->delete();

        return response()->json(['success' => $deleted > 0]);
    }


    private function toggleFavorite(Request $request, $userId)
    {
        $carId = $request->input('car_id');

        $existing = UserFavorite::where('user_id', $userId)
            ->where('car_id', $carId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['success' => true, 'action' => 'removed']);
        } else {
            UserFavorite::create([
                'user_id' => $userId,
                'car_id' => $carId,
                'created_at' => now(),
            ]);
            return response()->json(['success' => true, 'action' => 'added']);
        }
    }


    private function getBidDetails(Request $request, $userId)
    {
        try {
            $auctionId = $request->input('auction_id');
            
            if (!$auctionId) {
                return response()->json(['success' => false, 'error' => 'ID аукциона не указан']);
            }

            $auction = Auction::with('car')->find($auctionId);
            if (!$auction) {
                return response()->json(['success' => false, 'error' => 'Аукцион не найден']);
            }

            $bids = Bid::where('user_id', $userId)
                ->where('auction_id', $auctionId)
                ->orderBy('amount', 'desc')
                ->get();

            if ($bids->isEmpty()) {
                return response()->json(['success' => false, 'error' => 'Ставки не найдены']);
            }

            $html = '
            <div class="bid-details-content">
                <div class="bid-car-title">' . e($auction->car->model ?? 'Автомобиль') . '</div>
                
                <div class="bid-stats">
                    <div class="bid-stat-item">
                        <div class="bid-stat-label">Всего ставок</div>
                        <div class="bid-stat-value">' . $bids->count() . '</div>
                    </div>
                    <div class="bid-stat-item">
                        <div class="bid-stat-label">Максимальная</div>
                        <div class="bid-stat-value">' . number_format($bids->max('amount'), 0, '', ' ') . ' ₽</div>
                    </div>
                    <div class="bid-stat-item">
                        <div class="bid-stat-label">Минимальная</div>
                        <div class="bid-stat-value">' . number_format($bids->min('amount'), 0, '', ' ') . ' ₽</div>
                    </div>
                </div>
                
                <div class="bids-list">';
                
                $index = 0;
                foreach ($bids as $bid) {
                    $index++;
                    $rankClass = $index === 1 ? 'high' : '';
                    $html .= '
                    <div class="bid-card">
                        <div class="bid-info">
                            <div class="bid-rank ' . $rankClass . '">' . $index . '</div>
                            <div>
                                <div class="bid-amount">' . number_format($bid->amount, 0, '', ' ') . ' ₽</div>
                            </div>
                        </div>
                        <div class="bid-date">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="14" height="14">
                                <path d="M12 8V12L15 15M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            ' . $bid->created_at->format('d.m.Y H:i') . '
                        </div>
                    </div>';
                }
                
                $html .= '
                </div>
                
                <div class="bid-footer-info">
                    Ставки отсортированы по убыванию суммы
                </div>
            </div>';
            
            return response()->json(['success' => true, 'html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Ошибка при загрузке данных: ' . $e->getMessage()]);
        }
    }
}