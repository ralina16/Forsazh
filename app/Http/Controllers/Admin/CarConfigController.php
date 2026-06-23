<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarConfig;
use App\Models\CarMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CarConfigController extends Controller
{
    protected $interiorTypes = [
        'business' => 'Business',
        'standart' => 'Standard',
        'luxury'   => 'Luxury',
        'sport'    => 'Sport'
    ];

    protected $variants = [
        'business' => 'Business',
        'comfort'  => 'Comfort',
        'luxury'   => 'Luxury',
        'sport'    => 'Sport',
        'premium'  => 'Premium'
    ];

    public function index()
    {
        $configs = CarConfig::with('media')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.car-configs.index', compact('configs') + [
            'interiorTypes' => $this->interiorTypes,
            'variants' => $this->variants
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'car_key'               => 'required|string|max:20|unique:car_configs',
            'name'                  => 'required|string|max:30',
            'base_price'            => 'required|numeric|min:0|max:100000000',
            'variant'               => 'required|string|in:' . implode(',', array_keys($this->variants)),
            'description'           => 'nullable|string|max:200',
            'year'                  => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'engine_id.*'           => 'nullable|string|max:10',
            'engine_title.*'        => 'nullable|string|max:25',
            'engine_desc.*'         => 'nullable|string|max:50',
            'engine_hp.*'           => 'nullable|string|max:15',
            'engine_accel.*'        => 'nullable|string|max:10',
            'engine_fuel.*'         => 'nullable|string|max:15',
            'engine_co2.*'          => 'nullable|string|max:15',
            'engine_price.*'        => 'nullable|integer|min:0|max:10000000',
            'color_key.*'           => 'nullable|string|max:20',
            'color_label.*'         => 'nullable|string|max:25',
            'color_hex.*'           => 'nullable|string|max:7',
            'color_group.*'         => 'nullable|in:metal,nonmetal',
            'color_price.*'         => 'nullable|integer|min:0|max:1000000',
            'wheel_id.*'            => 'nullable|string|max:10',
            'wheel_title.*'         => 'nullable|string|max:50',
            'wheel_desc.*'          => 'nullable|string|max:100',
            'wheel_price.*'         => 'nullable|integer|min:0|max:10000000',
            'wheel_images.*'        => 'nullable|image|max:5120', 
            'main_image'            => 'nullable|image|max:5120',
            'model_3d'              => 'nullable|file|max:102400',
            'interior_images'       => 'nullable|array',
            'interior_images.*'     => 'nullable|array',
            'interior_images.*.*'   => 'nullable|image|max:5120',
            'color_images'          => 'nullable|array',
            'color_images.*'        => 'nullable|array',
            'color_images.*.*'      => 'nullable|image|max:5120',
        ];

        $messages = [
            'car_key.required'       => 'Поле "Ключ" обязательно.',
            'car_key.unique'         => 'Ключ должен быть уникальным.',
            'car_key.max'            => 'Ключ не должен превышать 20 символов.',
            'name.required'          => 'Название обязательно.',
            'name.max'               => 'Название не должно превышать 30 символов.',
            'base_price.required'    => 'Базовая цена обязательна.',
            'base_price.numeric'     => 'Цена должна быть числом.',
            'base_price.min'         => 'Цена не может быть отрицательной.',
            'base_price.max'         => 'Цена не может превышать 100 000 000.',
            'variant.required'       => 'Выберите вариант исполнения.',
            'variant.in'             => 'Недопустимый вариант.',
            'year.required'          => 'Укажите год выпуска.',
            'year.integer'           => 'Год должен быть числом.',
            'year.min'               => 'Год не может быть ранее 2000.',
            'year.max'               => 'Год не может быть позже ' . (date('Y') + 5) . '.',
            'main_image.image'       => 'Главное фото должно быть изображением.',
            'main_image.max'         => 'Размер главного фото не должен превышать 5 МБ.',
            'model_3d.file'          => '3D модель должна быть файлом.',
            'model_3d.max'           => 'Размер 3D модели не должен превышать 100 МБ.',
            'interior_images.*.*.image' => 'Фото салона должно быть изображением.',
            'interior_images.*.*.max'   => 'Размер фото салона не должен превышать 5 МБ.',
            'color_images.*.*.image'    => 'Фото цвета должно быть изображением.',
            'color_images.*.*.max'      => 'Размер фото цвета не должен превышать 5 МБ.',
            'wheel_title.*.max'      => 'Название колеса не должно превышать 50 символов.',
            'wheel_desc.*.max'       => 'Описание колеса не должно превышать 100 символов.',
            'wheel_price.*.integer'  => 'Цена колес должна быть числом.',
            'wheel_images.*.image'   => 'Фото колеса должно быть изображением.', 
            'wheel_images.*.max'     => 'Размер фото колеса не должен превышать 5 МБ.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $configData = $this->buildConfigData($request);

        DB::beginTransaction();
        try {
            $carConfig = CarConfig::create([
                'car_key'      => $request->car_key,
                'name'         => $request->name,
                'base_price'   => $request->base_price,
                'variant'      => $request->variant,
                'description'  => $request->description,
                'year'         => $request->year,
                'config_data'  => $configData,
            ]);

            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')->store('car-configs/main', 'public');
                $carConfig->media()->create([
                    'type'      => 'main_image',
                    'file_path' => $path,
                ]);
            }

            if ($request->has('interior_images') && is_array($request->interior_images)) {
                foreach ($request->interior_images as $interiorKey => $files) {
                    if (!is_array($files)) continue;
                    if (!array_key_exists($interiorKey, $this->interiorTypes)) continue;

                    foreach ($files as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                            $path = $file->store('car-configs/interior', 'public');
                            $carConfig->media()->create([
                                'type'          => 'interior_image',
                                'file_path'     => $path,
                                'interior_key'  => $interiorKey,
                            ]);
                        }
                    }
                }
            }

            if ($request->has('color_images') && is_array($request->color_images)) {
                foreach ($request->color_images as $colorKey => $files) {
                    if (!is_array($files)) continue;
                    if (empty($colorKey) || $colorKey === '__AUTO__') continue; 

                    foreach ($files as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                            $path = $file->store('car-configs/colors', 'public');
                            $carConfig->media()->create([
                                'type'       => 'color_image',
                                'file_path'  => $path,
                                'color_key'  => $colorKey,
                                'sort_order' => 0,
                            ]);
                        }
                    }
                }
            }

            if ($request->hasFile('model_3d')) {
                $path = $request->file('model_3d')->store('car-configs/models', 'public');
                $carConfig->media()->create([
                    'type'      => 'model_3d',
                    'file_path' => $path,
                ]);
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Конфигурация создана!']);
            }

            return redirect()->route('admin.car-configs.index')
                ->with('success', 'Конфигурация создана!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Ошибка базы данных: ' . $e->getMessage()], 500);
            }

            return back()->withInput()->withErrors(['error' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $carConfig = CarConfig::findOrFail($id);

        $rules = [
            'car_key'               => ['required', 'string', 'max:20', Rule::unique('car_configs')->ignore($id)],
            'name'                  => 'required|string|max:30',
            'base_price'            => 'required|numeric|min:0|max:100000000',
            'variant'               => 'required|string|in:' . implode(',', array_keys($this->variants)),
            'description'           => 'nullable|string|max:200',
            'year'                  => 'required|integer|min:2000|max:' . (date('Y') + 5),
            'engine_id.*'           => 'nullable|string|max:10',
            'engine_title.*'        => 'nullable|string|max:25',
            'engine_desc.*'         => 'nullable|string|max:50',
            'engine_hp.*'           => 'nullable|string|max:15',
            'engine_accel.*'        => 'nullable|string|max:10',
            'engine_fuel.*'         => 'nullable|string|max:15',
            'engine_co2.*'          => 'nullable|string|max:15',
            'engine_price.*'        => 'nullable|integer|min:0|max:10000000',
            'color_key.*'           => 'nullable|string|max:20',
            'color_label.*'         => 'nullable|string|max:25',
            'color_hex.*'           => 'nullable|string|max:7',
            'color_group.*'         => 'nullable|in:metal,nonmetal',
            'color_price.*'         => 'nullable|integer|min:0|max:1000000',
            'wheel_id.*'            => 'nullable|string|max:10',
            'wheel_title.*'         => 'nullable|string|max:50',
            'wheel_desc.*'          => 'nullable|string|max:100',
            'wheel_price.*'         => 'nullable|integer|min:0|max:10000000',
            'wheel_images.*'        => 'nullable|image|max:5120', 
            'main_image'            => 'nullable|image|max:5120',
            'model_3d'              => 'nullable|file|max:102400',
            'interior_images'       => 'nullable|array',
            'interior_images.*'     => 'nullable|array',
            'interior_images.*.*'   => 'nullable|image|max:5120',
            'color_images'          => 'nullable|array',
            'color_images.*'        => 'nullable|array',
            'color_images.*.*'      => 'nullable|image|max:5120',
        ];

        $messages = [
            'car_key.required'       => 'Поле "Ключ" обязательно.',
            'car_key.unique'         => 'Ключ должен быть уникальным.',
            'car_key.max'            => 'Ключ не должен превышать 20 символов.',
            'name.required'          => 'Название обязательно.',
            'name.max'               => 'Название не должно превышать 30 символов.',
            'base_price.required'    => 'Базовая цена обязательна.',
            'base_price.numeric'     => 'Цена должна быть числом.',
            'base_price.min'         => 'Цена не может быть отрицательной.',
            'base_price.max'         => 'Цена не может превышать 100 000 000.',
            'variant.required'       => 'Выберите вариант исполнения.',
            'variant.in'             => 'Недопустимый вариант.',
            'year.required'          => 'Укажите год выпуска.',
            'year.integer'           => 'Год должен быть числом.',
            'year.min'               => 'Год не может быть ранее 2000.',
            'year.max'               => 'Год не может быть позже ' . (date('Y') + 5) . '.',
            'main_image.image'       => 'Главное фото должно быть изображением.',
            'main_image.max'         => 'Размер главного фото не должен превышать 5 МБ.',
            'model_3d.file'          => '3D модель должна быть файлом.',
            'model_3d.max'           => 'Размер 3D модели не должен превышать 100 МБ.',
            'interior_images.*.*.image' => 'Фото салона должно быть изображением.',
            'interior_images.*.*.max'   => 'Размер фото салона не должен превышать 5 МБ.',
            'color_images.*.*.image'    => 'Фото цвета должно быть изображением.',
            'color_images.*.*.max'      => 'Размер фото цвета не должен превышать 5 МБ.',
            'wheel_title.*.max'      => 'Название колеса не должно превышать 50 символов.',
            'wheel_desc.*.max'       => 'Описание колеса не должно превышать 100 символов.',
            'wheel_price.*.integer'  => 'Цена колес должна быть числом.',
            'wheel_images.*.image'   => 'Фото колеса должно быть изображением.', 
            'wheel_images.*.max'     => 'Размер фото колеса не должен превышать 5 МБ.', 
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingConfig = $carConfig->config_data ?? [];
        $configData = $this->buildConfigData($request, $existingConfig['wheels'] ?? []);

        DB::beginTransaction();
        try {
            $carConfig->update([
                'car_key'      => $request->car_key,
                'name'         => $request->name,
                'base_price'   => $request->base_price,
                'variant'      => $request->variant,
                'description'  => $request->description,
                'year'         => $request->year,
                'config_data'  => $configData,
            ]);

            if ($request->get('delete_main_image') == '1') {
                $oldMain = $carConfig->media()->where('type', 'main_image')->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->file_path);
                    $oldMain->delete();
                }
            } elseif ($request->hasFile('main_image')) {
                $oldMain = $carConfig->media()->where('type', 'main_image')->first();
                if ($oldMain) {
                    Storage::disk('public')->delete($oldMain->file_path);
                    $oldMain->delete();
                }
                $path = $request->file('main_image')->store('car-configs/main', 'public');
                $carConfig->media()->create([
                    'type'      => 'main_image',
                    'file_path' => $path,
                ]);
            }

            if ($request->get('delete_model_3d') == '1') {
                $existingModel = $carConfig->media()->where('type', 'model_3d')->first();
                if ($existingModel) {
                    Storage::disk('public')->delete($existingModel->file_path);
                    $existingModel->delete();
                }
            } elseif ($request->hasFile('model_3d')) {
                $existingModel = $carConfig->media()->where('type', 'model_3d')->first();
                if ($existingModel) {
                    Storage::disk('public')->delete($existingModel->file_path);
                    $existingModel->delete();
                }
                $path = $request->file('model_3d')->store('car-configs/models', 'public');
                $carConfig->media()->create([
                    'type'      => 'model_3d',
                    'file_path' => $path,
                ]);
            }

            $existingInteriorImageIds = [];
            if ($request->has('existing_interior_images') && is_array($request->existing_interior_images)) {
                foreach ($request->existing_interior_images as $interiorKey => $ids) {
                    if (is_array($ids)) {
                        foreach ($ids as $id) {
                            if (!empty($id)) $existingInteriorImageIds[] = $id;
                        }
                    }
                }
            }

            $oldInteriors = $carConfig->media()->where('type', 'interior_image')->get();
            foreach ($oldInteriors as $old) {
                if (!in_array($old->id, $existingInteriorImageIds)) {
                    Storage::disk('public')->delete($old->file_path);
                    $old->delete();
                }
            }

            if ($request->has('interior_images') && is_array($request->interior_images)) {
                foreach ($request->interior_images as $interiorKey => $files) {
                    if (!is_array($files)) continue;
                    if (!array_key_exists($interiorKey, $this->interiorTypes)) continue;

                    foreach ($files as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                            $path = $file->store('car-configs/interior', 'public');
                            $carConfig->media()->create([
                                'type'          => 'interior_image',
                                'file_path'     => $path,
                                'interior_key'  => $interiorKey,
                            ]);
                        }
                    }
                }
            }

            $existingColorImageIds = [];
            if ($request->has('existing_color_images') && is_array($request->existing_color_images)) {
                foreach ($request->existing_color_images as $colorKey => $ids) {
                    if (is_array($ids)) {
                        foreach ($ids as $id) {
                            if (!empty($id)) $existingColorImageIds[] = $id;
                        }
                    }
                }
            }

            $oldColors = $carConfig->media()->where('type', 'color_image')->get();
            foreach ($oldColors as $old) {
                if (!in_array($old->id, $existingColorImageIds)) {
                    Storage::disk('public')->delete($old->file_path);
                    $old->delete();
                }
            }

            if ($request->has('color_images') && is_array($request->color_images)) {
                foreach ($request->color_images as $colorKey => $files) {
                    if (!is_array($files)) continue;
                    if (empty($colorKey) || $colorKey === '__AUTO__') continue;

                    foreach ($files as $file) {
                        if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                            $path = $file->store('car-configs/colors', 'public');
                            $carConfig->media()->create([
                                'type'       => 'color_image',
                                'file_path'  => $path,
                                'color_key'  => $colorKey,
                                'sort_order' => 0,
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Конфигурация обновлена!']);
            }

            return redirect()->route('admin.car-configs.index')
                ->with('success', 'Конфигурация обновлена!');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ошибка базы данных: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $carConfig = CarConfig::findOrFail($id);
        
        $configData = $carConfig->config_data ?? [];
        if (!empty($configData['wheels'])) {
            foreach ($configData['wheels'] as $wheel) {
                if (!empty($wheel['image_url'])) {
                    Storage::disk('public')->delete($wheel['image_url']);
                }
            }
        }
        
        foreach ($carConfig->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $carConfig->delete();
        return redirect()->route('admin.car-configs.index')
            ->with('success', 'Конфигурация удалена.');
    }

    public function deleteMedia($id)
    {
        $media = CarMedia::findOrFail($id);
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
        return response()->json(['success' => true]);
    }

    protected function buildConfigData(Request $request, array $existingWheels = [])
    {
        $engines = [];
        if (!empty($request->engine_title)) {
            foreach ($request->engine_title as $index => $title) {
                if (!empty(trim($title))) {
                    $engines[] = [
                        'id'    => $request->engine_id[$index] ?? 'e' . ($index + 1),
                        'title' => $title,
                        'desc'  => $request->engine_desc[$index] ?? '',
                        'hp'    => $request->engine_hp[$index] ?? '',
                        'accel' => $request->engine_accel[$index] ?? '',
                        'fuel'  => $request->engine_fuel[$index] ?? '',
                        'co2'   => $request->engine_co2[$index] ?? '',
                        'price' => intval($request->engine_price[$index] ?? 0),
                    ];
                }
            }
        }

        $colors = [];
        if (!empty($request->color_key)) {
            foreach ($request->color_key as $index => $key) {
                if (!empty(trim($key))) {
                    $colors[$key] = [
                        'label' => $request->color_label[$index] ?? '',
                        'hex'   => $request->color_hex[$index] ?? '#FFFFFF',
                        'price' => intval($request->color_price[$index] ?? 0),
                        'group' => $request->color_group[$index] ?? 'metal',
                    ];
                }
            }
        }

        // ИЗМЕНЕНО: wheel_image -> wheel_images
        $wheelImages = [];
        if ($request->hasFile('wheel_images')) {
            foreach ($request->file('wheel_images') as $index => $file) {
                if ($file && $file->isValid()) {
                    $wheelImages[$index] = $file->store('car-configs/wheels', 'public');
                }
            }
        }

        $existingWheelsById = [];
        foreach ($existingWheels as $w) {
            if (!empty($w['id'])) {
                $existingWheelsById[$w['id']] = $w;
            }
        }

        $existingWheelImageUrls = $request->input('existing_wheel_image_urls', []);

        $wheels = [];
        if (!empty($request->wheel_id)) {
            foreach ($request->wheel_id as $index => $id) {
                if (!empty(trim($id))) {
                    $imageUrl = '';
                    
                    if (isset($wheelImages[$index])) {
                        $imageUrl = $wheelImages[$index];
                    }
                    elseif (isset($existingWheelImageUrls[$index]) && !empty($existingWheelImageUrls[$index])) {
                        $imageUrl = $existingWheelImageUrls[$index];
                    }
                    elseif (isset($existingWheelsById[$id]['image_url']) && !empty($existingWheelsById[$id]['image_url'])) {
                        $imageUrl = $existingWheelsById[$id]['image_url'];
                    }
                    
                    if (!empty($imageUrl) && isset($existingWheelsById[$id]['image_url']) && $existingWheelsById[$id]['image_url'] !== $imageUrl) {
                        Storage::disk('public')->delete($existingWheelsById[$id]['image_url']);
                    }
                    
                    $wheels[] = [
                        'id'         => $id,
                        'title'      => $request->wheel_title[$index] ?? '',
                        'desc'       => $request->wheel_desc[$index] ?? '',
                        'price'      => intval($request->wheel_price[$index] ?? 0),
                        'image_url'  => $imageUrl,
                    ];
                }
            }
        }

        return ['engines' => $engines, 'colors' => $colors, 'wheels' => $wheels];
    }
}