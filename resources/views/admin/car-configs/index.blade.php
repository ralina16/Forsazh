@extends('layouts.admin')

@section('title', '3D Конфигурации автомобилей')

@push('styles')
    <style>
        .car-table-img { height: 85px; object-fit: cover; border-radius: 8px; }
        .config-item-card { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 12px; padding: 18px; margin-bottom: 14px; transition: all 0.25s ease; }
        .config-item-card:hover { background: #fff; border-color: #4071CB; box-shadow: 0 4px 15px rgba(64,113,203,0.12); }
        .nav-tabs .nav-link { font-weight: 600; }
        .nav-tabs .nav-link.active { border-bottom: 3px solid #4071CB; color: #4071CB; }
        .form-control-color { height: 42px; width: 62px; padding: 4px; cursor: pointer; border-radius: 6px; }
        .color-preview { width: 38px; height: 38px; border-radius: 8px; border: 3px solid #fff; box-shadow: 0 0 0 1px rgba(0,0,0,0.15); }
        .dynamic-list { max-height: 520px; overflow-y: auto; padding-right: 10px; }
        .required-field::after { content: " *"; color: #dc3545; }
        .modal-xl { max-width: 1150px; }

        .photo-preview-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); 
            gap: 12px; 
            margin-top: 12px; 
        }
        .photo-preview-item { 
            position: relative; 
            border: 2px solid #e9ecef; 
            border-radius: 8px; 
            overflow: hidden; 
            background: #fff; 
        }
        .photo-preview-item img { 
            width: 100%; 
            height: 110px; 
            object-fit: cover; 
        }
        .photo-preview-item .delete-btn { 
            position: absolute; 
            top: 6px; 
            right: 6px; 
            background: rgba(220,53,69,0.9); 
            color: white; 
            border: none; 
            width: 24px; 
            height: 24px; 
            border-radius: 50%; 
            font-size: 14px; 
            cursor: pointer; 
        }

        .no-photos { 
            text-align: center; 
            padding: 30px; 
            color: #6c757d; 
            background: #f8f9fa; 
            border-radius: 8px; 
        }

        .interior-photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 16px;
        }
        .interior-photo-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            transition: all 0.2s ease;
        }
        .interior-photo-card:hover {
            border-color: #4071CB;
            box-shadow: 0 4px 12px rgba(64,113,203,0.15);
        }
        .interior-photo-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }
        .interior-photo-info {
            padding: 10px 12px;
            font-size: 13px;
        }
        .interior-photo-type {
            font-weight: 600;
            color: #4071CB;
        }
        .interior-photo-date {
            font-size: 12px;
            color: #888;
        }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }} <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
        <h4 class="mb-0">3D Конфигурации автомобилей</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#carModal" onclick="resetFormForAdd()">
            <i class="bi bi-plus-lg me-2"></i>Добавить новую конфигурацию
        </button>
    </div>

    <div class="table-wrapper mb-4">
        <table class="table car-table my-5">
            <thead>
                <tr class="text-center align-middle">
                    <th>ID</th>
                    <th>Фото</th>
                    <th>Ключ</th>
                    <th>Модель</th>
                    <th>Год</th>
                    <th>Вариант</th>
                    <th>Цена</th>
                    <th>3D</th>
                    <th>Салон</th>
                    <th>Цвета</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-center align-middle">
               @foreach ($configs as $config)
    @php
        $modelsCount = $config->media()->where('type', 'model_3d')->count();
        $interiorCount = $config->media()->where('type', 'interior_image')->count();
        $colorCount = $config->media()->where('type', 'color_image')->count();
        $mainImage = $config->media()->where('type', 'main_image')->first();
        $previewSrc = $mainImage ? asset('storage/' . $mainImage->file_path) : asset('assets/images/offer/default-car.jpg');
        
        // ✅ Исправлено: выносим тернарный оператор в @php (избегаем проблем парсера Blade)
        $hasMainPhoto = $mainImage ? '1' : '0';
        
        $interiorData = $config->media()->where('type', 'interior_image')->get()->map(fn($img) => [
            'id' => $img->id,
            'key' => $img->interior_key,
            'path' => asset('storage/' . $img->file_path),
            'type' => $interiorTypes[$img->interior_key] ?? $img->interior_key,
            'date' => $img->created_at->format('Y-m-d H:i:s'),
        ]);
        $colorImagesData = $config->media()->where('type', 'color_image')->get()->map(fn($img) => [
            'id' => $img->id, 
            'color_key' => $img->color_key, 
            'path' => asset('storage/' . $img->file_path)
        ]);
        $variantName = $variants[$config->variant] ?? $config->variant;
        $threeDModel = $config->media()->where('type', 'model_3d')->first();
        $threeDModelJson = $threeDModel
            ? json_encode(['title' => $threeDModel->title, 'path' => asset('storage/' . $threeDModel->file_path)])
            : 'null';
    @endphp

    <tr class="mobile-card" 
        data-id="{{ $config->id }}" 
        data-key="{{ $config->car_key }}"
        data-name="{{ $config->name }}" 
        data-price="{{ number_format($config->base_price, 0, '.', ' ') }}"
        data-year="{{ $config->year }}" 
        data-variant="{{ $config->variant }}" 
        data-variant-name="{{ $variantName }}"
        data-description="{{ $config->description ?? '' }}" 
        data-photo="{{ $previewSrc }}"
        data-config='{{ json_encode($config->config_data) }}' 
        data-models-count="{{ $modelsCount }}"
        data-interior-count="{{ $interiorCount }}" 
        data-color-count="{{ $colorCount }}"
        data-interior-images='{{ json_encode($interiorData) }}'
        data-color-images='{{ json_encode($colorImagesData) }}'
        data-has-main-photo="{{ $hasMainPhoto }}"
        data-three-d-model='{{ $threeDModelJson }}'>
        
        <td class="fw-bold">{{ $config->id }}</td>
        <td><img src="{{ $previewSrc }}" class="car-table-img" alt="{{ $config->name }}"></td>
        <td><code>{{ $config->car_key }}</code></td>
        <td>{{ $config->name }}</td>
        <td>{{ $config->year }}</td>
        <td>{{ $variantName }}</td>
        <td>{{ number_format($config->base_price, 0, ',', ' ') }} ₽</td>
        <td><span class="badge bg-primary">{{ $modelsCount }}</span></td>
        <td><span class="badge bg-success">{{ $interiorCount }}</span></td>
        <td><span class="badge bg-info">{{ $colorCount }}</span></td>
        <td>
            <button class="row-more-btn btn btn-light" data-bs-toggle="modal" data-bs-target="#viewConfigModal">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
        </td>
    </tr>
