<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarBrand;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminCarController extends Controller
{
    protected $uploadDir = 'assets/images/cars';

    protected $brandUploadDir = 'assets/images/marks';

    public function index()
    {
        $cars = Car::with('images')->orderBy('created_at', 'desc')->get();
        $carBrands = \App\Models\CarBrand::orderBy('name')->paginate(10);
        $existingCarBrands = CarBrand::active()->ordered()->pluck('name');

        return view('admin.cars.index', compact('cars', 'carBrands', 'existingCarBrands'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'max' => 'Поле :attribute не должно превышать :max символов.',
            'min' => 'Поле :attribute должно быть не менее :min.',
            'numeric' => 'Поле :attribute должно быть числом.',
            'integer' => 'Поле :attribute должно быть целым числом.',
            'in' => 'Выбранное значение для :attribute некорректно.',
            'image' => 'Файл должен быть изображением.',
            'mimes' => 'Изображение должно быть формата: :values.',
            'catalog_photo.required' => 'Загрузите главное фото для каталога.',
            'catalog_photo.max' => 'Фото не должно превышать 4 МБ.',
            'images.required' => 'Загрузите хотя бы одно дополнительное фото.',
            'images.*.image' => 'Каждый файл должен быть изображением.',
            'images.*.max' => 'Каждое изображение не должно превышать 5 МБ.',
        ];

        $rules = [
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:30',
            'drive' => 'required|in:Полный,Передний,Задний',
            'engine' => 'required|numeric|min:0.1|max:20.0',
            'fuel' => 'required|in:Бензин,Дизель,Гибрид,Электро',
            'mileage' => 'required|integer|min:0|max:10000000',
            'condition' => 'required|in:Новая,Не битая,Битая,Аварийная',
            'owners' => 'required|integer|min:0|max:100',
            'transmissions' => 'required|integer|min:1|max:20',
            'trunk' => 'required|integer|min:1|max:10000',
            'gearbox' => 'required|in:Автомат,Механика,Робот,Вариатор',
            'body' => 'required|in:Кроссовер,Седан,Хэтчбек,Универсал,Купе,Кабриолет,Внедорожник,Минивэн,Пикап',
            'price' => 'required|integer|min:0|max:1000000000',
            'description' => 'required|string|max:2000',
            'catalog_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        if ($request->condition === 'Новая') {
            $request->merge(['mileage' => 0, 'owners' => 0]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'addCarModal');
        }

        $validated = $validator->validated();

        $brand = CarBrand::firstOrCreate(
            ['name' => $validated['brand']],
            [
                'sort_order' => CarBrand::max('sort_order') + 1,
                'is_active' => true,
            ]
        );

        DB::beginTransaction();
        try {
            $catalogPhotoPath = $this->uploadCatalogPhoto($request->file('catalog_photo'));

            $car = Car::create([
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'drive' => $validated['drive'],
                'engine' => $validated['engine'],
                'fuel' => $validated['fuel'],
                'mileage' => $validated['mileage'],
                'condition' => $validated['condition'],
                'owners' => $validated['owners'],
                'transmissions' => $validated['transmissions'],
                'trunk' => $validated['trunk'],
                'gearbox' => $validated['gearbox'],
                'body' => $validated['body'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'catalog_photo' => $catalogPhotoPath,
            ]);

            $this->saveMultipleImages($request->file('images'), $car->id);

            $firstImage = CarImage::where('car_id', $car->id)->orderBy('id')->first();
            if ($firstImage) {
                $car->photo = $firstImage->path;
                $car->save();

                CarImage::where('car_id', $car->id)->update(['is_main' => 0]);
                $firstImage->is_main = 1;
                $firstImage->save();
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Автомобиль успешно добавлен.']);
            }

            return redirect()->route('admin.cars.index')->with('success', 'Автомобиль успешно добавлен.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка добавления автомобиля: '.$e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Ошибка базы данных при добавлении автомобиля.'], 500);
            }

            return redirect()->back()
                ->withErrors(['Ошибка базы данных при добавлении автомобиля.'])
                ->withInput()
                ->with('open_modal', 'addCarModal');
        }
    }

    public function update(Request $request, $id)
    {
        $car = Car::findOrFail($id);

        $messages = [
            'required' => 'Поле :attribute обязательно для заполнения.',
            'max' => 'Поле :attribute не должно превышать :max символов.',
            'min' => 'Поле :attribute должно быть не менее :min.',
            'numeric' => 'Поле :attribute должно быть числом.',
            'integer' => 'Поле :attribute должно быть целым числом.',
            'in' => 'Выбранное значение для :attribute некорректно.',
            'image' => 'Файл должен быть изображением.',
            'mimes' => 'Изображение должно быть формата: :values.',
            'catalog_photo.max' => 'Фото не должно превышать 4 МБ.',
            'images.*.image' => 'Каждый файл должен быть изображением.',
            'images.*.max' => 'Каждое изображение не должно превышать 5 МБ.',
        ];

        $rules = [
            'brand' => 'required|string|max:20',
            'model' => 'required|string|max:30',
            'drive' => 'required|in:Полный,Передний,Задний',
            'engine' => 'required|numeric|min:0.1|max:20.0',
            'fuel' => 'required|in:Бензин,Дизель,Гибрид,Электро',
            'mileage' => 'required|integer|min:0|max:10000000',
            'condition' => 'required|in:Новая,Не битая,Битая,Аварийная',
            'owners' => 'required|integer|min:0|max:100',
            'transmissions' => 'required|integer|min:1|max:20',
            'trunk' => 'required|integer|min:1|max:10000',
            'gearbox' => 'required|in:Автомат,Механика,Робот,Вариатор',
            'body' => 'required|in:Кроссовер,Седан,Хэтчбек,Универсал,Купе,Кабриолет,Внедорожник,Минивэн,Пикап',
            'price' => 'required|integer|min:0|max:1000000000',
            'description' => 'required|string|max:2000',
            'catalog_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];

        if ($request->condition === 'Новая') {
            $request->merge(['mileage' => 0, 'owners' => 0]);
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editCarModal')
                ->with('edit_car_id', $id);
        }

        $validated = $validator->validated();

        $brand = CarBrand::firstOrCreate(
            ['name' => $validated['brand']],
            [
                'sort_order' => CarBrand::max('sort_order') + 1,
                'is_active' => true,
            ]
        );

        DB::beginTransaction();
        try {
            $catalogPhotoPath = $car->catalog_photo;

            if ($request->has('delete_catalog_photo') && $request->delete_catalog_photo == '1') {
                if ($catalogPhotoPath) {
                    Storage::disk('public')->delete($catalogPhotoPath);
                }
                $catalogPhotoPath = null;
            }

            if ($request->hasFile('catalog_photo')) {
                $newPath = $this->uploadCatalogPhoto($request->file('catalog_photo'));
                if ($newPath) {
                    if ($catalogPhotoPath) {
                        Storage::disk('public')->delete($catalogPhotoPath);
                    }
                    $catalogPhotoPath = $newPath;
                }
            }

            if ($request->hasFile('images')) {
                $this->saveMultipleImages($request->file('images'), $car->id);
            }

            $car->update([
                'brand' => $validated['brand'],
                'model' => $validated['model'],
                'drive' => $validated['drive'],
                'engine' => $validated['engine'],
                'fuel' => $validated['fuel'],
                'mileage' => $validated['mileage'],
                'condition' => $validated['condition'],
                'owners' => $validated['owners'],
                'transmissions' => $validated['transmissions'],
                'trunk' => $validated['trunk'],
                'gearbox' => $validated['gearbox'],
                'body' => $validated['body'],
                'price' => $validated['price'],
                'description' => $validated['description'],
                'catalog_photo' => $catalogPhotoPath,
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Автомобиль успешно обновлён.']);
            }

            return redirect()->route('admin.cars.index')->with('success', 'Автомобиль успешно обновлён.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Ошибка обновления автомобиля: '.$e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Ошибка базы данных при обновлении автомобиля.'], 500);
            }

            return redirect()->back()
                ->withErrors(['Ошибка базы данных при обновлении автомобиля.'])
                ->withInput()
                ->with('open_modal', 'editCarModal')
                ->with('edit_car_id', $id);
        }
    }

    public function destroy($id)
    {
        $car = Car::findOrFail($id);

        if ($car->catalog_photo) {
            Storage::disk('public')->delete($car->catalog_photo);
        }

        foreach ($car->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        if ($car->photo) {
            Storage::disk('public')->delete($car->photo);
        }

        $car->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Автомобиль удалён.');
    }

    public function deleteImage($imageId)
    {
        $image = CarImage::findOrFail($imageId);
        $carId = $image->car_id;

        Storage::disk('public')->delete($image->path);

        if ($image->is_main) {
            $nextImage = CarImage::where('car_id', $carId)
                ->where('id', '!=', $imageId)
                ->orderBy('id')
                ->first();

            if ($nextImage) {
                $nextImage->is_main = 1;
                $nextImage->save();
                Car::where('id', $carId)->update(['photo' => $nextImage->path]);
            } else {
                Car::where('id', $carId)->update(['photo' => null]);
            }
        }

        $image->delete();

        return redirect()->route('admin.cars.index')->with('success', 'Изображение удалено.');
    }

    public function addBrand(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|string|max:100|unique:car_brands,name',
            'brand_icon' => 'nullable|image|mimes:png,jpeg,jpg,gif,svg,webp|max:2048',
        ], [
            'brand_name.required' => 'Название марки обязательно.',
            'brand_name.unique' => 'Такая марка уже существует.',
            'brand_icon.image' => 'Иконка должна быть изображением.',
            'brand_icon.mimes' => 'Иконка должна быть формата: png, jpeg, jpg, gif, svg, webp.',
            'brand_icon.max' => 'Иконка не должна превышать 2 МБ.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'addBrandModal');
        }

        $iconPath = null;
        if ($request->hasFile('brand_icon')) {
            $extension = $request->file('brand_icon')->getClientOriginalExtension();
            $filename = 'brand_'.time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
            $iconPath = $request->file('brand_icon')->storeAs($this->brandUploadDir, $filename, 'public');
        }

        $maxSortOrder = CarBrand::max('sort_order') ?? 0;

        CarBrand::create([
            'name' => $request->brand_name,
            'icon' => $iconPath,
            'sort_order' => $maxSortOrder + 1,
            'is_active' => true,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Марка успешно добавлена.']);
        }

        return redirect()->route('admin.cars.index')->with('brand_success', 'Марка успешно добавлена.');
    }

    public function updateBrand(Request $request, $brandId)
    {
        $brand = CarBrand::findOrFail($brandId);

        $validator = Validator::make($request->all(), [
            'brand_name' => 'required|string|max:100|unique:car_brands,name,'.$brandId,
            'brand_icon' => 'nullable|image|mimes:png,jpeg,jpg,gif,svg,webp|max:2048',
            'delete_icon' => 'nullable|in:1',
        ], [
            'brand_name.required' => 'Название марки обязательно.',
            'brand_name.unique' => 'Такая марка уже существует.',
            'brand_icon.image' => 'Иконка должна быть изображением.',
            'brand_icon.mimes' => 'Иконка должна быть формата: png, jpeg, jpg, gif, svg, webp.',
            'brand_icon.max' => 'Иконка не должна превышать 2 МБ.',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('open_modal', 'editBrandModal')
                ->with('edit_brand_id', $brandId);
        }

        $data = ['name' => $request->brand_name];

        if ($request->delete_icon == '1' && $brand->icon) {
            Storage::disk('public')->delete($brand->icon);
            $data['icon'] = null;
        }

        if ($request->hasFile('brand_icon')) {
            if ($brand->icon) {
                Storage::disk('public')->delete($brand->icon);
            }
            $extension = $request->file('brand_icon')->getClientOriginalExtension();
            $filename = 'brand_'.time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
            $data['icon'] = $request->file('brand_icon')->storeAs($this->brandUploadDir, $filename, 'public');
        }

        $brand->update($data);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Марка успешно обновлена.']);
        }

        return redirect()->route('admin.cars.index')->with('brand_success', 'Марка успешно обновлена.');
    }

    public function deleteBrand($brandId)
    {
        $brand = CarBrand::findOrFail($brandId);

        $usedCount = Car::where('brand', $brand->name)->count();

        if ($usedCount > 0) {
            return back()->withErrors(['brand_errors' => "Невозможно удалить марку: она используется в {$usedCount} автомобиле(ях)."]);
        }

        if ($brand->icon) {
            Storage::disk('public')->delete($brand->icon);
        }

        $brand->delete();

        return redirect()->route('admin.cars.index')->with('brand_success', 'Марка удалена.');
    }

    public function toggleBrand($brandId)
    {
        $brand = CarBrand::findOrFail($brandId);
        $brand->is_active = ! $brand->is_active;
        $brand->save();

        return redirect()->route('admin.cars.index')->with('brand_success', 'Статус марки обновлён.');
    }

    public function updateBrandOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*' => 'required|integer|exists:car_brands,id',
        ]);

        foreach ($request->orders as $index => $brandId) {
            CarBrand::where('id', $brandId)->update(['sort_order' => $index + 1]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('admin.cars.index')->with('brand_success', 'Порядок марок обновлён.');
    }

    protected function uploadCatalogPhoto($file)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = time().'_'.bin2hex(random_bytes(4)).'.'.$extension;

        return $file->storeAs($this->uploadDir, $filename, 'public');
    }

    protected function saveMultipleImages($files, $carId)
    {
        foreach ($files as $file) {
            $extension = $file->getClientOriginalExtension();
            $filename = time().'_'.bin2hex(random_bytes(4)).'.'.$extension;
            $path = $file->storeAs($this->uploadDir, $filename, 'public');

            CarImage::create([
                'car_id' => $carId,
                'path' => $path,
                'is_main' => 0,
            ]);
        }
    }
}
