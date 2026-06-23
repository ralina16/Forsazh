<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuctionCar;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $cars = AuctionCar::orderBy('created_at', 'desc')->get();
        $auctions = Auction::with('car')->orderBy('created_at', 'desc')->get();

        $stats = [
            'active_auctions' => $auctions->filter(function($auction) {
                return $auction->status === 'active';
            })->count(),
            'total_price'     => $cars->sum('price'),
            'total_bids'      => Bid::count(),
            'total_cars'      => $cars->count(),
        ];

        $editCar = null;
        if ($request->has('edit_id')) {
            $editCar = AuctionCar::find($request->edit_id);
        }

        $managePhotosCar = null;
        $carPhotos = [];
        if ($request->has('manage_photos')) {
            $managePhotosCar = AuctionCar::find($request->manage_photos);
            if ($managePhotosCar) {
                $carPhotos = $managePhotosCar->additional_photos ?? [];
            }
        }

        $auctionBids = null;
        $bids = [];
        if ($request->has('view_bids')) {
            $auctionBids = Auction::with('car', 'bids.user')->find($request->view_bids);
            if ($auctionBids) {
                $bids = $auctionBids->bids;
            }
        }

        return view('admin.auctions.index', compact(
            'cars', 'auctions', 'stats', 'editCar', 'managePhotosCar', 'carPhotos', 'auctionBids', 'bids'
        ));
    }


    public function storeCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'model'         => 'required|string|max:100',
            'drive'         => 'required|string|max:50',
            'engine'        => 'required|string|max:50',
            'fuel'          => 'required|string|max:50',
            'mileage'       => 'required|string|max:50',
            'condition'     => 'required|string|max:50',
            'owners'        => 'nullable|integer|min:0|max:99',
            'transmissions' => 'nullable|integer|min:1|max:12',
            'trunk'         => 'required|string|max:50',
            'gearbox'       => 'required|string|max:50',
            'body'          => 'required|string|max:50',
            'price'         => 'required|integer|min:1000|max:1000000000',
            'description'   => 'nullable|string|max:2000',
            'main_photo'    => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            '*.required' => 'Поле обязательно для заполнения.',
            'price.min' => 'Цена должна быть не менее 1000 ₽.',
            'main_photo.required' => 'Загрузите основное фото автомобиля.',
            'main_photo.image' => 'Файл должен быть изображением.',
            'main_photo.max' => 'Размер файла не должен превышать 10 МБ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('main_photo')->store('auction_cars', 'public');

        $car = AuctionCar::create([
            'model' => $request->model,
            'photo' => $path,
            'additional_photos' => [],
            'drive' => $request->drive,
            'engine' => $request->engine,
            'fuel' => $request->fuel,
            'mileage' => $request->mileage,
            'condition' => $request->condition,
            'owners' => $request->owners,
            'transmissions' => $request->transmissions,
            'trunk' => $request->trunk,
            'gearbox' => $request->gearbox,
            'body' => $request->body,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Автомобиль добавлен!']);
    }

    public function updateCar(Request $request, $id)
    {
        $car = AuctionCar::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'model'         => 'required|string|max:100',
            'drive'         => 'required|string|max:50',
            'engine'        => 'required|string|max:50',
            'fuel'          => 'required|string|max:50',
            'mileage'       => 'required|string|max:50',
            'condition'     => 'required|string|max:50',
            'owners'        => 'nullable|integer|min:0|max:99',
            'transmissions' => 'nullable|integer|min:1|max:12',
            'trunk'         => 'required|string|max:50',
            'gearbox'       => 'required|string|max:50',
            'body'          => 'required|string|max:50',
            'price'         => 'required|integer|min:1000|max:1000000000',
            'description'   => 'nullable|string|max:2000',
            'main_photo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            '*.required' => 'Поле обязательно для заполнения.',
            'price.min' => 'Цена должна быть не менее 1000 ₽.',
            'main_photo.image' => 'Файл должен быть изображением.',
            'main_photo.max' => 'Размер файла не должен превышать 10 МБ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('main_photo');

        if ($request->hasFile('main_photo')) {
            if ($car->photo) {
                Storage::disk('public')->delete($car->photo);
            }
            $data['photo'] = $request->file('main_photo')->store('auction_cars', 'public');
        }

        $car->update($data);

        return response()->json(['success' => true, 'message' => 'Автомобиль обновлён!']);
    }


    public function destroyCar($id)
    {
        $car = AuctionCar::findOrFail($id);
        if ($car->photo) {
            Storage::disk('public')->delete($car->photo);
        }
        if ($car->additional_photos) {
            foreach ($car->additional_photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        $car->delete();

        return response()->json(['success' => true, 'message' => 'Автомобиль удалён!']);
    }


    public function storeAuction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'car_id'         => 'required|exists:auction_cars,id',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'starting_price' => 'required|integer|min:1000|max:1000000000',
            'reserve_price'  => 'required|integer|min:0|max:1000000000',
        ], [
            'car_id.required' => 'Выберите автомобиль.',
            'start_date.required' => 'Укажите дату начала.',
            'end_date.required' => 'Укажите дату окончания.',
            'end_date.after' => 'Дата окончания должна быть позже даты начала.',
            'starting_price.required' => 'Укажите начальную цену.',
            'starting_price.min' => 'Начальная цена должна быть не менее 1000 ₽.',
            'reserve_price.required' => 'Укажите резервную цену.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $auction = Auction::create([
            'car_id'         => $request->car_id,
            'start_date'     => $startDate,
            'end_date'       => $endDate,
            'starting_price' => $request->starting_price,
            'reserve_price'  => $request->reserve_price,
        ]);

        $statusText = $auction->status === 'upcoming' ? 'Скоро начнется' : ($auction->status === 'active' ? 'Активен' : 'Завершен');

        return response()->json(['success' => true, 'message' => "Аукцион создан! Статус: {$statusText}"]);
    }


   public function updateAuction(Request $request, $id)
{
    $auction = Auction::findOrFail($id);

    $rules = [
        'start_date'     => 'required|date',
        'end_date'       => 'required|date|after:start_date',
        'starting_price' => 'required|integer|min:1000|max:1000000000',
        'reserve_price'  => 'required|integer|min:0|max:1000000000',
    ];

    if ($request->has('status') && $request->status == 'ended') {
        $rules['winner_name']  = 'required|string|max:100';
        $rules['winner_email'] = 'required|email|max:255';
        $rules['final_price']  = 'required|integer|min:0';
    }

    $rules['winner_notes'] = 'nullable|string|max:1000';

    $messages = [
        'start_date.required' => 'Укажите дату начала.',
        'end_date.required' => 'Укажите дату окончания.',
        'end_date.after' => 'Дата окончания должна быть позже даты начала.',
        'starting_price.required' => 'Укажите начальную цену.',
        'starting_price.min' => 'Начальная цена должна быть не менее 1000 ₽.',
        'reserve_price.required' => 'Укажите резервную цену.',
        'winner_name.required' => 'Укажите имя победителя.',
        'winner_email.required' => 'Укажите email победителя.',
        'final_price.required' => 'Укажите итоговую цену.',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    $data = [
        'start_date'     => $startDate,
        'end_date'       => $endDate,
        'starting_price' => $request->starting_price,
        'reserve_price'  => $request->reserve_price,
    ];

    // Обновляем winner_notes всегда, если оно пришло
    if ($request->has('winner_notes')) {
        $data['winner_notes'] = $request->winner_notes;
    }

    // Обновляем winner-поля только если переданы все данные победителя
    if ($request->has('status') && $request->status == 'ended' && $request->filled('winner_name')) {
        $data['winner_name']  = $request->winner_name;
        $data['winner_email'] = $request->winner_email;
        $data['final_price']  = $request->final_price;
    }
    // Если winner-поля не переданы — НЕ трогаем их, оставляем как есть в БД

    $auction->update($data);

    $statusText = $auction->status === 'upcoming' ? 'Скоро начнется' : ($auction->status === 'active' ? 'Активен' : 'Завершен');

    return response()->json(['success' => true, 'message' => "Аукцион обновлён! Статус: {$statusText}"]);
}


    public function destroyAuction($id)
    {
        $auction = Auction::findOrFail($id);
        $auction->delete();

        return response()->json(['success' => true, 'message' => 'Аукцион удалён!']);
    }


     public function determineWinner($id)
    {
        $auction = Auction::with('bids.user')->findOrFail($id);

        if ($auction->status !== 'active') {
            return response()->json(['success' => false, 'error' => 'Аукцион не активен.'], 422);
        }

        $topBid = $auction->bids()->orderBy('amount', 'desc')->orderBy('created_at', 'asc')->first();

        DB::beginTransaction();
        try {
            if ($topBid) {
                $auction->bids()->update(['is_winner' => false]);

                $topBid->update(['is_winner' => true]);

                $auction->update([
                    'winner_name'  => $topBid->user->name ?? '',
                    'winner_email' => $topBid->user->email ?? '',
                    'final_price'  => $topBid->amount,
                    'end_date'     => now(),
                ]);

                $message = "Победитель: {$topBid->user->name}, сумма: " .
                          number_format($topBid->amount, 0, '.', ' ') . ' ₽';
            } else {
                $auction->update(['end_date' => now()]);
                $message = 'Аукцион завершён. Ставок не было.';
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Ошибка при завершении аукциона: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
{
    $car = AuctionCar::findOrFail($id);

    $linkedAuctions = Auction::where('car_id', $car->id)->count();

    if ($linkedAuctions > 0) {
        return response()->json([
            'success' => false,
            'error' => 'Удаление невозможно: у автомобиля имеются связанные аукционы. Сначала удалите аукционы.'
        ], 422);
    }

    if ($car->photo) {
        Storage::disk('public')->delete($car->photo);
    }

    if (!empty($car->additional_photos)) {
        foreach ($car->additional_photos as $photo) {
            Storage::disk('public')->delete($photo);
        }
    }

    $car->delete();

    return response()->json(['success' => true, 'message' => 'Автомобиль удалён.']);
}

    public function uploadPhotos(Request $request, $carId)
    {
        $car = AuctionCar::findOrFail($carId);

        $validator = Validator::make($request->all(), [
            'photos'   => 'required|array|max:10',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'photos.required' => 'Выберите файлы.',
            'photos.*.image' => 'Каждый файл должен быть изображением.',
            'photos.*.max' => 'Размер каждого файла не должен превышать 10 МБ.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $paths = [];
        foreach ($request->file('photos') as $file) {
            $paths[] = $file->store('auction_cars', 'public');
        }

        $existing = $car->additional_photos ?? [];
        $car->additional_photos = array_merge($existing, $paths);
        $car->save();

        return response()->json(['success' => true, 'message' => 'Фото загружены!']);
    }


    public function deletePhoto($carId, $index)
    {
        $car = AuctionCar::findOrFail($carId);
        $allPhotos = $car->additional_photos ?? [];

        if (isset($allPhotos[$index])) {
            Storage::disk('public')->delete($allPhotos[$index]);
            unset($allPhotos[$index]);
            $car->additional_photos = array_values($allPhotos);
            $car->save();
        }

        return response()->json(['success' => true, 'message' => 'Фото удалено.']);
    }


    public function setMainPhoto($carId, $index)
    {
        $car = AuctionCar::findOrFail($carId);
        $allPhotos = $car->additional_photos ?? [];

        if (isset($allPhotos[$index])) {
            $currentMain = $car->photo;
            $car->photo = $allPhotos[$index];
            $allPhotos[$index] = $currentMain;
            $car->additional_photos = array_values($allPhotos);
            $car->save();
        }

        return response()->json(['success' => true, 'message' => 'Основное фото обновлено.']);
    }
}