@endforeach
            </tbody>
        </table>
    </div>

    @if ($configs->hasPages())
        <nav class="mt-4"><ul class="pagination justify-content-center">{{ $configs->links() }}</ul></nav>
    @endif

    <div class="modal fade" id="carModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Добавить новую конфигурацию</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div id="modalErrorContainer" class="alert alert-danger mx-3 mt-2 d-none"></div>

                <form id="carForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="form_id" value="">
                    <input type="hidden" name="_method" id="form_method" value="POST">

                    <ul class="nav nav-tabs px-4 pt-3" id="configTabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabMain" type="button">Основное</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabEngines" type="button">Двигатели</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabColors" type="button">Цвета</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabWheels" type="button">Колёса</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabInterior" type="button">Салон</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabMedia" type="button">Медиа</button></li>
                    </ul>

                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="tabMain">
                            <div class="row g-3">
                                <div class="col-md-3"><label class="form-label required-field">Ключ модели</label><input type="text" name="car_key" id="car_key" class="form-control" required></div>
                                <div class="col-md-5"><label class="form-label required-field">Название модели</label><input type="text" name="name" id="name" class="form-control" required></div>
                                <div class="col-md-2"><label class="form-label required-field">Год</label><input type="number" name="year" id="year" class="form-control" required></div>
                                <div class="col-md-4"><label class="form-label required-field">Базовая цена</label>
                                    <div class="input-group"><input type="number" name="base_price" id="base_price" class="form-control" required><span class="input-group-text">₽</span></div>
                                </div>
                                <div class="col-md-4"><label class="form-label required-field">Вариант исполнения</label>
                                    <select name="variant" id="variant" class="form-select">
                                        @foreach ($variants as $k => $l)
                                            <option value="{{ $k }}">{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12"><label class="form-label">Описание</label><textarea name="description" id="description" rows="3" class="form-control"></textarea></div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabEngines">
                            <div class="dynamic-list" id="enginesContainer"></div>
                            <button type="button" class="btn btn-outline-primary mt-3" onclick="addEngineRow()"><i class="bi bi-plus-lg"></i> Добавить двигатель</button>
                        </div>

                        <div class="tab-pane fade" id="tabColors">
                            <div class="dynamic-list" id="colorsContainer"></div>
                            <button type="button" class="btn btn-outline-primary mt-3" onclick="addColorRow()"><i class="bi bi-plus-lg"></i> Добавить цвет</button>
                        </div>

                        <div class="tab-pane fade" id="tabWheels">
                            <div class="dynamic-list" id="wheelsContainer"></div>
                            <button type="button" class="btn btn-outline-primary mt-3" onclick="addWheelRow()"><i class="bi bi-plus-lg"></i> Добавить колёса</button>
                        </div>

                        <div class="tab-pane fade" id="tabInterior">
                            <div class="row g-3 align-items-end mb-3">
                                <div class="col-md-5">
                                    <label class="form-label">Тип салона</label>
                                    <select id="interiorTypeSelect" class="form-select">
                                        <option value="">Выберите тип...</option>
                                        @foreach ($interiorTypes as $k => $l)
                                            <option value="{{ $k }}">{{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary w-100" onclick="addInteriorUploadRow()">Добавить новый тип</button>
                                </div>
                            </div>
                            <div id="interiorUploadsContainer" class="dynamic-list"></div>
                        </div>

                        <div class="tab-pane fade" id="tabMedia">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Главное фото</label>
                                    <div id="mainImagePreview" class="mb-2"></div>
                                    <input type="file" name="main_image" class="form-control" accept="image/*">
                                    <input type="hidden" name="delete_main_image" id="delete_main_image" value="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">3D модель (.glb)</label>
                                    <div id="model3dPreview" class="mb-2"></div>
                                    <input type="file" name="model_3d" class="form-control" accept=".glb">
                                    <input type="hidden" name="delete_model_3d" id="delete_model_3d" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Сохранить конфигурацию</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewConfigModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <div>
                        <h5 class="modal-title">Информация о конфигурации</h5>
                        <div class="small text-muted">ID: <strong id="v_id">—</strong> · Ключ: <strong id="v_key">—</strong></div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <h4 id="v_name">—</h4>
                            <h5 class="text-primary" id="v_price">—</h5>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Основная информация</h6>
                            <div class="row">
                                <div class="col-md-3 mb-2"><div class="small text-muted">Год</div><div id="v_year">—</div></div>
                                <div class="col-md-3 mb-2"><div class="small text-muted">Вариант</div><div id="v_variant">—</div></div>
                                <div class="col-md-3 mb-2"><div class="small text-muted">3D Модели</div><div><span class="badge bg-primary" id="v_models_count">0</span></div></div>
                                <div class="col-md-3 mb-2"><div class="small text-muted">Фото салона</div><div><span class="badge bg-success" id="v_interior_count">0</span></div></div>
                                <div class="col-12 mt-2"><div class="small text-muted">Описание</div><div id="v_description">—</div></div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Фото цветов</h6>
                            <div id="v_color_images_list" class="interior-photos-grid"></div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Фото салона</h6>
                            <div id="v_interior_list" class="interior-photos-grid"></div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Двигатели</h6>
                            <div id="v_engines_list"></div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Колёса</h6>
                            <div id="v_wheels_list"></div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Цвета</h6>
                            <div id="v_colors_list"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <form method="POST" action="" id="deleteConfigForm" onsubmit="return confirm('Удалить конфигурацию?');">
                        @csrf @method('DELETE')
                        <input type="hidden" name="id" id="delete_id_2" value="">
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-primary" id="v_edit_bottom">Редактировать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function addEngineRow(engine = {}) {
        const container = document.getElementById('enginesContainer');
        const div = document.createElement('div');
        div.className = 'config-item-card';
        div.innerHTML = `
        <div class="d-flex justify-content-end mb-2"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.config-item-card').remove()">Удалить</button></div>
        <div class="row g-3">
            <div class="col-md-2"><label class="form-label required-field">ID</label><input type="text" name="engine_id[]" class="form-control" value="${engine.id||''}" placeholder="e1"></div>
            <div class="col-md-4"><label class="form-label required-field">Название</label><input type="text" name="engine_title[]" class="form-control" value="${engine.title||''}"></div>
            <div class="col-md-3"><label class="form-label">Мощность</label><input type="text" name="engine_hp[]" class="form-control" value="${engine.hp||''}"></div>
            <div class="col-md-3"><label class="form-label">Цена</label><input type="number" name="engine_price[]" class="form-control" value="${engine.price||0}"></div>
            <div class="col-md-6"><label class="form-label">Описание</label><input type="text" name="engine_desc[]" class="form-control" value="${engine.desc||''}"></div>
            <div class="col-md-2"><label class="form-label">Разгон</label><input type="text" name="engine_accel[]" class="form-control" value="${engine.accel||''}"></div>
            <div class="col-md-2"><label class="form-label">Расход</label><input type="text" name="engine_fuel[]" class="form-control" value="${engine.fuel||''}"></div>
            <div class="col-md-2"><label class="form-label">CO₂</label><input type="text" name="engine_co2[]" class="form-control" value="${engine.co2||''}"></div>
        </div>`;
        container.appendChild(div);
    }

    function addColorRow(color = {}) {
        const container = document.getElementById('colorsContainer');
        const div = document.createElement('div');
        div.className = 'config-item-card';

        let photosHtml = '';
        if (color.photos && color.photos.length) {
            photosHtml = `<div class="col-12 mt-3"><label class="form-label">Загруженные фото цвета</label><div class="photo-preview-grid">`;
            color.photos.forEach((path, index) => {
                const id = color.photoIds ? color.photoIds[index] : '';
                photosHtml += `
                <div class="photo-preview-item">
                    <img src="${path}" alt="">
                    <button type="button" class="delete-btn" onclick="this.closest('.photo-preview-item').remove();">×</button>
                    <input type="hidden" name="existing_color_images[${color.key || '__AUTO__'}][]" value="${id}">
                </div>`;
            });
            photosHtml += `</div></div>`;
        }

        div.innerHTML = `
        <div class="d-flex justify-content-end mb-2"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.config-item-card').remove()">Удалить цвет</button></div>
        <div class="row g-3 align-items-end">
            <div class="col-md-2"><label class="form-label required-field">Ключ цвета</label><input type="text" name="color_key[]" class="form-control color-key-input" value="${color.key||''}" placeholder="alpine_white"></div>
            <div class="col-md-3"><label class="form-label required-field">Название</label><input type="text" name="color_label[]" class="form-control" value="${color.label||''}"></div>
            <div class="col-md-2"><label class="form-label">HEX</label><input type="color" name="color_hex[]" class="form-control form-control-color" value="${color.hex||'#ffffff'}"></div>
            <div class="col-md-1"><div class="color-preview mt-4" style="background-color:${color.hex||'#ffffff'}"></div></div>
            <div class="col-md-2"><label class="form-label">Группа</label><select name="color_group[]" class="form-select"><option value="metal" ${color.group==='metal'?'selected':''}>Металлик</option><option value="nonmetal" ${color.group==='nonmetal'?'selected':''}>Неметаллик</option></select></div>
            <div class="col-md-2"><label class="form-label">Цена (₽)</label><input type="number" name="color_price[]" class="form-control" value="${color.price||0}"></div>
            
            ${photosHtml}
            
            <div class="col-12 mt-3"><label class="form-label">Добавить новые фото цвета (можно несколько)</label><input type="file" name="color_images[${color.key||'__AUTO__'}][]" class="form-control color-photo-input" accept="image/*" multiple><small class="text-muted">Выберите одно или несколько фото для этого цвета</small></div>
        </div>`;

        const keyInput = div.querySelector('.color-key-input');
        const fileInput = div.querySelector('.color-photo-input');
        if (keyInput && fileInput) {
            keyInput.addEventListener('input', function() {
                const val = this.value.trim();
                fileInput.name = val ? `color_images[${val}][]` : `color_images[__AUTO__][]`;
            });
        }
        container.appendChild(div);
    }

   function addWheelRow(wheel = {}) {
    const container = document.getElementById('wheelsContainer');
    const div = document.createElement('div');
    div.className = 'config-item-card';

    // Поддержка как image_url, так и photos
    let photos = wheel.photos || [];
    if (photos.length === 0 && wheel.image_url) {
        photos = [wheel.image_url];
    }

    let photosHtml = '';
    if (photos && photos.length > 0) {
        photosHtml = `<div class="col-12 mt-3">
            <label class="form-label">Загруженные фото колёс</label>
            <div class="photo-preview-grid">`;

        photos.forEach((path) => {
            if (!path) return;

            // Исправляем путь, если он относительный
            let fullPath = path;
            if (path && !path.startsWith('http') && !path.startsWith('/')) {
                fullPath = '/storage/' + path.replace(/^storage\//, '');
            }

            photosHtml += `
            <div class="photo-preview-item">
                <img src="${fullPath}" alt="Колесо" 
                     onerror="this.onerror=null; this.src='{{ asset('assets/images/offer/default-car.jpg') }}';">
                <button type="button" class="delete-btn" onclick="this.closest('.photo-preview-item').remove();">×</button>
                <input type="hidden" name="existing_wheel_image_urls[]" value="${path}">
            </div>`;
        });

        photosHtml += `</div></div>`;
    }

    div.innerHTML = `
    <div class="d-flex justify-content-end mb-2">
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.config-item-card').remove()">Удалить</button>
    </div>
    <div class="row g-3 align-items-end">
        <div class="col-md-2"><label class="form-label required-field">ID</label><input type="text" name="wheel_id[]" class="form-control" value="${wheel.id||''}"></div>
        <div class="col-md-4"><label class="form-label required-field">Название</label><input type="text" name="wheel_title[]" class="form-control" value="${wheel.title||''}"></div>
        <div class="col-md-4"><label class="form-label">Описание</label><input type="text" name="wheel_desc[]" class="form-control" value="${wheel.desc||''}"></div>
        <div class="col-md-2"><label class="form-label">Цена</label><input type="number" name="wheel_price[]" class="form-control" value="${wheel.price||0}"></div>
        
        ${photosHtml}
        
        <div class="col-12 mt-3">
            <label class="form-label">Добавить новые фото колёс (можно несколько)</label>
            <input type="file" name="wheel_images[]" class="form-control" accept="image/*" multiple>
            <small class="text-muted">Можно выбрать несколько фотографий</small>
        </div>
    </div>`;

    container.appendChild(div);
}

    function addInteriorUploadRow() {
        const select = document.getElementById('interiorTypeSelect');
        if (!select.value) return;
        const container = document.getElementById('interiorUploadsContainer');
        const card = document.createElement('div');
        card.className = 'config-item-card';
        card.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary">${select.options[select.selectedIndex].text}</span>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.config-item-card').remove()">Удалить</button>
            </div>
            <input type="file" name="interior_images[${select.value}][]" class="form-control" multiple accept="image/*">`;
        container.appendChild(card);
        select.value = '';
    }

    function fillExistingInteriorPhotos(interiorImages) {
        const container = document.getElementById('interiorUploadsContainer');
        if (!interiorImages || !interiorImages.length) return;

        const grouped = {};
        interiorImages.forEach(img => {
            if (!grouped[img.key]) grouped[img.key] = [];
            grouped[img.key].push(img);
        });

        Object.keys(grouped).forEach(key => {
            const images = grouped[key];
            const typeName = images[0].type || key;
            const card = document.createElement('div');
            card.className = 'config-item-card';
            let html = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary">${typeName}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.config-item-card').remove()">Удалить тип</button>
                </div>
                <div class="photo-preview-grid">`;
            images.forEach(img => {
                html += `
                <div class="photo-preview-item">
                    <img src="${img.path}" alt="${typeName}">
                    <button type="button" class="delete-btn" onclick="this.closest('.photo-preview-item').remove();">×</button>
                    <input type="hidden" name="existing_interior_images[${key}][]" value="${img.id}">
                </div>`;
            });
            html += `</div>
                <input type="file" name="interior_images[${key}][]" class="form-control mt-3" multiple accept="image/*">
                <small class="text-muted">Добавить ещё фото к этому типу</small>`;
            card.innerHTML = html;
            container.appendChild(card);
        });
    }

    function resetFormForAdd() {
        document.getElementById('modalTitle').textContent = 'Добавить новую конфигурацию';
        document.getElementById('carForm').action = '{{ route('admin.car-configs.store') }}';
        document.getElementById('form_id').value = '';
        document.getElementById('form_method').value = 'POST';
        document.getElementById('modalErrorContainer').classList.add('d-none');

        document.getElementById('enginesContainer').innerHTML = '';
        document.getElementById('colorsContainer').innerHTML = '';
        document.getElementById('wheelsContainer').innerHTML = '';
        document.getElementById('interiorUploadsContainer').innerHTML = '';
        document.getElementById('mainImagePreview').innerHTML = '';
        document.getElementById('model3dPreview').innerHTML = '';

        addEngineRow();
        addColorRow();
        addWheelRow();
    }

    function fillEditPreviews(data) {
        const mainPreview = document.getElementById('mainImagePreview');
        if (data.photo && data.photo !== '{{ asset('assets/images/offer/default-car.jpg') }}') {
            mainPreview.innerHTML = `<div class="photo-preview-item"><img src="${data.photo}" alt="Главное фото"><button type="button" class="delete-btn" onclick="markDeleteMainImage()">×</button></div>`;
        }

        const modelPreview = document.getElementById('model3dPreview');
        if (data.threeDModel && data.threeDModel.path) {
            modelPreview.innerHTML = `<div class="photo-preview-item"><div style="height:110px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;font-size:13px;color:#6c757d;">3D модель загружена</div><button type="button" class="delete-btn" onclick="markDeleteModel3d()">×</button></div>`;
        }
    }

    function markDeleteMainImage() {
        if (confirm('Удалить главное фото?')) {
            document.getElementById('delete_main_image').value = '1';
            document.getElementById('mainImagePreview').innerHTML = '<small class="text-danger">Главное фото будет удалено при сохранении</small>';
        }
    }

    function markDeleteModel3d() {
        if (confirm('Удалить 3D модель?')) {
            document.getElementById('delete_model_3d').value = '1';
            document.getElementById('model3dPreview').innerHTML = '<small class="text-danger">3D модель будет удалена при сохранении</small>';
        }
    }

    function openEditModalWithData(data) {
        document.getElementById('modalTitle').textContent = 'Редактировать конфигурацию';
        document.getElementById('carForm').action = '{{ route('admin.car-configs.update', '') }}/' + data.id;
        document.getElementById('form_id').value = data.id;
        document.getElementById('form_method').value = 'PUT';
        document.getElementById('modalErrorContainer').classList.add('d-none');

        document.getElementById('car_key').value = data.key || '';
        document.getElementById('name').value = data.name || '';
        document.getElementById('year').value = data.year || '';
        document.getElementById('base_price').value = data.price ? data.price.replace(/[^\d]/g, '') : '';
        document.getElementById('description').value = data.description || '';

        const variantSelect = document.getElementById('variant');
        variantSelect.value = data.variant || '';

        document.getElementById('enginesContainer').innerHTML = '';
        document.getElementById('colorsContainer').innerHTML = '';
        document.getElementById('wheelsContainer').innerHTML = '';
        document.getElementById('interiorUploadsContainer').innerHTML = '';

        if (data.config.engines && data.config.engines.length) data.config.engines.forEach(e => addEngineRow(e));
        else addEngineRow();

        if (data.config.colors) {
            Object.keys(data.config.colors).forEach(key => {
                const colorData = data.config.colors[key];
                colorData.photos = data.colorImages
                    .filter(img => img.color_key === key)
                    .map(img => img.path);
                colorData.photoIds = data.colorImages
                    .filter(img => img.color_key === key)
                    .map(img => img.id);
                addColorRow({ ...colorData, key });
            });
        } else addColorRow();

        if (data.config.wheels && data.config.wheels.length) {
            data.config.wheels.forEach(w => {
                if (w.image_url) w.photos = [w.image_url];
                addWheelRow(w);
            });
        } else addWheelRow();

        fillExistingInteriorPhotos(data.interiorImages);
        fillEditPreviews(data);

        new bootstrap.Modal(document.getElementById('carModal')).show();
    }

    document.getElementById('carForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const errorContainer = document.getElementById('modalErrorContainer');
        errorContainer.classList.add('d-none');
        errorContainer.innerHTML = '';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (response.ok) {
                bootstrap.Modal.getInstance(document.getElementById('carModal')).hide();
                location.reload();
            } else if (response.status === 422) {
                let html = '<ul class="mb-0">';
                Object.keys(data.errors).forEach(key => {
                    data.errors[key].forEach(msg => { html += `<li>${msg}</li>`; });
                });
                html += '</ul>';
                errorContainer.innerHTML = html;
                errorContainer.classList.remove('d-none');
            } else {
                errorContainer.innerHTML = 'Произошла неизвестная ошибка';
                errorContainer.classList.remove('d-none');
            }
        } catch (err) {
            console.error(err);
            errorContainer.innerHTML = 'Ошибка соединения с сервером';
            errorContainer.classList.remove('d-none');
        }
    });

    function initModalHandlers() {
        document.querySelectorAll('.mobile-card').forEach(row => {
            const btn = row.querySelector('.row-more-btn');
            if (!btn) return;
            btn.addEventListener('click', () => {
                const data = {
                    id: row.dataset.id,
                    key: row.dataset.key,
                    name: row.dataset.name,
                    price: row.dataset.price,
                    year: row.dataset.year,
                    variant: row.dataset.variant,
                    variantName: row.dataset.variantName,
                    description: row.dataset.description,
                    photo: row.dataset.photo,
                    config: JSON.parse(row.dataset.config || '{}'),
                    modelsCount: row.dataset.modelsCount || '0',
                    interiorCount: row.dataset.interiorCount || '0',
                    colorCount: row.dataset.colorCount || '0',
                    interiorImages: JSON.parse(row.dataset.interiorImages || '[]'),
                    colorImages: JSON.parse(row.dataset.colorImages || '[]'),
                    threeDModel: JSON.parse(row.dataset.threeDModel || 'null')
                };
                fillViewModal(data);
            });
        });
    }

    function fillViewModal(data) {
        document.getElementById('v_id').textContent = data.id || '—';
        document.getElementById('v_key').textContent = data.key || '—';
        document.getElementById('v_name').textContent = data.name || '—';
        document.getElementById('v_price').textContent = data.price ? data.price + ' ₽' : '—';
        document.getElementById('v_year').textContent = data.year || '—';
        document.getElementById('v_variant').textContent = data.variantName || data.variant || '—';
        document.getElementById('v_description').textContent = data.description || '—';
        document.getElementById('v_models_count').textContent = data.modelsCount;
        document.getElementById('v_interior_count').textContent = data.interiorCount;

        const colorList = document.getElementById('v_color_images_list');
        colorList.innerHTML = data.colorImages && data.colorImages.length ?
            data.colorImages.map(photo => `
                <div class="interior-photo-card">
                    <img src="${photo.path}" class="interior-photo-img" onerror="this.src='{{ asset('assets/images/offer/default-car.jpg') }}'">
                    <div class="interior-photo-info">
                        <div class="interior-photo-type">${photo.color_key}</div>
                    </div>
                </div>`).join('') :
            '<div class="no-photos"><p>Нет загруженных фото цветов</p></div>';

        const interiorList = document.getElementById('v_interior_list');
        interiorList.innerHTML = data.interiorImages && data.interiorImages.length ?
            data.interiorImages.map(photo => {
                const date = photo.date ? new Date(photo.date).toLocaleDateString('ru-RU') : 'Неизвестно';
                return `
                <div class="interior-photo-card">
                    <img src="${photo.path}" class="interior-photo-img" onerror="this.src='{{ asset('assets/images/offer/default-car.jpg') }}'">
                    <div class="interior-photo-info">
                        <div class="interior-photo-type">${photo.type}</div>
                        <div class="interior-photo-date">${date}</div>
                    </div>
                </div>`;
            }).join('') :
            '<div class="no-photos"><p>Нет загруженных фото салона</p></div>';

        const enginesList = document.getElementById('v_engines_list');
        enginesList.innerHTML = data.config.engines && data.config.engines.length ?
            data.config.engines.map(e => `<div class="config-item-card mb-2"><div class="row"><div class="col-md-3"><strong>${e.id || '—'}</strong></div><div class="col-md-5"><div class="fw-bold">${e.title || '—'}</div><div class="small text-muted">${e.desc || ''}</div></div><div class="col-md-2"><div class="small">${e.hp || '—'}</div></div><div class="col-md-2"><div class="text-end">${e.price ? e.price + ' ₽' : '0 ₽'}</div></div></div></div>`).join('') :
            '<div class="text-muted">Нет добавленных двигателей</div>';

        const wheelsList = document.getElementById('v_wheels_list');
        wheelsList.innerHTML = data.config.wheels && data.config.wheels.length ?
            data.config.wheels.map(w => `<div class="config-item-card mb-2"><div class="row"><div class="col-md-3"><strong>${w.id || '—'}</strong></div><div class="col-md-5"><div class="fw-bold">${w.title || '—'}</div><div class="small text-muted">${w.desc || ''}</div></div><div class="col-md-4"><div class="text-end">${w.price ? w.price + ' ₽' : '0 ₽'}</div></div></div></div>`).join('') :
            '<div class="text-muted">Нет добавленных колёс</div>';

        const colorsList = document.getElementById('v_colors_list');
        colorsList.innerHTML = data.config.colors && Object.keys(data.config.colors).length ?
            Object.keys(data.config.colors).map(key => {
                const col = data.config.colors[key];
                return `<div class="config-item-card mb-2"><div class="row align-items-center"><div class="col-md-2"><div style="width:30px;height:30px;background-color:${col.hex};border:1px solid #ddd;border-radius:4px;"></div></div><div class="col-md-3"><strong>${key}</strong></div><div class="col-md-4"><div>${col.label || ''}</div><div class="small text-muted">${col.group === 'metal' ? 'Металлик' : 'Неметаллик'}</div></div><div class="col-md-3"><div class="text-end">${col.price ? col.price + ' ₽' : '0 ₽'}</div></div></div></div>`;
            }).join('') :
            '<div class="text-muted">Нет добавленных цветов</div>';

        document.getElementById('delete_id_2').value = data.id;
        document.getElementById('deleteConfigForm').action = '{{ route('admin.car-configs.destroy', '') }}/' + data.id;

        const editBtn = document.getElementById('v_edit_bottom');
        editBtn.onclick = function() {
            const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewConfigModal'));
            if (viewModal) viewModal.hide();
            setTimeout(() => openEditModalWithData(data), 350);
        };
    }

    document.addEventListener('DOMContentLoaded', function() {
        initModalHandlers();
    });
</script>
@endpush