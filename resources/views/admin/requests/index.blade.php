@extends('layouts.admin')

@section('title', 'Управление заявками')

@push('styles')
    <style>
        .dropdown-menu-custom {
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 8px;
        }

        .dropdown-item-custom {
            border-radius: 8px;
            padding: 10px 15px;
            margin: 2px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s ease;
            font-size: 15px;
        }

        .dropdown-item-custom:hover {
            background: transparent !important;
            color: #4071CB;
            transform: translateX(5px);
        }

        .dropdown-divider-custom {
            margin: 8px 0;
            border-color: #e9ecef;
        }

        .request-type-tabs {
            display: flex;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }

        .request-type-tab {
            padding: 0.75rem 1.5rem;
            border: none;
            background: none;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s ease;
            white-space: nowrap;
            font-weight: 600;
            color: #333;
        }

        .request-type-tab.active {
            color: #4071CB;
            border-bottom-color: #4071CB;
            font-weight: 600;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .badge-new {
            background-color: #007bff;
        }

        .badge-processed {
            background-color: #28a745;
        }

        .badge-completed {
            background-color: #6c757d;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 1rem;
        }

        .checkbox-item {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .checkbox-item input[type="checkbox"] {
            display: none;
        }

        .checkbox-item label {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            color: #495057;
        }

        .checkbox-item label:hover {
            border-color: #4071CB;
            background: #f0f4ff;
        }

        .checkbox-item input[type="checkbox"]:checked+label {
            background: #4071CB;
            border-color: #4071CB;
            color: white;
        }

        .checkbox-item label::before {
            content: '';
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid #adb5bd;
            border-radius: 3px;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .checkbox-item input[type="checkbox"]:checked+label::before {
            background-color: white;
            border-color: white;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%234071CB' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='m6 10 3 3 6-6'/%3e%3c/svg%3e");
            background-size: 12px;
            background-repeat: no-repeat;
            background-position: center;
        }

        .checkbox-group-error {
            border: 1px solid #dc3545;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 1rem;
        }

        .checkbox-group-error .invalid-feedback {
            display: block;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback {
            display: block;
        }

        .was-validated .form-control:invalid,
        .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 3.6.4.4.4-.4'/%3e%3cpath d='M6 7v1'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
    </style>
@endpush

@section('content')
    <div class="d-none d-xl-block mb-5 claims container-xxl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">Заявки</li>
            </ol>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 w-100 container-xxl">
        <h4 class="mb-0">Заявки</h4>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRequestModal">
                <i class="bi bi-plus-lg me-1"></i> Добавить заявку
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success container-xxl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger container-xxl">
            {{ session('error') }}
        </div>
    @endif

    <div class="my-5 d-flex flex-column align-items-center container-xxl">

        <!-- TRADE-IN -->
        <h4 class="mb-3">TRADE-IN</h4>
        <div class="table-wrapper mb-4">
            <div class="table-inner">
                <table class="table mb-0">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Номер телефона</th>
                            <th>Адрес дилерского центра</th>
                            <th>Дата создания</th>
                            <th>Управление</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="tradein-table-body">
                        @forelse($tradeInRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->dealer_center }}</td>
                                <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Изменить
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a class="dropdown-item dropdown-item-custom edit-tradein-btn" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editTradeInModal"
                                                    data-id="{{ $request->id }}"
                                                    data-name="{{ $request->name }}"
                                                    data-phone="{{ $request->phone }}"
                                                    data-dealer="{{ $request->dealer_center }}">
                                                    <i class="bi bi-pencil-square"></i>Редактировать
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider-custom"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.requests.trade-in.destroy', $request->id) }}" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="bi bi-trash"></i>Удалить
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Нет заявок</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- АВТОКРЕДИТЫ -->
        <h4 class="mb-3 mt-5">АВТОКРЕДИТЫ</h4>
        <div class="table-wrapper mb-4">
            <div class="table-inner">
                <table class="table mb-0">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Тип авто</th>
                            <th>Сумма</th>
                            <th>Ставка</th>
                            <th>Срок</th>
                            <th>Платёж</th>
                            <th>Дата</th>
                            <th>Управление</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="credit-table-body">
                        @forelse($creditRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->fio }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->car_type == 'new' ? 'Новый' : 'С пробегом' }}</td>
                                <td>{{ number_format($request->credit_amount, 0, '.', ' ') }} ₽</td>
                                <td>{{ $request->interest_rate }}%</td>
                                <td>{{ $request->loan_term }} лет</td>
                                <td>{{ number_format($request->monthly_payment, 0, '.', ' ') }} ₽</td>
                                <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Изменить
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a class="dropdown-item dropdown-item-custom edit-credit-btn" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editCreditModal"
                                                    data-id="{{ $request->id }}"
                                                    data-fio="{{ $request->fio }}"
                                                    data-phone="{{ $request->phone }}"
                                                    data-car_type="{{ $request->car_type }}"
                                                    data-credit_amount="{{ $request->credit_amount }}"
                                                    data-interest_rate="{{ $request->interest_rate }}"
                                                    data-loan_term="{{ $request->loan_term }}"
                                                    data-monthly_payment="{{ $request->monthly_payment }}"
                                                    data-insurance_kasko="{{ $request->insurance_kasko }}"
                                                    data-insurance_as_z="{{ $request->insurance_as_z }}"
                                                    data-early_repayment="{{ $request->early_repayment }}"
                                                    data-notes="{{ $request->notes }}">
                                                    <i class="bi bi-pencil-square"></i>Редактировать
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider-custom"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.requests.credit.destroy', $request->id) }}" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="bi bi-trash"></i>Удалить
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Нет заявок</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- АВТОСТРАХОВАНИЕ -->
        <h4 class="mb-3 mt-5">АВТОСТРАХОВАНИЕ</h4>
        <div class="table-wrapper mb-4">
            <div class="table-inner">
                <table class="table mb-0">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>Тип страхования</th>
                            <th>Стоимость авто</th>
                            <th>Годовая стоимость</th>
                            <th>Платёж в месяц</th>
                            <th>Дата</th>
                            <th>Управление</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="insurance-table-body">
                        @forelse($insuranceRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->fio }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->insurance_type == 'osago' ? 'ОСАГО' : 'КАСКО' }}</td>
                                <td>{{ number_format($request->car_price, 0, '.', ' ') }} ₽</td>
                                <td>{{ number_format($request->estimated_premium, 0, '.', ' ') }} ₽</td>
                                <td>{{ number_format($request->monthly_payment, 0, '.', ' ') }} ₽</td>
                                <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Изменить
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a class="dropdown-item dropdown-item-custom edit-insurance-btn" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editInsuranceModal"
                                                    data-id="{{ $request->id }}"
                                                    data-fio="{{ $request->fio }}"
                                                    data-phone="{{ $request->phone }}"
                                                    data-insurance_type="{{ $request->insurance_type }}"
                                                    data-car_price="{{ $request->car_price }}"
                                                    data-car_age="{{ $request->car_age }}"
                                                    data-estimated_premium="{{ $request->estimated_premium }}"
                                                    data-monthly_payment="{{ $request->monthly_payment }}"
                                                    data-risk_level="{{ $request->risk_level }}">
                                                    <i class="bi bi-pencil-square"></i>Редактировать
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider-custom"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.requests.insurance.destroy', $request->id) }}" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="bi bi-trash"></i>Удалить
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Нет заявок</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ЗАЯВКИ (ОБЩИЕ) -->
        <h4 class="mb-3 mt-5">ЗАЯВКИ</h4>
        <div class="table-wrapper mb-4">
            <div class="table-inner">
                <table class="table mb-0">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Тип заявки</th>
                            <th>Имя</th>
                            <th>Телефон</th>
                            <th>Email</th>
                            <th>Согласие</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th>Управление</th>
                        </tr>
                    </thead>
                    <tbody class="text-center" id="general-table-body">
                        @forelse($generalRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>
                                    @php
                                        $typeLabels = [
                                            'test-drive' => 'Тест-драйв',
                                            'consultation' => 'Консультация',
                                            'car-selection' => 'Подбор авто',
                                            'service' => 'Запись на сервис'
                                        ];
                                        $types = explode(',', $request->request_type);
                                        $labels = array_map(function($type) use ($typeLabels) {
                                            return $typeLabels[$type] ?? $type;
                                        }, $types);
                                        echo implode(', ', $labels);
                                    @endphp
                                </td>
                                <td>{{ $request->name }}</td>
                                <td>{{ $request->phone }}</td>
                                <td>{{ $request->email }}</td>
                                <td>
                                    @if($request->agree)
                                        <span class="badge bg-success">Да</span>
                                    @else
                                        <span class="badge bg-danger">Нет</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'new' => 'badge-new',
                                            'processed' => 'badge-processed',
                                            'completed' => 'badge-completed'
                                        ];
                                        $displayStatus = [
                                            'new' => 'Новая',
                                            'processed' => 'В обработке',
                                            'completed' => 'Завершена'
                                        ];
                                    @endphp
                                    <span class="badge badge-status {{ $statusClass[$request->status] ?? 'badge-secondary' }}">
                                        {{ $displayStatus[$request->status] ?? $request->status }}
                                    </span>
                                </td>
                                <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            Изменить
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-custom">
                                            <li>
                                                <a class="dropdown-item dropdown-item-custom edit-request-btn" href="#"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRequestModal"
                                                    data-id="{{ $request->id }}"
                                                    data-request_type="{{ $request->request_type }}"
                                                    data-name="{{ $request->name }}"
                                                    data-phone="{{ $request->phone }}"
                                                    data-email="{{ $request->email }}"
                                                    data-agree="{{ $request->agree }}"
                                                    data-status="{{ $request->status }}">
                                                    <i class="bi bi-pencil-square"></i>Редактировать
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider-custom"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.requests.general.destroy', $request->id) }}" class="d-inline delete-form">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item dropdown-item-custom text-danger" onclick="return confirm('Вы уверены?')">
                                                        <i class="bi bi-trash"></i>Удалить
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Нет заявок</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- МОДАЛКА ДОБАВЛЕНИЯ ЗАЯВКИ -->
        <div class="modal fade" id="addRequestModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить заявку</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                    </div>
                    <div class="modal-body">
                        <div class="request-type-tabs">
                            <button type="button" class="request-type-tab active" data-type="trade_in">Trade-in</button>
                            <button type="button" class="request-type-tab" data-type="credit">Автокредит</button>
                            <button type="button" class="request-type-tab" data-type="insurance">Автострахование</button>
                            <button type="button" class="request-type-tab" data-type="request">Заявка</button>
                        </div>

                        <!-- Форма Trade-in -->
                        <form method="POST" action="{{ route('admin.requests.trade-in.store') }}" id="tradeInForm" class="form-section active needs-validation" data-ajax="true">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tradein_name" class="form-label">Имя *</label>
                                        <input type="text" class="form-control" id="tradein_name" name="name" value="{{ old('name', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tradein_phone" class="form-label">Номер телефона *</label>
                                        <input type="tel" class="form-control" id="tradein_phone" name="phone" value="{{ old('phone', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Адрес дилерского центра *</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="dealer_center_1" name="dealer_center" value="г. Казань, ул. Ямашева, д. 76" class="dealer-checkbox" {{ old('dealer_center') == 'г. Казань, ул. Ямашева, д. 76' ? 'checked' : '' }}>
                                        <label for="dealer_center_1">г. Казань, ул. Ямашева, д. 76</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="dealer_center_2" name="dealer_center" value="г. Казань, ул. Чистопольская, д. 9а" class="dealer-checkbox" {{ old('dealer_center') == 'г. Казань, ул. Чистопольская, д. 9а' ? 'checked' : '' }}>
                                        <label for="dealer_center_2">г. Казань, ул. Чистопольская, д. 9а</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                                <small class="text-muted">Выберите один из вариантов</small>
                            </div>
                            <div class="modal-footer justify-content-center px-0">
                                <button type="submit" class="btn btn-primary w-100">Добавить заявку Trade-in</button>
                            </div>
                        </form>

                        <!-- Форма Автокредит -->
                        <form method="POST" action="{{ route('admin.requests.credit.store') }}" id="creditForm" class="form-section needs-validation" data-ajax="true">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="credit_fio" class="form-label">ФИО *</label>
                                        <input type="text" class="form-control" id="credit_fio" name="fio" value="{{ old('fio', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="credit_phone" class="form-label">Телефон *</label>
                                        <input type="tel" class="form-control" id="credit_phone" name="phone" value="{{ old('phone', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Тип автомобиля *</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="car_type" id="credit_new" value="new" {{ old('car_type', 'new') == 'new' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="credit_new">Новый</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="car_type" id="credit_used" value="used" {{ old('car_type') == 'used' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="credit_used">С пробегом</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="credit_amount" class="form-label">Сумма кредита *</label>
                                        <input type="text" class="form-control" id="credit_amount" name="credit_amount" value="{{ old('credit_amount', '') }}" required oninput="onlyDigits(this); calculateCreditPayment();">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="interest_rate" class="form-label">Процентная ставка *</label>
                                        <input type="number" step="0.1" class="form-control" id="interest_rate" name="interest_rate" value="{{ old('interest_rate', '') }}" required min="0" max="100" oninput="calculateCreditPayment()">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="loan_term" class="form-label">Срок (лет) *</label>
                                        <input type="number" class="form-control" id="loan_term" name="loan_term" value="{{ old('loan_term', '') }}" required min="1" max="30" oninput="calculateCreditPayment()">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="monthly_payment_credit" class="form-label">Ежемесячный платёж *</label>
                                        <input type="text" class="form-control" id="monthly_payment_credit" name="monthly_payment" value="{{ old('monthly_payment', '') }}" required readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Дополнительные опции</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insurance_kasko_add" name="insurance_kasko" value="1" {{ old('insurance_kasko') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="insurance_kasko_add">Каско</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="insurance_as_z_add" name="insurance_as_z" value="1" {{ old('insurance_as_z') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="insurance_as_z_add">АС/З</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="early_repayment_add" name="early_repayment" value="1" {{ old('early_repayment') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="early_repayment_add">Досрочное погашение</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes_add" class="form-label">Заметки</label>
                                <textarea class="form-control" id="notes_add" name="notes" rows="3">{{ old('notes', '') }}</textarea>
                            </div>

                            <div class="modal-footer justify-content-center px-0">
                                <button type="submit" class="btn btn-primary w-100">Добавить заявку на автокредит</button>
                            </div>
                        </form>

                        <!-- Форма Автострахование -->
                        <form method="POST" action="{{ route('admin.requests.insurance.store') }}" id="insuranceForm" class="form-section needs-validation" data-ajax="true">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="insurance_fio" class="form-label">ФИО *</label>
                                        <input type="text" class="form-control" id="insurance_fio" name="fio" value="{{ old('fio', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="insurance_phone" class="form-label">Телефон *</label>
                                        <input type="tel" class="form-control" id="insurance_phone" name="phone" value="{{ old('phone', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Тип страхования *</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance_type" id="insurance_osago" value="osago" {{ old('insurance_type', 'osago') == 'osago' ? 'checked' : '' }} onchange="calculateInsurancePremium()">
                                        <label class="form-check-label" for="insurance_osago">ОСАГО</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance_type" id="insurance_kasko" value="kasko" {{ old('insurance_type') == 'kasko' ? 'checked' : '' }} onchange="calculateInsurancePremium()">
                                        <label class="form-check-label" for="insurance_kasko">КАСКО</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="car_price_add" class="form-label">Стоимость автомобиля *</label>
                                        <input type="text" class="form-control" id="car_price_add" name="car_price" value="{{ old('car_price', '') }}" required oninput="onlyDigits(this); calculateInsurancePremium();">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="car_age_add" class="form-label">Возраст автомобиля *</label>
                                        <select class="form-select" id="car_age_add" name="car_age" required onchange="calculateInsurancePremium()">
                                            <option value="1-3 года" {{ old('car_age') == '1-3 года' ? 'selected' : '' }}>1-3 года</option>
                                            <option value="3-5 лет" {{ old('car_age') == '3-5 лет' ? 'selected' : '' }}>3-5 лет</option>
                                            <option value="5-9 лет" {{ old('car_age') == '5-9 лет' ? 'selected' : '' }}>5-9 лет</option>
                                            <option value="10+ лет" {{ old('car_age') == '10+ лет' ? 'selected' : '' }}>10+ лет</option>
                                        </select>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estimated_premium_add" class="form-label">Годовая стоимость страхования *</label>
                                        <input type="text" class="form-control" id="estimated_premium_add" name="estimated_premium" value="{{ old('estimated_premium', '') }}" required readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="monthly_payment_insurance_add" class="form-label">Платёж в месяц *</label>
                                        <input type="text" class="form-control" id="monthly_payment_insurance_add" name="monthly_payment" value="{{ old('monthly_payment', '') }}" required readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="risk_level_add" class="form-label">Уровень риска *</label>
                                <select class="form-select" id="risk_level_add" name="risk_level" required onchange="calculateInsurancePremium()">
                                    <option value="низкий" {{ old('risk_level') == 'низкий' ? 'selected' : '' }}>низкий</option>
                                    <option value="средний" {{ old('risk_level') == 'средний' ? 'selected' : '' }}>средний</option>
                                    <option value="высокий" {{ old('risk_level') == 'высокий' ? 'selected' : '' }}>высокий</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="modal-footer justify-content-center px-0">
                                <button type="submit" class="btn btn-primary w-100">Добавить заявку на автострахование</button>
                            </div>
                        </form>

                        <!-- Форма Заявки (общая) -->
                        <form method="POST" action="{{ route('admin.requests.general.store') }}" id="requestForm" class="form-section needs-validation" data-ajax="true">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Тип заявки *</label>
                                <div class="checkbox-group">
                                    @php
                                        $oldTypes = old('request_type', []);
                                    @endphp
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="test_drive" value="test-drive" {{ in_array('test-drive', $oldTypes) ? 'checked' : '' }}>
                                        <label for="test_drive">Тест-драйв</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="consultation" value="consultation" {{ in_array('consultation', $oldTypes) ? 'checked' : '' }}>
                                        <label for="consultation">Консультация</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="car_selection" value="car-selection" {{ in_array('car-selection', $oldTypes) ? 'checked' : '' }}>
                                        <label for="car_selection">Подбор авто</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="service" value="service" {{ in_array('service', $oldTypes) ? 'checked' : '' }}>
                                        <label for="service">Запись на сервис</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="request_name" class="form-label">Имя *</label>
                                        <input type="text" class="form-control" id="request_name" name="name" value="{{ old('name', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="request_phone" class="form-label">Телефон *</label>
                                        <input type="tel" class="form-control" id="request_phone" name="phone" value="{{ old('phone', '') }}" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="request_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="request_email" name="email" value="{{ old('email', '') }}">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="request_agree" name="agree" value="1" {{ old('agree', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="request_agree">Я согласен с политикой конфиденциальности</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center px-0">
                                <button type="submit" class="btn btn-primary w-100">Добавить заявку</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА РЕДАКТИРОВАНИЯ TRADE-IN -->
        <div class="modal fade" id="editTradeInModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать заявку Trade-in</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="" id="editTradeInForm" data-ajax="true">
                        @csrf
                        <input type="hidden" name="id" id="edit_tradein_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_tradein_name" class="form-label">Имя</label>
                                <input type="text" class="form-control" id="edit_tradein_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_tradein_phone" class="form-label">Номер телефона</label>
                                <input type="tel" class="form-control" id="edit_tradein_phone" name="phone" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Адрес дилерского центра *</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="edit_dealer_center_1" name="dealer_center" value="г. Казань, ул. Ямашева, д. 76" class="dealer-checkbox">
                                        <label for="edit_dealer_center_1">г. Казань, ул. Ямашева, д. 76</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="edit_dealer_center_2" name="dealer_center" value="г. Казань, ул. Чистопольская, д. 9а" class="dealer-checkbox">
                                        <label for="edit_dealer_center_2">г. Казань, ул. Чистопольская, д. 9а</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА РЕДАКТИРОВАНИЯ CREDIT -->
        <div class="modal fade" id="editCreditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать заявку на автокредит</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="" id="editCreditForm" data-ajax="true">
                        @csrf
                        
                        <input type="hidden" name="id" id="edit_credit_id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_credit_fio" class="form-label">ФИО *</label>
                                        <input type="text" class="form-control" id="edit_credit_fio" name="fio" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_credit_phone" class="form-label">Телефон *</label>
                                        <input type="tel" class="form-control" id="edit_credit_phone" name="phone" required>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Тип автомобиля *</label>
                                        <div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="car_type" id="edit_credit_new" value="new">
                                                <label class="form-check-label" for="edit_credit_new">Новый</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="car_type" id="edit_credit_used" value="used">
                                                <label class="form-check-label" for="edit_credit_used">С пробегом</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_credit_amount" class="form-label">Сумма кредита *</label>
                                        <input type="text" class="form-control" id="edit_credit_amount" name="credit_amount" required oninput="onlyDigits(this); calculateEditCreditPayment();">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_interest_rate" class="form-label">Процентная ставка *</label>
                                        <input type="number" step="0.1" class="form-control" id="edit_interest_rate" name="interest_rate" required min="0" max="100" oninput="calculateEditCreditPayment()">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_loan_term" class="form-label">Срок (лет) *</label>
                                        <input type="number" class="form-control" id="edit_loan_term" name="loan_term" required min="1" max="30" oninput="calculateEditCreditPayment()">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="edit_monthly_payment" class="form-label">Ежемесячный платёж *</label>
                                        <input type="text" class="form-control" id="edit_monthly_payment" name="monthly_payment" required readonly>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Дополнительные опции</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_insurance_kasko" name="insurance_kasko" value="1">
                                    <label class="form-check-label" for="edit_insurance_kasko">Каско</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_insurance_as_z" name="insurance_as_z" value="1">
                                    <label class="form-check-label" for="edit_insurance_as_z">АС/З</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_early_repayment" name="early_repayment" value="1">
                                    <label class="form-check-label" for="edit_early_repayment">Досрочное погашение</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_notes" class="form-label">Заметки</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА РЕДАКТИРОВАНИЯ INSURANCE -->
        <div class="modal fade" id="editInsuranceModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать заявку на автострахование</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="" id="editInsuranceForm" data-ajax="true">
                        @csrf
                        <input type="hidden" name="id" id="edit_insurance_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_insurance_fio" class="form-label">ФИО *</label>
                                <input type="text" class="form-control" id="edit_insurance_fio" name="fio" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_insurance_phone" class="form-label">Телефон *</label>
                                <input type="tel" class="form-control" id="edit_insurance_phone" name="phone" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Тип страхования *</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance_type" id="edit_insurance_osago" value="osago" onchange="calculateEditInsurancePremium()">
                                        <label class="form-check-label" for="edit_insurance_osago">ОСАГО</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="insurance_type" id="edit_insurance_kasko" value="kasko" onchange="calculateEditInsurancePremium()">
                                        <label class="form-check-label" for="edit_insurance_kasko">КАСКО</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_car_price" class="form-label">Стоимость автомобиля *</label>
                                <input type="text" class="form-control" id="edit_car_price" name="car_price" required oninput="onlyDigits(this); calculateEditInsurancePremium();">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_car_age" class="form-label">Возраст автомобиля *</label>
                                <select class="form-select" id="edit_car_age" name="car_age" required onchange="calculateEditInsurancePremium()">
                                    <option value="1-3 года">1-3 года</option>
                                    <option value="3-5 лет">3-5 лет</option>
                                    <option value="5-9 лет">5-9 лет</option>
                                    <option value="10+ лет">10+ лет</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_estimated_premium" class="form-label">Годовая стоимость страхования *</label>
                                <input type="text" class="form-control" id="edit_estimated_premium" name="estimated_premium" required readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_monthly_payment_insurance" class="form-label">Платёж в месяц *</label>
                                <input type="text" class="form-control" id="edit_monthly_payment_insurance" name="monthly_payment" required readonly>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_risk_level" class="form-label">Уровень риска *</label>
                                <select class="form-select" id="edit_risk_level" name="risk_level" required onchange="calculateEditInsurancePremium()">
                                    <option value="низкий">низкий</option>
                                    <option value="средний">средний</option>
                                    <option value="высокий">высокий</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- МОДАЛКА РЕДАКТИРОВАНИЯ ОБЩЕЙ ЗАЯВКИ -->
        <div class="modal fade" id="editRequestModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать заявку</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="" id="editRequestForm" data-ajax="true">
                        @csrf
                        <input type="hidden" name="id" id="edit_request_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Тип заявки *</label>
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="edit_test_drive" value="test-drive">
                                        <label for="edit_test_drive">Тест-драйв</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="edit_consultation" value="consultation">
                                        <label for="edit_consultation">Консультация</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="edit_car_selection" value="car-selection">
                                        <label for="edit_car_selection">Подбор авто</label>
                                    </div>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="request_type[]" id="edit_service" value="service">
                                        <label for="edit_service">Запись на сервис</label>
                                    </div>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_request_name" class="form-label">Имя *</label>
                                <input type="text" class="form-control" id="edit_request_name" name="name" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_request_phone" class="form-label">Телефон *</label>
                                <input type="tel" class="form-control" id="edit_request_phone" name="phone" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_request_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_request_email" name="email">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="edit_request_agree" name="agree" value="1">
                                    <label class="form-check-label" for="edit_request_agree">Согласие с политикой</label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_request_status" class="form-label">Статус *</label>
                                <select class="form-select" id="edit_request_status" name="status" required>
                                    <option value="new">Новая</option>
                                    <option value="processed">В обработке</option>
                                    <option value="completed">Завершена</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" class="btn btn-primary w-100">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let formDataCache = {};

        document.querySelectorAll('.request-type-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                const activeForm = document.querySelector('#addRequestModal .form-section.active');
                if (activeForm) {
                    saveFormData(activeForm.id);
                }

                document.querySelectorAll('.request-type-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('#addRequestModal .form-section').forEach(f => f.classList.remove('active'));

                this.classList.add('active');
                const type = this.dataset.type;
                let formId;
                if (type === 'trade_in') formId = 'tradeInForm';
                else if (type === 'credit') formId = 'creditForm';
                else if (type === 'insurance') formId = 'insuranceForm';
                else if (type === 'request') formId = 'requestForm';

                const form = document.getElementById(formId);
                if (form) {
                    form.classList.add('active');
                    restoreFormData(formId);
                }
            });
        });

        function saveFormData(formId) {
            const form = document.getElementById(formId);
            if (!form) return;
            const formData = new FormData(form);
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key.endsWith('[]')) {
                    if (!data[key]) data[key] = [];
                    data[key].push(value);
                } else {
                    data[key] = value;
                }
            }
            formDataCache[formId] = data;
        }

        function restoreFormData(formId) {
            const form = document.getElementById(formId);
            if (!form || !formDataCache[formId]) return;

            const data = formDataCache[formId];
            for (const [key, value] of Object.entries(data)) {
                const elements = form.querySelectorAll(`[name="${key}"]`);
                if (elements.length === 0) continue;

                if (key.endsWith('[]')) {
                    elements.forEach(el => {
                        if (Array.isArray(value) && value.includes(el.value)) {
                            el.checked = true;
                        } else {
                            el.checked = false;
                        }
                    });
                } else if (elements[0].type === 'checkbox') {
                    elements[0].checked = value === '1' || value === 1 || value === true;
                } else if (elements[0].type === 'radio') {
                    elements.forEach(el => {
                        el.checked = el.value === value;
                    });
                } else {
                    elements[0].value = value;
                }
            }

            if (formId === 'creditForm') {
                calculateCreditPayment();
            } else if (formId === 'insuranceForm') {
                calculateInsurancePremium();
            }
        }

        function onlyDigits(input) {
            input.value = input.value.replace(/\D/g, '');
        }

        function calculateCreditPayment() {
            const amount = parseFloat(document.getElementById('credit_amount')?.value) || 0;
            const rate = parseFloat(document.getElementById('interest_rate')?.value) || 0;
            const term = parseInt(document.getElementById('loan_term')?.value) || 0;

            if (amount > 0 && rate > 0 && term > 0) {
                const monthlyRate = rate / 100 / 12;
                const numberOfPayments = term * 12;
                const monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
                document.getElementById('monthly_payment_credit').value = Math.round(monthlyPayment);
            } else {
                document.getElementById('monthly_payment_credit').value = '';
            }
        }

        function calculateInsurancePremium() {
            const carPrice = parseFloat(document.getElementById('car_price_add')?.value) || 0;
            const insuranceType = document.querySelector('#insuranceForm input[name="insurance_type"]:checked')?.value || 'osago';
            const carAge = document.getElementById('car_age_add')?.value;
            const riskLevel = document.getElementById('risk_level_add')?.value;

            if (carPrice > 0 && carAge && riskLevel) {
                let baseRate = insuranceType === 'osago' ? 0.05 : 0.07;

                const ageModifiers = {
                    '1-3 года': 1.0,
                    '3-5 лет': 1.2,
                    '5-9 лет': 1.5,
                    '10+ лет': 2.0
                };
                const riskModifiers = {
                    'низкий': 0.8,
                    'средний': 1.0,
                    'высокий': 1.3
                };

                const ageModifier = ageModifiers[carAge] || 1.0;
                const riskModifier = riskModifiers[riskLevel] || 1.0;

                const annualPremium = carPrice * baseRate * ageModifier * riskModifier;
                const monthlyPayment = annualPremium / 12;

                document.getElementById('estimated_premium_add').value = Math.round(annualPremium);
                document.getElementById('monthly_payment_insurance_add').value = Math.round(monthlyPayment);
            } else {
                document.getElementById('estimated_premium_add').value = '';
                document.getElementById('monthly_payment_insurance_add').value = '';
            }
        }

        // Расчет для редактирования кредита
        function calculateEditCreditPayment() {
            const amount = parseFloat(document.getElementById('edit_credit_amount')?.value) || 0;
            const rate = parseFloat(document.getElementById('edit_interest_rate')?.value) || 0;
            const term = parseInt(document.getElementById('edit_loan_term')?.value) || 0;

            if (amount > 0 && rate > 0 && term > 0) {
                const monthlyRate = rate / 100 / 12;
                const numberOfPayments = term * 12;
                const monthlyPayment = amount * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments) / (Math.pow(1 + monthlyRate, numberOfPayments) - 1);
                document.getElementById('edit_monthly_payment').value = Math.round(monthlyPayment);
            } else {
                document.getElementById('edit_monthly_payment').value = '';
            }
        }

        // Расчет для редактирования страховки
        function calculateEditInsurancePremium() {
            const carPrice = parseFloat(document.getElementById('edit_car_price')?.value) || 0;
            const insuranceType = document.querySelector('#editInsuranceModal input[name="insurance_type"]:checked')?.value || 'osago';
            const carAge = document.getElementById('edit_car_age')?.value;
            const riskLevel = document.getElementById('edit_risk_level')?.value;

            if (carPrice > 0 && carAge && riskLevel) {
                let baseRate = insuranceType === 'osago' ? 0.05 : 0.07;
                const ageModifiers = {
                    '1-3 года': 1.0,
                    '3-5 лет': 1.2,
                    '5-9 лет': 1.5,
                    '10+ лет': 2.0
                };
                const riskModifiers = {
                    'низкий': 0.8,
                    'средний': 1.0,
                    'высокий': 1.3
                };
                const ageModifier = ageModifiers[carAge] || 1.0;
                const riskModifier = riskModifiers[riskLevel] || 1.0;
                const annualPremium = carPrice * baseRate * ageModifier * riskModifier;
                const monthlyPayment = annualPremium / 12;
                document.getElementById('edit_estimated_premium').value = Math.round(annualPremium);
                document.getElementById('edit_monthly_payment_insurance').value = Math.round(monthlyPayment);
            } else {
                document.getElementById('edit_estimated_premium').value = '';
                document.getElementById('edit_monthly_payment_insurance').value = '';
            }
        }

        // Маска телефона
        function phoneMask(event) {
            let input = event.target;
            let x = input.value.replace(/\D/g, '').match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
            input.value = !x[2] ? x[1] : "+7 (" + x[2] + (x[3] ? ") " + x[3] : "") + (x[4] ? "-" + x[4] : "") + (x[5] ? "-" + x[5] : "");
        }

        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', phoneMask);
        });

        // Заполнение модалок редактирования данными
        document.querySelectorAll('.edit-tradein-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const phone = this.dataset.phone;
                const dealer = this.dataset.dealer;

                document.getElementById('edit_tradein_id').value = id;
                document.getElementById('edit_tradein_name').value = name;
                document.getElementById('edit_tradein_phone').value = phone;

                document.querySelectorAll('#editTradeInModal .dealer-checkbox').forEach(cb => cb.checked = false);
                if (dealer === "г. Казань, ул. Ямашева, д. 76") {
                    document.getElementById('edit_dealer_center_1').checked = true;
                } else if (dealer === "г. Казань, ул. Чистопольская, д. 9а") {
                    document.getElementById('edit_dealer_center_2').checked = true;
                }

                document.getElementById('editTradeInForm').action = '{{ route('admin.requests.trade-in.update', '') }}/' + id;
            });
        });

        document.querySelectorAll('.edit-credit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('edit_credit_id').value = id;
                document.getElementById('edit_credit_fio').value = this.dataset.fio;
                document.getElementById('edit_credit_phone').value = this.dataset.phone;
                document.getElementById('edit_credit_amount').value = this.dataset.credit_amount; 
                document.getElementById('edit_interest_rate').value = this.dataset.interest_rate;
                document.getElementById('edit_loan_term').value = this.dataset.loan_term;
                document.getElementById('edit_monthly_payment').value = this.dataset.monthly_payment; 
                document.getElementById('edit_notes').value = this.dataset.notes || '';

                document.querySelectorAll('#editCreditModal input[name="car_type"]').forEach(radio => {
                    radio.checked = radio.value === this.dataset.car_type;
                });

                document.getElementById('edit_insurance_kasko').checked = this.dataset.insurance_kasko === '1';
                document.getElementById('edit_insurance_as_z').checked = this.dataset.insurance_as_z === '1';
                document.getElementById('edit_early_repayment').checked = this.dataset.early_repayment === '1';

                document.getElementById('editCreditForm').action = '{{ route('admin.requests.credit.update', '') }}/' + id;

                setTimeout(calculateEditCreditPayment, 100);
            });
        });

        document.querySelectorAll('.edit-insurance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('edit_insurance_id').value = id;
                document.getElementById('edit_insurance_fio').value = this.dataset.fio;
                document.getElementById('edit_insurance_phone').value = this.dataset.phone;
                document.getElementById('edit_car_price').value = this.dataset.car_price; 
                document.getElementById('edit_car_age').value = this.dataset.car_age;
                document.getElementById('edit_estimated_premium').value = this.dataset.estimated_premium; 
                document.getElementById('edit_monthly_payment_insurance').value = this.dataset.monthly_payment; 
                document.getElementById('edit_risk_level').value = this.dataset.risk_level;

                document.querySelectorAll('#editInsuranceModal input[name="insurance_type"]').forEach(radio => {
                    radio.checked = radio.value === this.dataset.insurance_type;
                });

                document.getElementById('editInsuranceForm').action = '{{ route('admin.requests.insurance.update', '') }}/' + id;

                setTimeout(calculateEditInsurancePremium, 100);
            });
        });

        document.querySelectorAll('.edit-request-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('edit_request_id').value = id;
                document.getElementById('edit_request_name').value = this.dataset.name;
                document.getElementById('edit_request_phone').value = this.dataset.phone;
                document.getElementById('edit_request_email').value = this.dataset.email;
                document.getElementById('edit_request_agree').checked = this.dataset.agree === '1';
                document.getElementById('edit_request_status').value = this.dataset.status;

                document.querySelectorAll('#editRequestModal input[name="request_type[]"]').forEach(cb => cb.checked = false);
                const types = this.dataset.request_type.split(',');
                types.forEach(type => {
                    const checkbox = document.querySelector(`#editRequestModal input[value="${type}"]`);
                    if (checkbox) checkbox.checked = true;
                });

                document.getElementById('editRequestForm').action = '{{ route('admin.requests.general.update', '') }}/' + id;
            });
        });

        // ========== AJAX ОБРАБОТКА ФОРМ ==========
        const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');

        ajaxForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                const formData = new FormData(form);
                const action = form.action;
                const method = form.method;

                fetch(action, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, data })))
                .then(({ status, data }) => {
                    if (status === 422) {
                        displayValidationErrors(form, data.errors);
                    } else if (status === 200) {
                        const modal = bootstrap.Modal.getInstance(form.closest('.modal'));
                        if (modal) modal.hide();

                        alert(data.message || 'Заявка сохранена');

                        location.reload();
                    } else {
                        alert('Произошла ошибка: ' + (data.error || 'Неизвестная ошибка'));
                    }
                })
                .catch(error => {
                    console.error('AJAX error:', error);
                    alert('Ошибка соединения с сервером');
                });
            });
        });

        function displayValidationErrors(form, errors) {
            for (let field in errors) {
                let input = form.querySelector(`[name="${field}"]`);
                if (!input) {
                    input = form.querySelector(`[name="${field}[]"]`);
                }
                if (input) {
                    input.classList.add('is-invalid');
                    let feedback = input.parentNode.querySelector('.invalid-feedback');
                    if (!feedback) {
                        feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        input.parentNode.appendChild(feedback);
                    }
                    feedback.textContent = errors[field][0];
                } else {
                    console.warn('Поле не найдено для ошибки:', field, errors[field]);
                }
            }
        }

        setTimeout(() => {
            if (document.getElementById('creditForm')?.classList.contains('active')) {
                calculateCreditPayment();
            }
            if (document.getElementById('insuranceForm')?.classList.contains('active')) {
                calculateInsurancePremium();
            }
        }, 500);
    </script>
@endpush