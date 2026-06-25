<?php

use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\AdminCarController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ConfiguratorController;
use App\Http\Controllers\CreditController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\MiniChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicAuctionController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TradeInController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/about', 'pages.about')->name('about');

Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CarController::class, 'index'])->name('index');
    Route::get('/{car}', [CarController::class, 'show'])->name('show');
});

Route::get('/configurator', [ConfiguratorController::class, 'index'])->name('configurator.index');

Route::get('/credit', [CreditController::class, 'index'])->name('credit');
Route::post('/credit', [CreditController::class, 'store'])->name('credit.store');

Route::get('/trade-in', [TradeInController::class, 'index'])->name('trade-in');
Route::post('/trade-in', [TradeInController::class, 'store'])->name('trade-in.store');

Route::get('/insurance', [InsuranceController::class, 'index'])->name('insurance');
Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');
Route::get('/check-name', [AuthController::class, 'checkName'])->name('check.name');

Route::post('/requests', [RequestController::class, 'store'])->name('requests.store');

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

Route::middleware('web')->prefix('chat')->name('chat.')->group(function () {
    Route::post('/check', [MiniChatController::class, 'check'])->name('check');
    Route::get('/messages', [MiniChatController::class, 'getMessages'])->name('messages');
    Route::post('/send', [MiniChatController::class, 'sendMessage'])->name('send');
    Route::post('/ai', [MiniChatController::class, 'aiResponse'])->name('ai');
    Route::post('/clear', [MiniChatController::class, 'clearChat'])->name('clear');
    Route::post('/complaint', [MiniChatController::class, 'sendComplaint'])->name('complaint');
    Route::post('/price', [MiniChatController::class, 'getPriceList'])->name('price');
    Route::post('/register', [MiniChatController::class, 'register'])->name('register');
    Route::post('/mark-read', [MiniChatController::class, 'markAsRead'])->name('read');
});

Route::prefix('auction')->name('auction.')->group(function () {
    Route::get('/', [PublicAuctionController::class, 'index'])->name('index');
});

Route::get('/media/{id}', function ($id) {
    $media = App\Models\CarMedia::findOrFail($id);

    return response()->file(storage_path('app/public/'.$media->file_path));
})->name('media.show');

Route::post('/email-verification/send', [EmailVerificationController::class, 'send'])
    ->name('verification.send');
