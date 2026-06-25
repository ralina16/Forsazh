@extends('layouts.admin')
@section('title', 'Управление автомобилями')
@push('styles')
    <style>
        .validation-error {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .was-validated .form-control:valid,
        .form-control.is-valid {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        .validation-message {
            display: none;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .form-error-message {
            display: none;
        }

        .char-counter {
            font-size: 0.75rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.25rem;
        }

        .char-counter.warning {
            color: #ffc107;
        }

        .char-counter.error {
            color: #dc3545;
        }

        .all-fields-required {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            border-radius: 8px !important;
        }

        .brand-table-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .dropdown-menu-custom {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-divider-custom {
            margin: 8px 0;
            border-color: #e9ecef;
        }

        @media (max-width: 768px) {
            .brand-table-actions {
                flex-direction: column;
                align-items: center;
            }

            .dropdown-menu-custom {
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                width: 90% !important;
                max-width: 300px !important;
            }
        }

        .w-100 {
            width: 100%;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 24px;
            padding: 0;
            list-style: none;
            flex-wrap: wrap;
        }

        .pagination .page-item {
            margin: 0;
        }

        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 500;
            color: #555;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .pagination .page-link:hover {
            background: #f0f4ff;
            color: #4071CB;
            border-color: #4071CB;
        }

        .pagination .page-item.active .page-link {
            background: #4071CB;
            border-color: #4071CB;
            color: #fff;
            font-weight: 600;
        }

        .pagination .page-item.disabled .page-link {
            color: #bbb;
            background: #f8f9fa;
            border-color: #eee;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-size: 18px;
            padding: 0 10px;
        }

        .pagination+div,
        .pagination~div[class*="text-sm"],
        .pagination~div[class*="text-gray"] {
            display: none !important;
        }
    </style>
@endpush
@section('content')
    @if (session('open_modal'))
        <input type="hidden" id="open_modal" value="{{ session('open_modal') }}">
    @endif
    @if (session('edit_car_id'))
        <input type="hidden" id="edit_car_id" value="{{ session('edit_car_id') }}">
    @endif
    @if ($errors->any())
        <input type="hidden" id="has_validation_errors" value="1">
    @endif

    <div class="d-none d-xl-block mt-2 mb-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Автомобили</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Автомобили</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
                <i class="bi bi-plus-lg me-1"></i> Добавить автомобиль
            </button>
        </div>
    </div>

    <div class="table-wrapper mb-4">
        <div class="table-inner">
            <table class="table car-table my-5">
                <thead>
                    <tr class="text-center align-middle">
                        <th>ID</th>
                        <th>Фото</th>
                        <th>Марка</th>
                        <th>Модель</th>
                        <th>Описание</th>
                        <th>Привод</th>
                        <th>Объем</th>
                        <th>Топливо</th>
                        <th>Состояние</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody class="text-center align-middle">
                    @foreach ($cars as $c)
                        @php
                            $dataAttrs = [
                                'data-id' => $c->id,
                                'data-brand' => $c->brand ?? '',
                                'data-model' => $c->model,
                                'data-photo' => $c->photo
                                    ? asset('storage/' . $c->photo)
                                    : asset('assets/images/one_car/placeholder.png'),
                                'data-catalog-photo' => $c->catalog_photo ? asset('storage/' . $c->catalog_photo) : '',
                                'data-drive' => $c->drive ?? '',
                                'data-engine' => $c->engine ? $c->engine . ' L' : '',
                                'data-fuel' => $c->fuel ?? '',
                                'data-condition' => $c->condition ?? '',
                                'data-owners' => $c->owners ?? '',
                                'data-transmissions' => $c->transmissions ?? '',
                                'data-trunk' => $c->trunk ? $c->trunk . ' L' : '',
                                'data-gearbox' => $c->gearbox ?? '',
                                'data-body' => $c->body ?? '',
                                'data-price' => $c->price ? number_format($c->price, 0, '.', ' ') . ' ₽' : '',
                                'data-description' => $c->description ?? '',
                                'data-mileage' => $c->mileage ?? 0,
                            ];
                        @endphp
                        <tr class="mobile-card"
                            @foreach ($dataAttrs as $key => $val)
                        {{ $key }}="{{ $val }}" @endforeach>
                            <td data-label="ID">{{ $c->id }}</td>
                            <td data-label="Фото">
                                @php
                                    $display_photo = $c->catalog_photo
                                        ? asset('storage/' . $c->catalog_photo)
                                        : ($c->photo
                                            ? asset('storage/' . $c->photo)
                                            : asset('assets/images/one_car/placeholder.png'));
                                @endphp
                                <img src="{{ $display_photo }}" alt="Фото" class="car-table-img"
                                    style="max-width:120px;">
                            </td>
                            <td data-label="Марка">{{ $c->brand ?? '' }}</td>
                            <td class="td-model" data-label="Модель">{{ $c->model }}</td>
                            <td class="td-description ps-3" data-label="Описание">
                                @php
                                    $description = $c->description ?? '';
                                    if (!empty($description)) {
                                        echo htmlspecialchars(mb_strimwidth($description, 0, 30, '...'));
                                    } else {
                                        echo '—';
                                    }
                                @endphp
                            </td>
                            <td data-label="Привод">{{ $c->drive ?? '—' }}</td>
                            <td data-label="Объем">{{ $c->engine ?? '—' }}</td>
                            <td data-label="Топливо">{{ $c->fuel ?? '—' }}</td>
                            <td data-label="Состояние">{{ $c->condition ?? '—' }}</td>
                            <td>
                                <button class="row-more-btn btn-light" data-bs-toggle="modal" data-bs-target="#viewRowModal"
                                    aria-label="Показать">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <input type="hidden" class="images-data"
                                    value='{{ json_encode($c->images->map(fn($img) => ['id' => $img->id, 'path' => asset('storage/' . $img->path), 'is_main' => $img->is_main])) }}'>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mobile-cards my-4"></div>
        </div>
    </div>

    {{-- РАЗДЕЛ УПРАВЛЕНИЯ МАРКАМИ --}}
    <div class="mt-5 pt-4 border-top">
        <div class="d-flex justify-content-between align-items-center mb-5 mt-3">
            <h4 class="mb-0">Управление марками автомобилей</h4>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                    <i class="bi bi-plus-lg me-1"></i> Добавить марку
                </button>
            </div>
        </div>
        <div class="table-wrapper mb-4">
            <div class="table-inner">
                <table class="table car-table">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>ID</th>
                            <th>Название</th>
                            <th>Статус</th>
                            <th>Автомобилей</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody class="text-center align-middle">
                        @foreach ($carBrands as $brand)
                            @php
                                $carsCount = \App\Models\Car::where('brand', $brand->name)->count();
                            @endphp
                            <tr>
                                <td data-label="ID">{{ $brand->id }}</td>
                                <td data-label="Название">{{ $brand->name }}</td>
                                <td data-label="Статус">
                                    <form method="POST" action="{{ route('admin.cars.toggleBrand', $brand->id) }}"
                                        class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="status-text"
                                            style="background: none; border: none; padding: 0; cursor: pointer; font-weight: 500; color: {{ $brand->is_active ? '#198754' : '#6c757d' }};">
                                            {{ $brand->is_active ? 'Активна' : 'Неактивна' }}
                                        </button>
                                    </form>
                                </td>
                                <td data-label="Автомобилей">
                                    <span style="font-weight: 500; color: #4071CB;">{{ $carsCount }}</span>
                                </td>
                                <td data-label="Действия">
                                    <button class="btn btn-primary btn-sm edit-brand-btn" data-bs-toggle="modal"
                                        data-bs-target="#editBrandModal" data-brand-id="{{ $brand->id }}"
                                        data-brand-name="{{ $brand->name }}">
                                        Редактировать
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        @if ($carBrands->isEmpty())
                            <tr>
                                <td colspan="5" class="text-muted py-4">Нет добавленных марок</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        @if ($carBrands->hasPages())
            <nav aria-label="Page navigation brands" class="mt-4">
                <ul class="pagination justify-content-center">
                    {{ $carBrands->links() }}
                </ul>
            </nav>
        @endif
    </div>

    {{-- МОДАЛКА ДОБАВЛЕНИЯ МАРКИ --}}
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить марку</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form method="POST" action="{{ route('admin.cars.addBrand') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="brand_name_select" class="form-label required-field">Название марки</label>
                            <select id="brand_name_select" name="brand_name" class="form-select" required>
                                <option value="">Выберите марку...</option>
                                @foreach ($existingCarBrands as $existingBrand)
                                    <option value="{{ $existingBrand }}">{{ $existingBrand }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Выберите марку из существующих автомобилей или введите новую ниже</div>
                        </div>
                        <div class="mb-3">
                            <label for="brand_name_input" class="form-label">Или введите новую марку</label>
                            <input type="text" class="form-control" id="brand_name_input" name="brand_name"
                                maxlength="100" placeholder="Введите новую марку">
                            <div class="form-text">Максимум 100 символов</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить марку</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- МОДАЛКА РЕДАКТИРОВАНИЯ МАРКИ --}}
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Редактировать марку</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form method="POST" action="" id="editBrandForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="brand_id" id="edit_brand_id" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_brand_name" class="form-label required-field">Название марки</label>
                            <input type="text" class="form-control" id="edit_brand_name" name="brand_name" required
                                maxlength="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- МОДАЛКА ДОБАВЛЕНИЯ АВТОМОБИЛЯ --}}
    <div class="modal fade add-modal" id="addCarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Добавить автомобиль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form id="addCarForm" method="POST" action="{{ route('admin.cars.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="add_brand" class="form-label required-field">Марка</label>
                                <div class="input-group">
                                    <select id="add_brand" name="brand"
                                        class="form-select @error('brand') is-invalid @enderror" required>
                                        <option value="">Выберите марку...</option>
                                        @foreach ($existingCarBrands as $existingBrand)
                                            <option value="{{ $existingBrand }}"
                                                {{ old('brand') == $existingBrand ? 'selected' : '' }}>
                                                {{ $existingBrand }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal"
                                        data-bs-target="#addBrandModal" title="Добавить новую марку">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Выберите марку из списка. Если нужной марки нет, нажмите
                                    <strong>+</strong> чтобы добавить</div>
                            </div>

                            <!-- Модель -->
                            <div class="col-md-6">
                                <label for="add_model" class="form-label required-field">Модель</label>
                                <input id="add_model" name="model" type="text"
                                    class="form-control @error('model') is-invalid @enderror" maxlength="30"
                                    placeholder="LX 500d" value="{{ old('model') }}">
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="add_model_counter">0/30</div>
                            </div>

                            <!-- Привод -->
                            <div class="col-md-6">
                                <label for="add_drive" class="form-label required-field">Привод</label>
                                <select id="add_drive" name="drive"
                                    class="form-select @error('drive') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option {{ old('drive') == 'Полный' ? 'selected' : '' }}>Полный</option>
                                    <option {{ old('drive') == 'Передний' ? 'selected' : '' }}>Передний</option>
                                    <option {{ old('drive') == 'Задний' ? 'selected' : '' }}>Задний</option>
                                </select>
                                @error('drive')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Объем двигателя -->
                            <div class="col-md-6">
                                <label for="add_engine" class="form-label required-field">Объем двигателя (L)</label>
                                <input id="add_engine" name="engine" type="number" step="0.1" min="0.1"
                                    max="20.0" class="form-control @error('engine') is-invalid @enderror"
                                    placeholder="5.0" value="{{ old('engine') }}">
                                @error('engine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Топливо -->
                            <div class="col-md-4">
                                <label for="add_fuel" class="form-label required-field">Тип топлива</label>
                                <select id="add_fuel" name="fuel"
                                    class="form-select @error('fuel') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option {{ old('fuel') == 'Бензин' ? 'selected' : '' }}>Бензин</option>
                                    <option {{ old('fuel') == 'Дизель' ? 'selected' : '' }}>Дизель</option>
                                    <option {{ old('fuel') == 'Гибрид' ? 'selected' : '' }}>Гибрид</option>
                                    <option {{ old('fuel') == 'Электро' ? 'selected' : '' }}>Электро</option>
                                </select>
                                @error('fuel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Пробег -->
                            <div class="col-md-4">
                                <label for="add_mileage" class="form-label required-field">Пробег (км)</label>
                                <input id="add_mileage" name="mileage" type="number" min="0" max="10000000"
                                    class="form-control @error('mileage') is-invalid @enderror" placeholder="140000"
                                    value="{{ old('mileage') }}">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Состояние -->
                            <div class="col-md-4">
                                <label for="add_condition" class="form-label required-field">Состояние</label>
                                <select id="add_condition" name="condition"
                                    class="form-select @error('condition') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option {{ old('condition') == 'Новая' ? 'selected' : '' }}>Новая</option>
                                    <option {{ old('condition') == 'Не битая' ? 'selected' : '' }}>Не битая</option>
                                    <option {{ old('condition') == 'Битая' ? 'selected' : '' }}>Битая</option>
                                    <option {{ old('condition') == 'Аварийная' ? 'selected' : '' }}>Аварийная</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Владельцы -->
                            <div class="col-md-6">
                                <label for="add_owners" class="form-label required-field">Кол-во владельцев</label>
                                <input id="add_owners" name="owners" type="number" min="0" max="100"
                                    class="form-control @error('owners') is-invalid @enderror" placeholder="1"
                                    value="{{ old('owners') }}">
                                @error('owners')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Передачи -->
                            <div class="col-md-6">
                                <label for="add_transmissions" class="form-label required-field">Кол-во передач</label>
                                <input id="add_transmissions" name="transmissions" type="number" min="1"
                                    max="20" class="form-control @error('transmissions') is-invalid @enderror"
                                    placeholder="8" value="{{ old('transmissions') }}">
                                @error('transmissions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Багажник -->
                            <div class="col-md-6">
                                <label for="add_trunk" class="form-label required-field">Вместимость багажника (L)</label>
                                <input id="add_trunk" name="trunk" type="number" min="1" max="10000"
                                    class="form-control @error('trunk') is-invalid @enderror" placeholder="701"
                                    value="{{ old('trunk') }}">
                                @error('trunk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Коробка передач -->
                            <div class="col-md-6">
                                <label for="add_gearbox" class="form-label required-field">Коробка передач</label>
                                <select id="add_gearbox" name="gearbox"
                                    class="form-select @error('gearbox') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option {{ old('gearbox') == 'Автомат' ? 'selected' : '' }}>Автомат</option>
                                    <option {{ old('gearbox') == 'Механика' ? 'selected' : '' }}>Механика</option>
                                    <option {{ old('gearbox') == 'Робот' ? 'selected' : '' }}>Робот</option>
                                    <option {{ old('gearbox') == 'Вариатор' ? 'selected' : '' }}>Вариатор</option>
                                </select>
                                @error('gearbox')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Кузов -->
                            <div class="col-md-6">
                                <label for="add_body" class="form-label required-field">Тип кузова</label>
                                <select id="add_body" name="body"
                                    class="form-select @error('body') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option {{ old('body') == 'Кроссовер' ? 'selected' : '' }}>Кроссовер</option>
                                    <option {{ old('body') == 'Седан' ? 'selected' : '' }}>Седан</option>
                                    <option {{ old('body') == 'Хэтчбек' ? 'selected' : '' }}>Хэтчбек</option>
                                    <option {{ old('body') == 'Универсал' ? 'selected' : '' }}>Универсал</option>
                                    <option {{ old('body') == 'Купе' ? 'selected' : '' }}>Купе</option>
                                    <option {{ old('body') == 'Кабриолет' ? 'selected' : '' }}>Кабриолет</option>
                                    <option {{ old('body') == 'Внедорожник' ? 'selected' : '' }}>Внедорожник</option>
                                    <option {{ old('body') == 'Минивэн' ? 'selected' : '' }}>Минивэн</option>
                                    <option {{ old('body') == 'Пикап' ? 'selected' : '' }}>Пикап</option>
                                </select>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Цена -->
                            <div class="col-md-6">
                                <label for="add_price" class="form-label required-field">Цена (₽)</label>
                                <div class="input-group">
                                    <input id="add_price" name="price" type="number" min="0" max="1000000000"
                                        class="form-control @error('price') is-invalid @enderror" placeholder="12500000"
                                        value="{{ old('price') }}">
                                    <span class="input-group-text">₽</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Описание -->
                            <div class="col-12">
                                <label for="add_description" class="form-label required-field">Описание</label>
                                <textarea id="add_description" name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="add_description_counter">0/2000</div>
                            </div>
                            <!-- Catalog photo -->
                            <div class="col-12">
                                <label for="add_catalog_photo" class="form-label required-field">Фото для карточки
                                    каталога (главное)</label>
                                <input type="file" accept="image/*" id="add_catalog_photo" name="catalog_photo"
                                    class="form-control @error('catalog_photo') is-invalid @enderror">
                                @error('catalog_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Доп. фото -->
                            <div class="col-12">
                                <label for="add_images" class="form-label required-field">Дополнительные фото для
                                    галереи</label>
                                <input type="file" accept="image/*" id="add_images" name="images[]"
                                    class="form-control @error('images') is-invalid @enderror" multiple>
                                @error('images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-primary w-100 btn"><i class="bi bi-save me-1"></i>
                            Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- МОДАЛКА РЕДАКТИРОВАНИЯ АВТОМОБИЛЯ --}}
    <div class="modal fade edit-modal" id="editCarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="small text-muted">ID: <strong id="edit_id_label">—</strong></div>
                        <h5 class="modal-title mb-0">Редактировать автомобиль</h5>
                    </div>
                    <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form id="editCarForm" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit_id" value="{{ old('id') }}">
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            <div class="col-12" id="edit_current_catalog_photo"></div>
                            <div class="col-12">
                                <label for="edit_catalog_photo" class="form-label">Новое фото для каталога (оставьте
                                    пустым, если не меняете)</label>
                                <input type="file" accept="image/*" id="edit_catalog_photo" name="catalog_photo"
                                    class="form-control @error('catalog_photo') is-invalid @enderror">
                                @error('catalog_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Текущие фотографии галереи</label>
                                <div class="existing-images d-flex flex-wrap gap-3 mb-3" id="edit_existing_images"></div>
                            </div>
                            <!-- Добавление новых фото -->
                            <div class="col-12">
                                <label for="edit_images" class="form-label">Добавить новые фотографии</label>
                                <input type="file" accept="image/*" id="edit_images" name="images[]"
                                    class="form-control @error('images.*') is-invalid @enderror" multiple>
                                @error('images.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Марка -->
                            <div class="col-md-6">
                                <label for="edit_brand" class="form-label required-field">Марка</label>
                                <input id="edit_brand" name="brand" type="text"
                                    class="form-control @error('brand') is-invalid @enderror" maxlength="20"
                                    placeholder="Lexus" value="{{ old('brand') }}">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="edit_brand_counter">0/20</div>
                            </div>
                            <!-- Модель -->
                            <div class="col-md-6">
                                <label for="edit_model" class="form-label required-field">Модель</label>
                                <input id="edit_model" name="model" type="text"
                                    class="form-control @error('model') is-invalid @enderror" maxlength="30"
                                    placeholder="LX 500d" value="{{ old('model') }}">
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="edit_model_counter">0/30</div>
                            </div>
                            <!-- Привод -->
                            <div class="col-md-6">
                                <label for="edit_drive" class="form-label required-field">Привод</label>
                                <select id="edit_drive" name="drive"
                                    class="form-select @error('drive') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option value="Полный" {{ old('drive') == 'Полный' ? 'selected' : '' }}>Полный
                                    </option>
                                    <option value="Передний" {{ old('drive') == 'Передний' ? 'selected' : '' }}>Передний
                                    </option>
                                    <option value="Задний" {{ old('drive') == 'Задний' ? 'selected' : '' }}>Задний
                                    </option>
                                </select>
                                @error('drive')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Объем двигателя -->
                            <div class="col-md-6">
                                <label for="edit_engine" class="form-label required-field">Объем двигателя (L)</label>
                                <input id="edit_engine" name="engine" type="number" step="0.1" min="0.1"
                                    max="20.0" class="form-control @error('engine') is-invalid @enderror"
                                    placeholder="5.0" value="{{ old('engine') }}">
                                @error('engine')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Топливо -->
                            <div class="col-md-4">
                                <label for="edit_fuel" class="form-label required-field">Тип топлива</label>
                                <select id="edit_fuel" name="fuel"
                                    class="form-select @error('fuel') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option value="Бензин" {{ old('fuel') == 'Бензин' ? 'selected' : '' }}>Бензин</option>
                                    <option value="Дизель" {{ old('fuel') == 'Дизель' ? 'selected' : '' }}>Дизель</option>
                                    <option value="Гибрид" {{ old('fuel') == 'Гибрид' ? 'selected' : '' }}>Гибрид</option>
                                    <option value="Электро" {{ old('fuel') == 'Электро' ? 'selected' : '' }}>Электро
                                    </option>
                                </select>
                                @error('fuel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Пробег -->
                            <div class="col-md-4">
                                <label for="edit_mileage" class="form-label required-field">Пробег (км)</label>
                                <input id="edit_mileage" name="mileage" type="number" min="0" max="10000000"
                                    class="form-control @error('mileage') is-invalid @enderror" placeholder="140000"
                                    value="{{ old('mileage') }}">
                                @error('mileage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Состояние -->
                            <div class="col-md-4">
                                <label for="edit_condition" class="form-label required-field">Состояние</label>
                                <select id="edit_condition" name="condition"
                                    class="form-select @error('condition') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option value="Новая" {{ old('condition') == 'Новая' ? 'selected' : '' }}>Новая
                                    </option>
                                    <option value="Не битая" {{ old('condition') == 'Не битая' ? 'selected' : '' }}>Не
                                        битая</option>
                                    <option value="Битая" {{ old('condition') == 'Битая' ? 'selected' : '' }}>Битая
                                    </option>
                                    <option value="Аварийная" {{ old('condition') == 'Аварийная' ? 'selected' : '' }}>
                                        Аварийная</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Владельцы -->
                            <div class="col-md-6">
                                <label for="edit_owners" class="form-label required-field">Кол-во владельцев</label>
                                <input id="edit_owners" name="owners" type="number" min="0" max="100"
                                    class="form-control @error('owners') is-invalid @enderror" placeholder="1"
                                    value="{{ old('owners') }}">
                                @error('owners')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Передачи -->
                            <div class="col-md-6">
                                <label for="edit_transmissions" class="form-label required-field">Кол-во передач</label>
                                <input id="edit_transmissions" name="transmissions" type="number" min="1"
                                    max="20" class="form-control @error('transmissions') is-invalid @enderror"
                                    placeholder="8" value="{{ old('transmissions') }}">
                                @error('transmissions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Багажник -->
                            <div class="col-md-6">
                                <label for="edit_trunk" class="form-label required-field">Вместимость багажника
                                    (L)</label>
                                <input id="edit_trunk" name="trunk" type="number" min="1" max="10000"
                                    class="form-control @error('trunk') is-invalid @enderror" placeholder="701"
                                    value="{{ old('trunk') }}">
                                @error('trunk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Коробка передач -->
                            <div class="col-md-6">
                                <label for="edit_gearbox" class="form-label required-field">Коробка передач</label>
                                <select id="edit_gearbox" name="gearbox"
                                    class="form-select @error('gearbox') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option value="Автомат" {{ old('gearbox') == 'Автомат' ? 'selected' : '' }}>Автомат
                                    </option>
                                    <option value="Механика" {{ old('gearbox') == 'Механика' ? 'selected' : '' }}>
                                        Механика</option>
                                    <option value="Робот" {{ old('gearbox') == 'Робот' ? 'selected' : '' }}>Робот
                                    </option>
                                    <option value="Вариатор" {{ old('gearbox') == 'Вариатор' ? 'selected' : '' }}>
                                        Вариатор</option>
                                </select>
                                @error('gearbox')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Кузов -->
                            <div class="col-md-6">
                                <label for="edit_body" class="form-label required-field">Тип кузова</label>
                                <select id="edit_body" name="body"
                                    class="form-select @error('body') is-invalid @enderror">
                                    <option value="">Выберите...</option>
                                    <option value="Кроссовер" {{ old('body') == 'Кроссовер' ? 'selected' : '' }}>
                                        Кроссовер</option>
                                    <option value="Седан" {{ old('body') == 'Седан' ? 'selected' : '' }}>Седан</option>
                                    <option value="Хэтчбек" {{ old('body') == 'Хэтчбек' ? 'selected' : '' }}>Хэтчбек
                                    </option>
                                    <option value="Универсал" {{ old('body') == 'Универсал' ? 'selected' : '' }}>
                                        Универсал</option>
                                    <option value="Купе" {{ old('body') == 'Купе' ? 'selected' : '' }}>Купе</option>
                                    <option value="Кабриолет" {{ old('body') == 'Кабриолет' ? 'selected' : '' }}>
                                        Кабриолет</option>
                                    <option value="Внедорожник" {{ old('body') == 'Внедорожник' ? 'selected' : '' }}>
                                        Внедорожник</option>
                                    <option value="Минивэн" {{ old('body') == 'Минивэн' ? 'selected' : '' }}>Минивэн
                                    </option>
                                    <option value="Пикап" {{ old('body') == 'Пикап' ? 'selected' : '' }}>Пикап</option>
                                </select>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- Цена -->
                            <div class="col-md-6">
                                <label for="edit_price" class="form-label required-field">Цена (₽)</label>
                                <div class="input-group">
                                    <input id="edit_price" name="price" type="number" min="0"
                                        max="1000000000" class="form-control @error('price') is-invalid @enderror"
                                        placeholder="12500000" value="{{ old('price') }}">
                                    <span class="input-group-text">₽</span>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Описание -->
                            <div class="col-12">
                                <label for="edit_description" class="form-label required-field">Описание</label>
                                <textarea id="edit_description" name="description" rows="4"
                                    class="form-control @error('description') is-invalid @enderror" maxlength="2000">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="char-counter" id="edit_description_counter">0/2000</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn-primary w-100 btn"><i class="bi bi-save me-1"></i> Сохранить
                            изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- VIEW MODAL --}}
    <div class="modal fade view-modal" id="viewRowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="modal-title" id="viewRowModalLabel">Информация об автомобиле</h5>
                        <div class="small text-muted">ID: <strong id="v_id">—</strong> · Пробег: <strong
                                id="v_mileage">—</strong></div>
                    </div>
                    <button type="button" class="btn-close ms-1" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div id="carImagesCarousel" class="carousel slide mb-3" data-bs-ride="carousel">
                                <div class="carousel-inner rounded" id="v_carousel_inner"></div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carImagesCarousel"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Предыдущее</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carImagesCarousel"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Следующее</span>
                                </button>
                            </div>
                            <div class="mb-3">
                                <h4 class="mb-1" id="v_model">—</h4>
                                <div class="details-badges mb-2" id="v_badges"></div>
                                <h5 class="text-primary mb-2" id="v_price">—</h5>
                                <div class="small text-muted">ID: <strong id="v_id_2">—</strong> · Пробег: <strong
                                        id="v_mileage_2">—</strong></div>
                            </div>
                        </div>
                        <div class="col-12 mb-4">
                            <h6 class="border-bottom pb-2">Характеристики</h6>
                            <div class="meta-grid">
                                <div class="meta-item row">
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Марка</div>
                                        <div class="data-value" id="v_brand">—</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Состояние</div>
                                        <div class="data-value" id="v_condition">—</div>
                                    </div>
                                </div>
                                <div class="meta-item row">
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Владельцы</div>
                                        <div class="data-value" id="v_owners">—</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Кол-во передач</div>
                                        <div class="data-value" id="v_transmissions">—</div>
                                    </div>
                                </div>
                                <div class="meta-item row">
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Багажник</div>
                                        <div class="data-value" id="v_trunk">—</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Тип кузова</div>
                                        <div class="data-value" id="v_body">—</div>
                                    </div>
                                </div>
                                <div class="meta-item row">
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Привод</div>
                                        <div class="data-value" id="v_drive">—</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Топливо</div>
                                        <div class="data-value" id="v_fuel">—</div>
                                    </div>
                                </div>
                                <div class="meta-item row">
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Объем двигателя</div>
                                        <div class="data-value" id="v_engine">—</div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="data-label">Коробка передач</div>
                                        <div class="data-value" id="v_gearbox">—</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="border-bottom pb-2">Описание</h6>
                            <div class="data-value" id="v_description" style="line-height: 1.6; white-space: pre-line;">—
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <form method="POST" action=""
                            onsubmit="return confirm('Удалить автомобиль? Эта операция необратима.');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" id="delete_id_2" value="">
                            <button type="submit" class="btn btn-danger">Удалить</button>
                        </form>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                        <button type="button" class="btn btn-primary" id="v_edit_bottom">Редактировать</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initCharCounters();
        initModalHandlers();
        initBrandManagement();

        const addForm = document.getElementById('addCarForm');
        if (addForm) {
            addForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitFormAjax(this, 'addCarModal');
            });
        }

        const editForm = document.getElementById('editCarForm');
        if (editForm) {
            editForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitFormAjax(this, 'editCarModal');
            });
        }

        const hasErrors = document.getElementById('has_validation_errors')?.value;
        const openModal = document.getElementById('open_modal')?.value;
        const editId = document.getElementById('edit_car_id')?.value;

        if (hasErrors || openModal) {
            let modalToOpen = openModal;
            if (hasErrors && !modalToOpen) {
                const errorFields = document.querySelectorAll('.is-invalid');
                if (errorFields.length > 0) {
                    const firstError = errorFields[0];
                    const form = firstError.closest('form');
                    if (form && form.id === 'addCarForm') modalToOpen = 'addCarModal';
                    else if (form && form.id === 'editCarForm') modalToOpen = 'editCarModal';
                    else if (form && form.id === 'editBrandForm') modalToOpen = 'editBrandModal';
                    else if (form && form.id === 'addBrandForm') modalToOpen = 'addBrandModal';
                }
            }

            if (modalToOpen === 'addCarModal') {
                new bootstrap.Modal(document.getElementById('addCarModal')).show();
            } else if (modalToOpen === 'editCarModal' && editId) {
                const row = document.querySelector(`tr[data-id="${editId}"]`);
                if (row) {
                    const data = row.dataset;
                    const imagesJson = row.querySelector('.images-data')?.value || '[]';
                    const images = JSON.parse(imagesJson);
                    openEditModalWithData(data, images);
                }
            } else if (modalToOpen === 'addBrandModal') {
                new bootstrap.Modal(document.getElementById('addBrandModal')).show();
            } else if (modalToOpen === 'editBrandModal') {
                new bootstrap.Modal(document.getElementById('editBrandModal')).show();
            }
        }
    });

    async function submitFormAjax(form, modalId) {
        const formData = new FormData(form);
        const url = form.action;
        const modalEl = document.getElementById(modalId);
        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

        clearFieldErrors(form);

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                window.location.reload();
            } else if (response.status === 422) {
                displayFieldErrors(form, data.errors);
            } else {
                alert(data.error || 'Произошла ошибка сервера');
            }
        } catch (error) {
            console.error('Ошибка отправки:', error);
            alert('Ошибка соединения. Проверьте интернет.');
        }
    }

    function clearFieldErrors(form) {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    }

    function displayFieldErrors(form, errors) {
        for (let field in errors) {
            let input = form.querySelector(`[name="${field}"]`);
            if (!input) input = form.querySelector(`[name="${field}[]"]`);

            if (input) {
                input.classList.add('is-invalid');
                let feedback = input.nextElementSibling;
                if (!feedback || !feedback.classList.contains('invalid-feedback')) {
                    feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    input.parentNode.insertBefore(feedback, input.nextSibling);
                }
                feedback.textContent = errors[field][0];
            }
        }
    }

    function initCharCounters() {
        const counters = {
            'add_brand': 20,
            'add_model': 30,
            'add_description': 2000,
            'edit_brand': 20,
            'edit_model': 30,
            'edit_description': 2000
        };

        Object.keys(counters).forEach(id => {
            const input = document.getElementById(id);
            const counterEl = document.getElementById(id + '_counter');
            if (input && counterEl) {
                const max = counters[id];
                const update = () => updateCharCounter(id + '_counter', input.value.length, max);
                input.addEventListener('input', update);
                update();
            }
        });
    }

    function updateCharCounter(counterId, currentLength, maxLength) {
        const counter = document.getElementById(counterId);
        if (!counter) return;
        counter.textContent = `${currentLength}/${maxLength}`;
        counter.className = 'char-counter';
        if (currentLength > maxLength) counter.classList.add('error');
        else if (currentLength > maxLength * 0.8) counter.classList.add('warning');
    }

    // ===== ОБРАБОТЧИКИ МОДАЛЬНЫХ ОКОН =====
    function initModalHandlers() {
        let currentCarData = null;
        let currentCarImages = [];

        const vEditBottom = document.getElementById('v_edit_bottom');
        if (vEditBottom) {
            vEditBottom.addEventListener('click', function() {
                if (currentCarData) {
                    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRowModal'));
                    if (viewModal) viewModal.hide();
                    setTimeout(() => openEditModalWithData(currentCarData, currentCarImages), 350);
                }
            });
        }

        document.querySelectorAll('.mobile-card').forEach(row => {
            const btn = row.querySelector('.row-more-btn');
            if (!btn) return;
            btn.addEventListener('click', () => {
                const data = row.dataset;
                const imagesJson = row.querySelector('.images-data')?.value || '[]';
                currentCarImages = JSON.parse(imagesJson);
                currentCarData = data;
                fillViewModal(data, currentCarImages);
            });
        });

        setupNewCarCondition('add');
        setupNewCarCondition('edit');
    }

    function setupNewCarCondition(mode) {
        const prefix = mode === 'add' ? 'add' : 'edit';
        const conditionSelect = document.getElementById(prefix + '_condition');
        const mileageInput = document.getElementById(prefix + '_mileage');
        const ownersInput = document.getElementById(prefix + '_owners');

        if (!conditionSelect || !mileageInput || !ownersInput) return;

        function toggleFields(isNew) {
            if (isNew) {
                mileageInput.value = '0';
                ownersInput.value = '0';
                mileageInput.readOnly = true;
                ownersInput.readOnly = true;
                mileageInput.style.backgroundColor = '#f8f9fa';
                ownersInput.style.backgroundColor = '#f8f9fa';
            } else {
                mileageInput.readOnly = false;
                ownersInput.readOnly = false;
                mileageInput.style.backgroundColor = '';
                ownersInput.style.backgroundColor = '';
            }
        }

        conditionSelect.addEventListener('change', () => toggleFields(conditionSelect.value === 'Новая'));
        toggleFields(conditionSelect.value === 'Новая');
    }

    // ===== УПРАВЛЕНИЕ МАРКАМИ =====
    function initBrandManagement() {
        document.querySelectorAll('.edit-brand-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit_brand_id').value = this.dataset.brandId;
                document.getElementById('edit_brand_name').value = this.dataset.brandName;
                document.getElementById('editBrandForm').action =
                    '{{ route('admin.cars.updateBrand', '') }}/' + this.dataset.brandId;
            });
        });

        const brandSelect = document.getElementById('brand_name_select');
        const brandInput = document.getElementById('brand_name_input');
        if (brandSelect && brandInput) {
            brandSelect.addEventListener('change', () => {
                if (brandSelect.value) brandInput.value = brandSelect.value;
            });
            brandInput.addEventListener('input', () => {
                if (brandInput.value) {
                    const option = Array.from(brandSelect.options).find(opt => opt.value === brandInput.value);
                    if (option) brandSelect.value = brandInput.value;
                }
            });
        }
    }

    // ===== ЗАПОЛНЕНИЕ МОДАЛКИ ПРОСМОТРА =====
    function fillViewModal(data, images) {
        document.getElementById('v_id').textContent = data.id || '—';
        document.getElementById('v_id_2').textContent = data.id || '—';
        document.getElementById('v_brand').textContent = data.brand || '—';
        document.getElementById('v_model').textContent = data.model || '—';
        document.getElementById('v_mileage').textContent = data.mileage || '—';
        document.getElementById('v_mileage_2').textContent = data.mileage || '—';
        document.getElementById('v_condition').textContent = data.condition || '—';
        document.getElementById('v_owners').textContent = data.owners || '—';
        document.getElementById('v_transmissions').textContent = data.transmissions || '—';
        document.getElementById('v_trunk').textContent = data.trunk || '—';
        document.getElementById('v_body').textContent = data.body || '—';
        document.getElementById('v_drive').textContent = data.drive || '—';
        document.getElementById('v_price').textContent = data.price || '—';
        document.getElementById('v_fuel').textContent = data.fuel || '—';
        document.getElementById('v_engine').textContent = data.engine || '—';
        document.getElementById('v_gearbox').textContent = data.gearbox || '—';
        document.getElementById('v_description').textContent = data.description || '—';

        // Бейджи
        const badges = document.getElementById('v_badges');
        badges.innerHTML = '';
        if (data.drive) badges.insertAdjacentHTML('beforeend',
            `<span class="badge bg-primary me-1">${data.drive}</span>`);
        if (data.engine) badges.insertAdjacentHTML('beforeend',
            `<span class="badge bg-secondary me-1">${data.engine}</span>`);
        if (data.fuel) badges.insertAdjacentHTML('beforeend',
        `<span class="badge bg-success me-1">${data.fuel}</span>`);
        if (data.gearbox) badges.insertAdjacentHTML('beforeend',
            `<span class="badge bg-info text-dark me-1">${data.gearbox}</span>`);

        // Карусель фото
        const carouselInner = document.getElementById('v_carousel_inner');
        carouselInner.innerHTML = '';
        if (images && images.length > 0) {
            images.forEach((img, i) => {
                carouselInner.insertAdjacentHTML('beforeend', `
                <div class="carousel-item ${i === 0 ? 'active' : ''}">
                    <img src="${img.path}" class="d-block w-100" style="max-height: 400px; object-fit: cover;" alt="Фото ${i+1}">
                </div>
            `);
            });
        } else {
            carouselInner.innerHTML = `
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/one_car/placeholder.png') }}" class="d-block w-100" style="max-height: 400px; object-fit: cover;" alt="Нет фото">
            </div>
        `;
        }

        document.getElementById('delete_id_2').value = data.id || '';
        document.querySelector('#viewRowModal .modal-footer form').action = '{{ route('admin.cars.destroy', '') }}/' +
            data.id;
    }

    // ===== ОТКРЫТИЕ МОДАЛКИ РЕДАКТИРОВАНИЯ =====
    window.openEditModalWithData = function(data, images) {
        document.getElementById('edit_id').value = data.id || '';
        document.getElementById('edit_id_label').textContent = data.id || '—';
        document.getElementById('editCarForm').action = '{{ route('admin.cars.update', '') }}/' + data.id;

        document.getElementById('edit_brand').value = data.brand || '';
        document.getElementById('edit_model').value = data.model || '';
        document.getElementById('edit_drive').value = data.drive || '';
        document.getElementById('edit_fuel').value = data.fuel || '';
        document.getElementById('edit_condition').value = data.condition || '';
        document.getElementById('edit_gearbox').value = data.gearbox || '';
        document.getElementById('edit_body').value = data.body || '';

        let mileage = data.mileage !== undefined ? String(data.mileage) : '0';
        mileage = mileage.replace(/[^\d]/g, '');
        document.getElementById('edit_mileage').value = mileage;

        document.getElementById('edit_owners').value = data.owners || '';
        document.getElementById('edit_transmissions').value = data.transmissions || '';

        let trunk = data.trunk !== undefined ? String(data.trunk) : '';
        trunk = trunk.replace(/\s*L$/i, '').trim();
        document.getElementById('edit_trunk').value = trunk;

        let engine = data.engine !== undefined ? String(data.engine) : '';
        engine = engine.replace(/\s*L$/i, '').trim();
        document.getElementById('edit_engine').value = engine;

        let price = data.price !== undefined ? String(data.price) : '';
        price = price.replace(/[^\d]/g, '');
        document.getElementById('edit_price').value = price;

        document.getElementById('edit_description').value = data.description || '';

        // Счётчики символов
        updateCharCounter('edit_brand_counter', (data.brand || '').length, 20);
        updateCharCounter('edit_model_counter', (data.model || '').length, 30);
        updateCharCounter('edit_description_counter', (data.description || '').length, 2000);

        // Текущее фото каталога
        const catalogContainer = document.getElementById('edit_current_catalog_photo');
        catalogContainer.innerHTML = data.catalogPhoto ?
            `<div class="mb-3"><strong>Текущее фото каталога:</strong><br><img src="${data.catalogPhoto}" class="img-thumbnail mt-2" style="max-width: 200px; max-height: 150px; object-fit: cover;"></div>` :
            '<div class="text-muted mb-2">Нет загруженного фото для каталога</div>';

        // Существующие фото галереи
        const existingContainer = document.getElementById('edit_existing_images');
        existingContainer.innerHTML = '';
        if (images && images.length > 0) {
            images.forEach(img => {
                existingContainer.insertAdjacentHTML('beforeend', `
                <div class="d-flex align-items-center gap-3 mb-2 p-2 border rounded">
                    <img src="${img.path}" class="img-thumbnail" style="width: 100px; height: 70px; object-fit: cover;">
                </div>
            `);
            });
        } else {
            existingContainer.innerHTML = '<div class="text-muted">Нет загруженных фотографий в галерее</div>';
        }

        const isNew = data.condition === 'Новая';
        const editMileage = document.getElementById('edit_mileage');
        const editOwners = document.getElementById('edit_owners');
        if (isNew) {
            editMileage.readOnly = true;
            editOwners.readOnly = true;
            editMileage.style.backgroundColor = '#f8f9fa';
            editOwners.style.backgroundColor = '#f8f9fa';
        }

        new bootstrap.Modal(document.getElementById('editCarModal')).show();
    };
</script>
