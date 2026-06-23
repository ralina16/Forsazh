<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    protected $allBodyTypes = [
        'кроссовер',
        'седан',
        'хэтчбек',
        'универсал',
        'купе',
        'кабриолет',
        'внедорожник',
        'минивэн',
        'пикап',
        'лимузин',
        'фургон',
    ];

    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        if ($request->filled('body')) {
            $query->where('body', $request->input('body'));
        }

        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }

        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }

        $brands = CarBrand::select('name')
            ->where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $brands->each(function ($brand) {
            $brand->name_lower = strtolower($brand->name);
        });

        $existingBodyTypes = Car::select('body')
            ->whereNotNull('body')
            ->where('body', '!=', '')
            ->distinct()
            ->orderBy('body')
            ->pluck('body')
            ->map(function ($body) {
                return strtolower(trim($body));
            })
            ->toArray();

        $bodyTypes = $this->allBodyTypes;

        $cars = $query->orderBy('created_at', 'desc')->get();

        $cars = $cars->map(function ($car) {
            $car->brand_lower = strtolower($car->brand ?? '');
            $car->body_type_lower = strtolower($car->body ?? '');
            $car->display_model = mb_strlen($car->model) > 20 ? mb_substr($car->model, 0, 20).'...' : $car->model;
            $car->badge_class = $car->condition === 'Новая' ? 'badge-new' : 'badge-used';
            $car->badge_text = $car->condition === 'Новая' ? 'NEW' : 'USED';
            $car->formatted_price = number_format($car->price, 0, '', ' ').' ₽';
            $car->catalog_photo_url = $car->catalog_photo ? asset('storage/'.$car->catalog_photo) : asset('assets/images/offer/1.jpg');
            $car->brand_name = $car->brand ?? '';
            $car->is_new = $car->condition === 'Новая';

            return $car;
        });

        return view('catalog.index', compact('cars', 'brands', 'bodyTypes', 'existingBodyTypes'));
    }

    public function show(Car $car)
    {
        $car->load('images');

        $mainImage = $car->images()->where('is_main', true)->first()?->path
            ?? $car->catalog_photo
            ?? $car->photo
            ?? 'assets/images/one_car/placeholder.png';

        $isFavorite = false;
        if (Auth::check()) {
            $isFavorite = UserFavorite::where('user_id', Auth::id())
                ->where('car_id', $car->id)
                ->exists();
        }

        // Похожие авто
        $similarCars = Car::where('id', '!=', $car->id)
            ->where(function ($q) use ($car) {
                $q->where('brand', $car->brand)
                    ->orWhere('body', $car->body);
            })
            ->limit(4)
            ->get();

        $similarCars = $similarCars->map(function ($similarCar) {
            $similarCar->display_model = mb_strlen($similarCar->model) > 20 ? mb_substr($similarCar->model, 0, 20).'...' : $similarCar->model;
            $similarCar->formatted_price = number_format($similarCar->price, 0, '', ' ').' ₽';
            $similarCar->catalog_photo_url = $similarCar->catalog_photo ? asset('storage/'.$similarCar->catalog_photo) : asset('assets/images/offer/1.jpg');
            $similarCar->badge_class = $similarCar->condition === 'Новая' ? 'badge-new' : 'badge-used';
            $similarCar->badge_text = $similarCar->condition === 'Новая' ? 'NEW' : 'USED';

            return $similarCar;
        });

        return view('catalog.show', compact('car', 'mainImage', 'isFavorite', 'similarCars'));
    }

    public function getBodyIcon($bodyType)
    {
        $icons = [
            'кроссовер' => 'image-1.png',
            'седан' => 'image-2.png',
            'универсал' => 'image-3.png',
            'хэтчбек' => 'image-4.png',
            'пикап' => 'image-5.png',
            'кабриолет' => 'image-6.png',
            'внедорожник' => 'image-1.png',
            'минивэн' => 'image-4.png',
            'купе' => 'image-2.png',
            'лимузин' => 'image-2.png',
            'фургон' => 'image-5.png',
        ];

        return $icons[strtolower($bodyType)] ?? 'image-1.png';
    }
}