Route::post('/email-verification/verify', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');

Route::middleware('auth')->group(function () {

    Route::get('/account', [ProfileController::class, 'index'])->name('account');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/action', [ProfileController::class, 'ajaxAction'])->name('profile.action');

    Route::middleware('auth')->prefix('configurator')->name('configurator.')->group(function () {
        Route::get('/{carConfig}', [ConfiguratorController::class, 'show'])
            ->name('show')
            ->whereNumber('carConfig');
        Route::get('/history', [ConfiguratorController::class, 'getHistory'])->name('history');
        Route::post('/ai', [ConfiguratorController::class, 'aiQuestion'])->name('ai');
        Route::post('/save', [ConfiguratorController::class, 'save'])->name('save');
    });

    Route::prefix('favorites')->name('favorites.')->group(function () {
        Route::post('/toggle', function (Request $request) {
            $carId = $request->input('car_id');
            $userId = Auth::id();

            $favorite = \App\Models\UserFavorite::where('user_id', $userId)
                ->where('car_id', $carId)
                ->first();

            if ($favorite) {
                $favorite->delete();

                return response()->json(['success' => true, 'action' => 'removed']);
            }

            \App\Models\UserFavorite::create([
                'user_id' => $userId,
                'car_id' => $carId,
            ]);

            return response()->json(['success' => true, 'action' => 'added']);
        })->name('toggle');
    });

    Route::prefix('auction')->name('auction.')->group(function () {
        Route::get('/{car}', [PublicAuctionController::class, 'show'])->name('show');
        Route::post('/{auction}/bid', [PublicAuctionController::class, 'storeBid'])->name('bid.store');
        Route::delete('/bid/{bid}', [PublicAuctionController::class, 'destroyBid'])->name('bid.destroy');
        Route::post('/{auction}/payment', [PublicAuctionController::class, 'processPayment'])->name('payment');
        Route::post('/check-bids', [PublicAuctionController::class, 'checkMyBids'])->name('checkBids');
        Route::get('/{auction}/check-payment', [PublicAuctionController::class, 'checkPayment'])->name('checkPayment');
        Route::post('/check-payment', [PublicAuctionController::class, 'checkPayment'])->name('checkPayment.post');
        Route::post('/get-data', [PublicAuctionController::class, 'getAuctionData'])->name('getData');

    });
});

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('index');

    Route::prefix('cars')->name('cars.')->group(function () {
        Route::get('/', [AdminCarController::class, 'index'])->name('index');
        Route::post('/', [AdminCarController::class, 'store'])->name('store');
        Route::put('/{car}', [AdminCarController::class, 'update'])->name('update');
        Route::delete('/{car}', [AdminCarController::class, 'destroy'])->name('destroy');

        Route::get('/brands', [AdminCarController::class, 'brands'])->name('brands');
        Route::post('/brands', [AdminCarController::class, 'addBrand'])->name('addBrand');
        Route::put('/brands/{brandId}', [AdminCarController::class, 'updateBrand'])->name('updateBrand');
        Route::delete('/brands/{brandId}', [AdminCarController::class, 'deleteBrand'])->name('deleteBrand');
        Route::patch('/brands/{brandId}/toggle', [AdminCarController::class, 'toggleBrand'])->name('toggleBrand');
        Route::post('/brands/reorder', [AdminCarController::class, 'updateBrandOrder'])->name('updateBrandOrder');

        Route::delete('/images/{imageId}', [AdminCarController::class, 'deleteImage'])->name('deleteImage');
    });

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/password', [AdminUserController::class, 'changePassword'])->name('users.password');
    Route::put('/users/{user}/role', [AdminUserController::class, 'changeRole'])->name('users.role');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::put('/reviews/{review}/toggle', [AdminReviewController::class, 'toggle'])->name('reviews.toggle');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::post('/reviews/publish-all', [AdminReviewController::class, 'publishAll'])->name('reviews.publishAll');

    Route::resource('car-configs', \App\Http\Controllers\Admin\CarConfigController::class)->except(['show']);
    Route::delete('car-media/{id}', [\App\Http\Controllers\Admin\CarConfigController::class, 'deleteMedia'])->name('car-configs.delete-media');

    Route::prefix('auctions')->name('auctions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AuctionController::class, 'index'])->name('index');
        Route::post('/cars', [\App\Http\Controllers\Admin\AuctionController::class, 'storeCar'])->name('cars.store');
        Route::put('/cars/{car}', [\App\Http\Controllers\Admin\AuctionController::class, 'updateCar'])->name('cars.update');
        Route::delete('/cars/{car}', [\App\Http\Controllers\Admin\AuctionController::class, 'destroyCar'])->name('cars.destroy');
        Route::post('/', [\App\Http\Controllers\Admin\AuctionController::class, 'storeAuction'])->name('store');
        Route::put('/{auction}', [\App\Http\Controllers\Admin\AuctionController::class, 'updateAuction'])->name('update');
        Route::delete('/{auction}', [\App\Http\Controllers\Admin\AuctionController::class, 'destroyAuction'])->name('destroy');
        Route::post('/{auction}/determine-winner', [\App\Http\Controllers\Admin\AuctionController::class, 'determineWinner'])->name('determineWinner');
        Route::post('/cars/{car}/photos', [\App\Http\Controllers\Admin\AuctionController::class, 'uploadPhotos'])->name('cars.photos.upload');
        Route::delete('/cars/{car}/photos/{index}', [\App\Http\Controllers\Admin\AuctionController::class, 'deletePhoto'])->name('cars.photos.delete');
        Route::post('/cars/{car}/photos/{index}/set-main', [\App\Http\Controllers\Admin\AuctionController::class, 'setMainPhoto'])->name('cars.photos.setMain');
        Route::delete('/admin/auctions/cars/{car}', [\App\Http\Controllers\Admin\AuctionController::class, 'destroy'])
            ->name('admin.auctions.cars.destroy');
    });

    Route::get('/chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat');
    Route::post('/chat/get-users', [\App\Http\Controllers\Admin\ChatController::class, 'getUsers'])->name('chat.getUsers');
    Route::post('/chat/get-user-info', [\App\Http\Controllers\Admin\ChatController::class, 'getUserInfo'])->name('chat.getUserInfo');
    Route::post('/chat/get-messages', [\App\Http\Controllers\Admin\ChatController::class, 'getMessages'])->name('chat.getMessages');
    Route::post('/chat/send-message', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::post('/chat/clear-messages', [\App\Http\Controllers\Admin\ChatController::class, 'clearMessages'])->name('chat.clearMessages');
    Route::post('/chat/delete-user', [\App\Http\Controllers\Admin\ChatController::class, 'deleteUser'])->name('chat.deleteUser');
    Route::post('/chat/create-user', [\App\Http\Controllers\Admin\ChatController::class, 'createUser'])->name('chat.createUser');
    Route::post('/chat/delete-message', [\App\Http\Controllers\Admin\ChatController::class, 'deleteMessage'])->name('chat.deleteMessage');

    Route::get('/requests', [RequestController::class, 'adminIndex'])->name('requests.index');

    Route::post('/requests/trade-in', [RequestController::class, 'storeTradeIn'])->name('requests.trade-in.store');
    Route::post('/requests/trade-in/{id}', [RequestController::class, 'updateTradeIn'])->name('requests.trade-in.update');
    Route::post('/requests/trade-in/{id}/delete', [RequestController::class, 'destroyTradeIn'])->name('requests.trade-in.destroy');

    Route::post('/requests/credit', [RequestController::class, 'storeCredit'])->name('requests.credit.store');
    Route::post('/requests/credit/{id}', [RequestController::class, 'updateCredit'])->name('requests.credit.update');
    Route::post('/requests/credit/{id}/delete', [RequestController::class, 'destroyCredit'])->name('requests.credit.destroy');

    Route::post('/requests/insurance', [RequestController::class, 'storeInsurance'])->name('requests.insurance.store');
    Route::post('/requests/insurance/{id}', [RequestController::class, 'updateInsurance'])->name('requests.insurance.update');
    Route::post('/requests/insurance/{id}/delete', [RequestController::class, 'destroyInsurance'])->name('requests.insurance.destroy');

    Route::post('/requests/general', [RequestController::class, 'storeGeneralAdmin'])->name('requests.general.store');
    Route::post('/requests/general/{id}', [RequestController::class, 'updateGeneralAdmin'])->name('requests.general.update');
    Route::post('/requests/general/{id}/delete', [RequestController::class, 'destroyGeneralAdmin'])->name('requests.general.destroy');

    Route::post('/users/toggle-email-verification', [AdminUserController::class, 'toggleEmailVerification'])
        ->name('users.toggle-email-verification');
});
