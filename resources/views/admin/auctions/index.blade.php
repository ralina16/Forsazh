@extends('layouts.admin')

@section('title', 'Админ-панель — Аукционы')

@push('styles')
    <style>
        :root {
            --primary-color: #2f6dd5;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        body {
            overflow-x: hidden;
        }

        .table th {
            background-color: #f1f5fd;
            color: var(--primary-color);
            font-weight: 600;
            border-bottom: 2px solid #e1e8f7;
        }

        .car-table-img {
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }

        .row-more-btn {
            background: none;
            border: none;
            color: var(--secondary-color);
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .row-more-btn:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .modal-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .modal-btn:hover {
            background-color: #1a56b0;
            color: white;
        }

        .stats-card {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.8);
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-4px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stats-icon-primary {
            background: linear-gradient(135deg, var(--primary-color), #1a56b0);
            color: white;
        }

        .stats-icon-success {
            background: linear-gradient(135deg, var(--success-color), #146c43);
            color: white;
        }

        .stats-icon-warning {
            background: linear-gradient(135deg, var(--warning-color), #e0a800);
            color: white;
        }

        .stats-icon-danger {
            background: linear-gradient(135deg, var(--danger-color), #b02a37);
            color: white;
        }

        .stats-icon-info {
            background: linear-gradient(135deg, #0dcaf0, #0aa2c0);
            color: white;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 8px;
            background: linear-gradient(135deg, var(--dark-color), #495057);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-label {
            color: var(--secondary-color);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .auction-card {
            background: #fff;
            border-radius: 8px;
            padding: 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.04);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.8);
            overflow: hidden;
            position: relative;
            height: 100%;
        }

        .auction-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.04);
        }

        .auction-card-header {
            padding: 24px 24px 16px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
            min-width: 0;
        }

        .auction-card-footer {
            padding: 16px 24px 24px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .auction-title {
            font-weight: 700;
            margin-bottom: 8px;
            font-size: 1.3rem;
            color: var(--dark-color);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
            word-break: break-all;
            width: 270px;
        }

        .auction-meta {
            color: var(--secondary-color);
            font-size: 0.85rem;
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .auction-meta i {
            font-size: 0.8rem;
        }

        .auction-status {
            display: inline-flex;
            align-items: center;
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12.5px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(104, 211, 145, 0.2);
            color: #38a169;
            border: 1px solid rgba(104, 211, 145, 0.3);
        }

        .status-upcoming {
            background: rgba(250, 240, 137, 0.3);
            color: #d69e2e;
            border: 1px solid rgba(250, 240, 137, 0.4);
        }

        .status-ended {
            background: rgba(160, 174, 192, 0.2);
            color: #718096;
            border: 1px solid rgba(160, 174, 192, 0.3);
        }

        .bid-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .bid-amount {
            font-weight: 800;
            color: var(--primary-color);
            font-size: 1.4rem;
        }

        .bid-count {
            color: var(--secondary-color);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(47, 109, 213, 0.1);
            padding: 6px 12px;
            border-radius: 8px;
        }

        .progress-container {
            margin: 20px 0;
        }

        .progress {
            height: 6px !important;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 8px;
            transition: width 1s ease-in-out;
        }

        .time-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: var(--secondary-color);
            margin-top: 8px;
        }

        .auction-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .form-control {
            padding: .6rem .75rem !important;
        }

        .action-btn {
            flex: 1;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            text-decoration: none;
        }

        .action-btn-primary {
            background: #4071CB;
            color: white;
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .action-btn-primary:hover {
            color: white;
        }

        .action-btn-outline {
            background: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .current-photo-preview {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-select {
            border-radius: 8px !important;
            border: 2px solid #e9ecef;
            transition: all 0.3s;
            background-color: white;
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(47, 109, 213, 0.25);
        }

        .form-select:hover {
            border-color: #adb5bd;
        }

        body.modal-open {
            overflow: auto !important;
            padding-right: 0 !important;
        }

        .modal {
            overflow-y: auto;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
        }

        .modal-backdrop.show {
            opacity: 0.5 !important;
        }

        .photo-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 12px;
        }

        .photo-gallery-item {
            width: 120px;
            height: 90px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .photo-gallery-item:hover {
            transform: scale(1.05);
        }

        .td-description {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .bid-amount-cell {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .bid-user-info {
            display: flex;
            flex-direction: column;
        }

        .bid-user-name {
            font-weight: 600;
            color: var(--dark-color);
        }

        .bid-user-contact {
            font-size: 0.85rem;
            color: var(--secondary-color);
        }

        .bid-time {
            font-size: 0.85rem;
            color: var(--secondary-color);
        }

        .winner-badge {
            background: linear-gradient(135deg, var(--success-color), #146c43);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .bids-section {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            background: var(--primary-color);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 20px rgba(47, 109, 213, 0.3);
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        .back-to-top:hover {
            background: #1a56b0;
            color: white;
            transform: translateY(-3px);
        }

        .is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated .form-control:invalid,
        .was-validated .form-select:invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .was-validated .form-control:valid,
        .was-validated .form-select:valid {
            border-color: #198754;
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        @media (max-width: 768px) {
            .mobile-cards {
                display: block;
            }

            .car-table {
                display: none;
            }

            .mobile-card {
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
                border-radius: 8px;
                padding: 24px;
                margin-bottom: 20px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.8);
            }

            .mobile-card .title {
                font-weight: 700;
                margin-bottom: 8px;
                font-size: 1.2rem;
            }

            .mobile-card .desc {
                color: var(--secondary-color);
                font-size: 0.9rem;
                margin-bottom: 16px;
            }

            .mobile-card .meta {
                margin-bottom: 16px;
            }

            .mobile-card .meta div {
                margin-bottom: 6px;
                font-size: 0.9rem;
            }

            .mobile-card .price {
                font-weight: 800;
                color: var(--primary-color);
                margin-bottom: 16px;
                font-size: 1.3rem;
            }

            .mobile-card .actions {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .modal-title {
                word-wrap: break-word;
                word-break: break-word;
                white-space: normal;
                line-height: 1.3;
            }

            .action-btn {
                min-width: 100px;
                padding: 8px 12px;
                font-size: 0.8rem;
            }

            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
            }
        }

        @media (min-width: 769px) {
            .mobile-cards {
                display: none;
            }
        }

        .photo-upload-section {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }

        .photos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .photo-card {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            background: white;
        }

        .photo-card:hover {
            transform: translateY(-8px);
        }

        .photo-card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .photo-card-actions {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            gap: 8px;
        }

        .section-title {
            font-weight: 800;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 1.8rem;
        }

        #carDetailsContent p,
#carDetailsContent .mt-3 p {
    word-wrap: break-word;
    overflow-wrap: break-word;
    word-break: break-word;
    white-space: normal;
}

        .section-subtitle {
            color: var(--secondary-color);
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .modal {
            padding-left: 0 !important;
        }

        .modal-open {
            padding-right: 0 !important;
        }
    </style>
@endpush

@section('content')

    <a href="#" class="back-to-top" id="backToTop">
        <i class="bi bi-chevron-up"></i>
    </a>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="flex-grow-1">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <section class="container-xxl section">
        @if (request()->has('manage_photos') && isset($managePhotosCar))
            <div class="d-none d-xl-block mt-2 mb-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.auctions.index') }}">Аукционы</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Управление фото</li>
                    </ol>
                </nav>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Управление фото</h4>
                    <p class="text-muted mb-0">{{ $managePhotosCar->model }} (ID: {{ $managePhotosCar->id }})</p>
                </div>
                <a href="{{ route('admin.auctions.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left me-1"></i> Назад к списку
                </a>
            </div>

            <div class="photo-upload-section">
                <h5 class="mb-3">Добавить дополнительные фото</h5>
                <form method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.auctions.cars.photos.upload', $managePhotosCar->id) }}" id="uploadPhotosForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" class="form-control" name="photos[]" multiple accept="image/*" required>
                            <div class="form-text">Можно выбрать несколько фото (максимум 10 файлов, каждый до 10MB).</div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-upload me-1"></i> Загрузить фото
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="photos-grid">
                @forelse($carPhotos as $index => $photo)
                    <div class="photo-card">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Фото автомобиля"
                            onerror="this.src='{{ asset('assets/images/one_car/image-1.jpg') }}'">
                        <div class="photo-card-actions">
                            <form method="POST"
                                action="{{ route('admin.auctions.cars.photos.setMain', [$managePhotosCar->id, $index]) }}"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary" title="Сделать основным">
                                    <i class="bi bi-star"></i>
                                </button>
                            </form>
                            <form method="POST"
                                action="{{ route('admin.auctions.cars.photos.delete', [$managePhotosCar->id, $index]) }}"
                                class="d-inline" onsubmit="return confirm('Удалить это фото?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-images display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Нет дополнительных фото</h5>
                        <p class="text-muted">Начните с загрузки первого дополнительного фото</p>
                    </div>
                @endforelse
            </div>
        @elseif(request()->has('view_bids') && isset($auctionBids))
            <div class="d-none d-xl-block mt-2 mb-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.auctions.index') }}">Аукционы</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ставки</li>
                    </ol>
                </nav>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">Ставки на аукцион</h4>
                    <p class="text-muted mb-0">{{ $auctionBids->car->model ?? '' }} (ID: {{ $auctionBids->id }})</p>
                </div>
                <a href="{{ route('admin.auctions.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-1"></i> Назад к списку
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-primary">
                            <i class="bi bi-gem"></i>
                        </div>
                        <div class="stats-value">{{ count($bids) }}</div>
                        <div class="stats-label">Всего ставок</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-warning">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="stats-value">
                            @if ($auctionBids->status == 'active')
                                Активен
                            @elseif($auctionBids->status == 'ended')
                                Завершен
                            @else
                                Скоро
                            @endif
                        </div>
                        <div class="stats-label">Статус аукциона</div>
                    </div>
                </div>
            </div>

            <div class="bids-section">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">История ставок</h5>
                    @if ($auctionBids->status == 'active' && count($bids) > 0)
                        <form method="POST" action="{{ route('admin.auctions.determineWinner', $auctionBids->id) }}"
                            class="determine-winner-form">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-trophy me-1"></i> Завершить аукцион
                            </button>
                        </form>
                    @endif
                </div>

                @if ($bids->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-clock-history display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Ставок пока нет</h5>
                        <p class="text-muted">Как только пользователи начнут делать ставки, они появятся здесь</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table car-table">
                            <thead>
                                <tr>
                                    <th>Пользователь</th>
                                    <th>Ставка</th>
                                    <th>Время</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody class="text-center align-middle">
                                @foreach ($bids as $bid)
                                    <tr class="mobile-card">
                                        <td>
                                            <div class="bid-user-info">
                                                <span class="bid-user-name">{{ $bid->user->name ?? 'Гость' }}</span>
                                                <span class="bid-user-contact">{{ $bid->user->email ?? '' }}</span>
                                            </div>
                                        </td>
                                        <td class="bid-amount-cell">
                                            {{ number_format($bid->amount, 0, ',', ' ') }} ₽
                                        </td>
                                        <td class="bid-time">
                                            {{ $bid->created_at->format('d.m.Y H:i') }}
                                        </td>
                                        <td>
                                            @if ($bid->is_winner)
                                                <span class="winner-badge">
                                                    <i class="bi bi-trophy-fill me-1"></i> Победитель
                                                </span>
                                            @else
                                                <span class="text-muted">Участник</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @else
            <div class="d-none d-xl-block mt-2 mb-5">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Главная</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Аукционы</li>
                    </ol>
                </nav>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="section-title">Аукционы</h4>
                    <p class="section-subtitle">Управление всеми аукционами системы</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAuctionModal">
                        <i class="bi bi-plus-lg me-2"></i> Создать аукцион
                    </button>
                </div>
            </div>

            <div class="row" id="auctionsContainer">
                @forelse($auctions as $auction)
                    @php
                        $currentStatus = $auction->status;
                        $statusText = $auction->status_text;
                        $statusClass = $auction->status_class;

                        $progress = 0;
                        if ($currentStatus == 'active') {
                            $total = $auction->end_date->timestamp - $auction->start_date->timestamp;
                            $elapsed = now()->timestamp - $auction->start_date->timestamp;
                            $progress = $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
                        } elseif ($currentStatus == 'ended') {
                            $progress = 100;
                        }

                        $currentPrice = $auction->starting_price;
                        $bidCount = $auction->bids()->count();
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="auction-card">
                            <div class="auction-card-header">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <div class="auction-title">{{ $auction->car->model ?? '' }}</div>
                                        <div class="auction-meta">
                                            <i class="bi bi-hash"></i>ID: {{ $auction->id }}
                                        </div>
                                    </div>
                                    <span class="auction-status {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <div class="progress-container">
                                    <div class="progress">
                                        <div class="progress-bar 
                                            @if ($currentStatus == 'active') bg-success
                                            @elseif($currentStatus == 'upcoming') bg-warning
                                            @else bg-secondary @endif"
                                            role="progressbar" style="width: {{ $progress }}%">
                                        </div>
                                    </div>
                                    <div class="time-info">
                                        <span>{{ $auction->start_date->format('d.m.Y H:i') }}</span>
                                        <span>{{ $auction->end_date->format('d.m.Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="auction-card-footer">
                                <div class="bid-info">
                                    <div class="bid-amount">{{ number_format($currentPrice, 0, ',', ' ') }} ₽</div>
                                    <div class="bid-count">
                                        <i class="bi bi-people"></i> {{ $bidCount }}
                                    </div>
                                </div>
                                <div class="auction-actions mt-3">
                                    <a href="{{ route('admin.auctions.index', ['view_bids' => $auction->id]) }}"
                                        class="action-btn action-btn-primary">Ставки</a>
                                    <button class="action-btn action-btn-outline manage-auction-btn"
                                        data-auction-id="{{ $auction->id }}"
                                        data-start-date="{{ $auction->start_date->format('Y-m-d\TH:i') }}"
                                        data-end-date="{{ $auction->end_date->format('Y-m-d\TH:i') }}"
                                        data-starting-price="{{ $auction->starting_price }}"
                                        data-reserve-price="{{ $auction->reserve_price }}"
                                        data-winner-notes="{{ $auction->winner_notes ?? '' }}">
                                        Управление
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-calendar-x display-1 text-muted"></i>
                        <h5 class="mt-3 text-muted">Аукционов пока нет</h5>
                        <p class="text-muted">Создайте первый аукцион, нажав кнопку выше</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
                <div>
                    <h4 class="section-title">Автомобили</h4>
                    <p class="section-subtitle">Все автомобили в системе</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarModal">
                        <i class="bi bi-plus-lg me-2"></i> Добавить автомобиль
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
                                <th>Модель</th>
                                <th>Описание</th>
                                <th>Привод</th>
                                <th>Объем</th>
                                <th>Топливо</th>
                                <th>Пробег</th>
                                <th>Состояние</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody class="text-center align-middle">
                            @foreach ($cars as $car)
                                <tr class="mobile-card" data-id="{{ $car->id }}" data-model="{{ $car->model }}"
                                    data-photo="{{ $car->photo ? asset('storage/' . $car->photo) : '' }}"
                                    data-drive="{{ $car->drive }}" data-engine="{{ $car->engine }}"
                                    data-fuel="{{ $car->fuel }}" data-mileage="{{ $car->mileage }}"
                                    data-condition="{{ $car->condition }}" data-owners="{{ $car->owners }}"
                                    data-transmissions="{{ $car->transmissions }}" data-trunk="{{ $car->trunk }}"
                                    data-gearbox="{{ $car->gearbox }}" data-body="{{ $car->body }}"
                                    data-price="{{ number_format($car->price, 0, ',', ' ') }} ₽"
                                    data-price-raw="{{ $car->price }}" data-description="{{ $car->description }}">
                                    <td data-label="ID">{{ $car->id }}</td>
                                    <td data-label="Фото">
                                        <img src="{{ $car->photo ? asset('storage/' . $car->photo) : asset('assets/images/one_car/image-1.jpg') }}"
                                            alt="Фото" class="car-table-img"
                                            onerror="this.src='{{ asset('assets/images/one_car/image-1.jpg') }}'">
                                    </td>
                                    <td class="td-model" data-label="Модель">{{ $car->model }}</td>
                                    <td class="td-description ps-3" data-label="Описание">
                                        {{ Str::limit($car->description, 50) }}</td>
                                    <td data-label="Привод">{{ $car->drive }}</td>
                                    <td data-label="Объем">{{ $car->engine }}</td>
                                    <td data-label="Топливо">{{ $car->fuel }}</td>
                                    <td data-label="Пробег">{{ $car->mileage }}</td>
                                    <td data-label="Состояние">{{ $car->condition }}</td>
                                    <td>
                                        <button class="row-more-btn view-car-btn" data-car-id="{{ $car->id }}"
                                            aria-label="Показать">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mobile-cards my-4">
                        @foreach ($cars as $car)
                            <div class="mobile-card">
                                <h6 class="title">{{ $car->model }}</h6>
                                <p class="desc">{{ Str::limit($car->description, 100) }}</p>
                                <div class="mb-2">
                                    <img src="{{ $car->photo ? asset('storage/' . $car->photo) : asset('assets/images/auction_car/image-1.jpg') }}"
                                        alt="Car" class="img-fluid rounded"
                                        style="max-height: 150px; width: 100%; object-fit: cover;"
                                        onerror="this.src='{{ asset('assets/images/auction_car/image-1.jpg') }}'">
                                </div>
                                <div class="meta">
                                    <div><strong>Привод:</strong> {{ $car->drive }}</div>
                                    <div><strong>Двигатель:</strong> {{ $car->engine }}</div>
                                    <div><strong>Топливо:</strong> {{ $car->fuel }}</div>
                                    <div><strong>Пробег:</strong> {{ $car->mileage }}</div>
                                    <div><strong>Состояние:</strong> {{ $car->condition }}</div>
                                </div>
                                <div class="price mt-2">{{ number_format($car->price, 0, ',', ' ') }} ₽</div>
                                <div class="actions mt-3">
                                    <button class="btn btn-sm btn-outline-primary view-car-btn"
                                        data-car-id="{{ $car->id }}">Подробнее</button>
                                    <button class="btn btn-sm btn-outline-secondary edit-car-btn"
                                        data-car-id="{{ $car->id }}">Редактировать</button>
                                    <a href="{{ route('admin.auctions.index', ['manage_photos' => $car->id]) }}"
                                        class="btn btn-sm btn-outline-info">Фото</a>
                                    <button class="btn btn-sm btn-outline-danger delete-car-btn"
                                        data-car-id="{{ $car->id }}">Удалить</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </section>

    
    <div class="modal fade" id="addCarModal" tabindex="-1" aria-labelledby="addCarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCarModalLabel">Добавить автомобиль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form id="addCarForm" method="POST" action="{{ route('admin.auctions.cars.store') }}"
                    enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Модель <span class="text-danger">*</span></label>
                                <input name="model" type="text" class="form-control" maxlength="30" required
                                    placeholder="Lexus LX 500d" value="{{ old('model') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Привод <span class="text-danger">*</span></label>
                                <select name="drive" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Полный">Полный</option>
                                    <option value="Передний">Передний</option>
                                    <option value="Задний">Задний</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Объем двигателя (L) <span class="text-danger">*</span></label>
                                <input name="engine" type="text" class="form-control" maxlength="20"
                                    placeholder="5.0 L" required value="{{ old('engine') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Тип топлива <span class="text-danger">*</span></label>
                                <select name="fuel" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Бензин">Бензин</option>
                                    <option value="Дизель">Дизель</option>
                                    <option value="Гибрид">Гибрид</option>
                                    <option value="Электро">Электро</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Пробег (км) <span class="text-danger">*</span></label>
                                <input name="mileage" type="text" class="form-control" maxlength="50"
                                    placeholder="140 000 км" required value="{{ old('mileage') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Состояние <span class="text-danger">*</span></label>
                                <select name="condition" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Не битая">Не битая</option>
                                    <option value="Битая">Битая</option>
                                    <option value="Аварийная">Аварийная</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Кол-во владельцев <span class="text-danger">*</span></label>
                                <input name="owners" type="number" min="1" max="99" class="form-control"
                                    placeholder="1" value="{{ old('owners', 1) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Кол-во передач <span class="text-danger">*</span></label>
                                <input name="transmissions" type="number" min="1" max="12"
                                    class="form-control" placeholder="8" value="{{ old('transmissions', 6) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Вместимость багажника (L) <span
                                        class="text-danger">*</span></label>
                                <input name="trunk" type="text" class="form-control" maxlength="20"
                                    placeholder="701 L" required value="{{ old('trunk') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Коробка передач <span class="text-danger">*</span></label>
                                <select name="gearbox" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Автомат">Автомат</option>
                                    <option value="Механика">Механика</option>
                                    <option value="Робот">Робот</option>
                                    <option value="Вариатор">Вариатор</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Тип кузова <span class="text-danger">*</span></label>
                                <select name="body" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Кроссовер">Кроссовер</option>
                                    <option value="Седан">Седан</option>
                                    <option value="Хэтчбек">Хэтчбек</option>
                                    <option value="Универсал">Универсал</option>
                                    <option value="Купе">Купе</option>
                                    <option value="Кабриолет">Кабриолет</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input name="price" type="number" class="form-control" placeholder="12500000"
                                        required step="1000" min="1000" max="1000000000"
                                        value="{{ old('price') }}">
                                    <span class="input-group-text">₽</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Описание <span class="text-danger">*</span></label>
                                <textarea name="description" rows="4" class="form-control" maxlength="2000"
                                    placeholder="Перечислите опции и состояние автомобиля" required>{{ old('description') }}</textarea>
                                <div class="form-text"><span id="add_description_counter">0</span>/2000 символов</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Основное фото автомобиля <span
                                        class="text-danger">*</span></label>
                                <input type="file" accept="image/*" name="main_photo" class="form-control" required>
                                <div class="form-text">Первое загруженное фото будет основным. Максимальный размер: 10MB
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_car" class="btn modal-btn w-100"><i
                                class="bi bi-save me-1"></i> Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCarModalLabel">Редактировать автомобиль</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form id="editCarForm" method="POST" action="" enctype="multipart/form-data"
                    class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="car_id" id="edit_car_id">
                    <div class="modal-body p-3">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Модель <span class="text-danger">*</span></label>
                                <input name="model" id="edit_car_model" type="text" class="form-control"
                                    maxlength="30" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Привод <span class="text-danger">*</span></label>
                                <select name="drive" id="edit_car_drive" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Полный">Полный</option>
                                    <option value="Передний">Передний</option>
                                    <option value="Задний">Задний</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Объем двигателя (L) <span class="text-danger">*</span></label>
                                <input name="engine" id="edit_car_engine" type="text" class="form-control"
                                    maxlength="20" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Тип топлива <span class="text-danger">*</span></label>
                                <select name="fuel" id="edit_car_fuel" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Бензин">Бензин</option>
                                    <option value="Дизель">Дизель</option>
                                    <option value="Гибрид">Гибрид</option>
                                    <option value="Электро">Электро</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Пробег (км) <span class="text-danger">*</span></label>
                                <input name="mileage" id="edit_car_mileage" type="text" class="form-control"
                                    maxlength="50" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Состояние <span class="text-danger">*</span></label>
                                <select name="condition" id="edit_car_condition" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Не битая">Не битая</option>
                                    <option value="Битая">Битая</option>
                                    <option value="Аварийная">Аварийная</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Кол-во владельцев <span class="text-danger">*</span></label>
                                <input name="owners" id="edit_car_owners" type="number" min="1" max="99"
                                    class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Кол-во передач <span class="text-danger">*</span></label>
                                <input name="transmissions" id="edit_car_transmissions" type="number" min="1"
                                    max="12" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Вместимость багажника (L) <span
                                        class="text-danger">*</span></label>
                                <input name="trunk" id="edit_car_trunk" type="text" class="form-control"
                                    maxlength="20" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Коробка передач <span class="text-danger">*</span></label>
                                <select name="gearbox" id="edit_car_gearbox" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Автомат">Автомат</option>
                                    <option value="Механика">Механика</option>
                                    <option value="Робот">Робот</option>
                                    <option value="Вариатор">Вариатор</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Тип кузова <span class="text-danger">*</span></label>
                                <select name="body" id="edit_car_body" class="form-select" required>
                                    <option value="">Выберите...</option>
                                    <option value="Кроссовер">Кроссовер</option>
                                    <option value="Седан">Седан</option>
                                    <option value="Хэтчбек">Хэтчбек</option>
                                    <option value="Универсал">Универсал</option>
                                    <option value="Купе">Купе</option>
                                    <option value="Кабриолет">Кабриолет</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input name="price" id="edit_car_price" type="number" class="form-control"
                                        required step="1000" min="1000" max="1000000000">
                                    <span class="input-group-text">₽</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Описание <span class="text-danger">*</span></label>
                                <textarea name="description" id="edit_car_description" rows="4" class="form-control" maxlength="2000"
                                    required></textarea>
                                <div class="form-text"><span id="edit_description_counter">0</span>/2000 символов</div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Основное фото автомобиля</label>
                                <input type="file" accept="image/*" name="main_photo" class="form-control">
                                <div class="form-text">Оставьте пустым, чтобы не менять фото</div>
                                <img id="edit_car_photo_preview" src="" class="current-photo-preview mt-2"
                                    style="display:none;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn modal-btn w-100"><i class="bi bi-save me-1"></i>
                            Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="addAuctionModal" tabindex="-1" aria-labelledby="addAuctionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAuctionModalLabel">Создать аукцион</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form method="POST" action="{{ route('admin.auctions.store') }}" id="addAuctionForm"
                    class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Выберите автомобиль <span class="text-danger">*</span></label>
                                <select name="car_id" class="form-select" required>
                                    <option value="">Выберите автомобиль...</option>
                                    @foreach ($cars as $car)
                                        <option value="{{ $car->id }}">{{ $car->model }} (ID:
                                            {{ $car->id }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата начала <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="start_date" required
                                    id="start_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата окончания <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" name="end_date" required
                                    id="end_date">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Начальная цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="starting_price" required
                                        step="1000" min="1000" max="1000000000"
                                        value="{{ old('starting_price', 100000) }}">
                                    <span class="input-group-text">₽</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Резервная цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="reserve_price" step="1000"
                                        min="0" max="1000000000" value="{{ old('reserve_price', 0) }}" required>
                                    <span class="input-group-text">₽</span>
                                </div>
                                <div class="form-text">0 = без резервной цены</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" name="create_auction" class="btn btn-primary">Создать аукцион</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editAuctionModal" tabindex="-1" aria-labelledby="editAuctionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAuctionModalLabel">Редактировать аукцион</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <form method="POST" action="" id="editAuctionForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="auction_id" id="edit_auction_id">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Дата начала <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="edit_start_date" name="start_date"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Дата окончания <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="form-control" id="edit_end_date" name="end_date"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Начальная цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="edit_starting_price"
                                        name="starting_price" required step="1000" min="1000" max="1000000000">
                                    <span class="input-group-text">₽</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Резервная цена (₽) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="edit_reserve_price"
                                        name="reserve_price" step="1000" min="0" max="1000000000" required>
                                    <span class="input-group-text">₽</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Заметки для победителя</label>
                                <textarea class="form-control" id="edit_winner_notes" name="winner_notes" rows="3"
                                    placeholder="Условия получения, контакты менеджера и т.д."></textarea>
                                <div class="form-text">Будут показаны победителю в личном кабинете</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" name="edit_auction" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCarModal" tabindex="-1" aria-labelledby="deleteCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCarModalLabel">Подтверждение удаления</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
            </div>
            <div class="modal-body">
                <p>Удалить автомобиль <strong id="deleteCarModelName"></strong>?</p>
                <p class="text-danger mb-0">Данное действие нельзя отменить. При наличии связанных аукционов удаление может оказаться невозможным.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCarBtn">
                    <i class="bi bi-trash me-1"></i> Удалить
                </button>
            </div>
        </div>
    </div>
</div>

    
    <div class="modal fade" id="viewRowModal" tabindex="-1" aria-labelledby="viewRowModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewRowModalLabel">Детали автомобиля</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body" id="carDetailsContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="button" class="btn btn-danger" id="deleteCarFromViewBtn">
    <i class="bi bi-trash me-1"></i> Удалить
</button>
                    <a href="#" class="btn btn-dark" id="managePhotosBtn">
                        <i class="bi bi-images me-1"></i> Загрузить доп фото
                    </a>
                    <button type="button" class="btn btn-primary" id="editCarFromViewBtn">Редактировать</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function getCsrfToken() {
                return document.querySelector('meta[name="csrf-token"]')?.content ||
                    document.querySelector('input[name="_token"]')?.value || '';
            }

            function clearFieldErrors(form) {
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }

            function displayFieldErrors(form, errors) {
                for (let field in errors) {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        let fb = input.parentNode.querySelector('.invalid-feedback');
                        if (!fb) {
                            fb = document.createElement('div');
                            fb.className = 'invalid-feedback';
                            input.parentNode.insertBefore(fb, input.nextSibling);
                        }
                        fb.textContent = errors[field][0];
                    }
                }
            }

            async function submitFormAjax(form, modalId) {
                const modalEl = modalId ? document.getElementById(modalId) : null;
                const modal = modalEl ? (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)) :
                    null;

                clearFieldErrors(form);
                const btn = form.querySelector('button[type="submit"]');
                const original = btn ? btn.innerHTML : '';
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Сохранение...';
                }

                try {
                    const res = await fetch(form.action, {
                        method: form.method,
                        headers: {
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    });
                    const data = await res.json();

                    if (res.ok) {
                        if (modal) modal.hide();
                        window.location.reload();
                    } else if (res.status === 422) {
                        displayFieldErrors(form, data.errors);
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = original;
                        }
                    } else {
                        alert(data.error || 'Ошибка сервера');
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = original;
                        }
                    }
                } catch {
                    alert('Ошибка соединения');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = original;
                    }
                }
            }

            function initDetermineWinnerForms() {
                document.querySelectorAll('form[action*="determine-winner"], form[action*="determineWinner"]')
                    .forEach(form => {
                        if (form.dataset.handlerAttached) return;
                        form.dataset.handlerAttached = 'true';

                        form.addEventListener('submit', async function(e) {
                            e.preventDefault();
                            if (!confirm('Завершить аукцион и определить победителя?')) return;

                            const submitBtn = this.querySelector('button[type="submit"]');
                            const originalText = submitBtn.innerHTML;
                            submitBtn.disabled = true;
                            submitBtn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2"></span> Обработка...';

                            try {
                                const response = await fetch(this.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': getCsrfToken(),
                                        'Accept': 'application/json'
                                    },
                                    body: new FormData(this)
                                });

                                const data = await response.json();

                                if (response.ok && data.success) {
                                    window.location.reload();
                                } else {
                                    alert(data.error || data.message || 'Произошла ошибка');
                                    submitBtn.disabled = false;
                                    submitBtn.innerHTML = originalText;
                                }
                            } catch {
                                alert('Ошибка соединения. Попробуйте ещё раз.');
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                            }
                        });
                    });
            }

            let carIdToDelete = null;

function openDeleteModal(carId, modelName) {
    carIdToDelete = carId;
    document.getElementById('deleteCarModelName').textContent = modelName || '';
    new bootstrap.Modal(document.getElementById('deleteCarModal')).show();
}

document.querySelectorAll('.delete-car-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const row = document.querySelector('tr[data-id="' + this.dataset.carId + '"]');
        openDeleteModal(this.dataset.carId, row ? row.dataset.model : '');
    });
});

const deleteCarFromViewBtn = document.getElementById('deleteCarFromViewBtn');
if (deleteCarFromViewBtn) {
    deleteCarFromViewBtn.addEventListener('click', function() {
        const carId = document.getElementById('editCarFromViewBtn').dataset.carId;
        const row = document.querySelector('tr[data-id="' + carId + '"]');
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRowModal'));
        if (viewModal) viewModal.hide();
        openDeleteModal(carId, row ? row.dataset.model : '');
    });
}

const confirmDeleteCarBtn = document.getElementById('confirmDeleteCarBtn');
if (confirmDeleteCarBtn) {
    confirmDeleteCarBtn.addEventListener('click', async function() {
        if (!carIdToDelete) return;
        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Удаление...';

        const formData = new FormData();
        formData.append('_method', 'DELETE');

        try {
            const response = await fetch('{{ route("admin.auctions.cars.destroy", "") }}/' + carIdToDelete, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: formData
            });
            const data = await response.json();

            if (response.ok && data.success) {
                window.location.reload();
            } else {
                alert(data.error || 'Ошибка при удалении');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            }
        } catch {
            alert('Ошибка соединения');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });
}

            const uploadForm = document.getElementById('uploadPhotosForm');
            if (uploadForm) {
                uploadForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;

                    submitBtn.disabled = true;
                    submitBtn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span> Загрузка...';

                    this.querySelectorAll('.photo-error').forEach(el => el.remove());

                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.location.reload();
                        } else if (response.status === 422 && data.errors) {
                            Object.values(data.errors).forEach(errs => {
                                const div = document.createElement('div');
                                div.className = 'alert alert-danger mt-2 photo-error';
                                div.textContent = errs[0];
                                uploadForm.querySelector('.row')?.appendChild(div);
                            });
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        } else {
                            alert(data.error || 'Ошибка загрузки');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = originalText;
                        }
                    } catch {
                        alert('Ошибка соединения');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                });
            }

            document.querySelectorAll('form[action*="photos.delete"]').forEach(form => {
                if (form.dataset.handlerAttached) return;
                form.dataset.handlerAttached = 'true';

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    if (!confirm('Удалить это фото?')) return;

                    const btn = this.querySelector('button[type="submit"]');
                    const original = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    try {
                        const res = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: new FormData(this)
                        });
                        const data = await res.json();

                        if (res.ok && data.success) {
                            window.location.reload();
                        } else {
                            alert(data.error || 'Ошибка');
                            btn.disabled = false;
                            btn.innerHTML = original;
                        }
                    } catch {
                        alert('Ошибка соединения');
                        btn.disabled = false;
                        btn.innerHTML = original;
                    }
                });
            });

            document.querySelectorAll('form[action*="photos.setMain"]').forEach(form => {
                if (form.dataset.handlerAttached) return;
                form.dataset.handlerAttached = 'true';

                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('button[type="submit"]');
                    const original = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

                    try {
                        const res = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': getCsrfToken(),
                                'Accept': 'application/json'
                            },
                            body: new FormData(this)
                        });
                        const data = await res.json();

                        if (res.ok && data.success) {
                            window.location.reload();
                        } else {
                            alert(data.error || 'Ошибка');
                            btn.disabled = false;
                            btn.innerHTML = original;
                        }
                    } catch {
                        alert('Ошибка соединения');
                        btn.disabled = false;
                        btn.innerHTML = original;
                    }
                });
            });

            const addCarForm = document.getElementById('addCarForm');
            if (addCarForm) {
                addCarForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitFormAjax(this, 'addCarModal');
                });
            }

            const editCarForm = document.getElementById('editCarForm');
            if (editCarForm) {
                editCarForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    submitFormAjax(this, 'editCarModal');
                });
            }

            const addAuctionForm = document.getElementById('addAuctionForm');
            if (addAuctionForm) {
                addAuctionForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const start = new Date(document.getElementById('start_date').value);
                    const end = new Date(document.getElementById('end_date').value);
                    if (end <= start) {
                        displayFieldErrors(this, {
                            end_date: ['Дата окончания должна быть позже даты начала.']
                        });
                        return;
                    }
                    await submitFormAjax(this, 'addAuctionModal');
                });
            }

            const editAuctionForm = document.getElementById('editAuctionForm');
            if (editAuctionForm) {
                editAuctionForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const start = new Date(document.getElementById('edit_start_date').value);
                    const end = new Date(document.getElementById('edit_end_date').value);
                    if (end <= start) {
                        displayFieldErrors(this, {
                            end_date: ['Дата окончания должна быть позже даты начала.']
                        });
                        return;
                    }
                    await submitFormAjax(this, 'editAuctionModal');
                });
            }

            const addDesc = document.querySelector('#addCarForm textarea[name="description"]');
            const addCnt = document.getElementById('add_description_counter');
            if (addDesc && addCnt) {
                addDesc.addEventListener('input', () => addCnt.textContent = addDesc.value.length);
            }

            const editDesc = document.querySelector('#editCarForm textarea[name="description"]');
            const editCnt = document.getElementById('edit_description_counter');
            if (editDesc && editCnt) {
                editDesc.addEventListener('input', () => editCnt.textContent = editDesc.value.length);
            }

            function openCarDetailsModal(carId) {
                const row = document.querySelector(`tr[data-id="${carId}"]`);
                if (!row) return;
                const d = row.dataset;
                const fallbackImg = '{{ asset('assets/images/one_car/image-1.jpg') }}';
                const html = `
            <div class="row">
                <div class="col-md-6">
                    <img src="${d.photo || fallbackImg}" class="img-fluid rounded mb-3" style="max-height:300px;width:100%;object-fit:cover" onerror="this.src='${fallbackImg}'">
                </div>
                <div class="col-md-6">
                    <h4>${d.model}</h4>
                    <p class="fs-5 text-primary"><strong>Цена:</strong> ${d.price}</p>
                    <p><strong>Состояние:</strong> ${d.condition || '-'}</p>
                    <p><strong>Коробка:</strong> ${d.gearbox || '-'}</p>
                </div>
            </div>
            <div class="mt-3">
                <p><strong>Описание:</strong></p>
                <p style="white-space:pre-wrap">${d.description}</p>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h6>Характеристики</h6>
                    <table class="table table-sm">
                        <tr><td>Привод</td><td>${d.drive || '-'}</td></tr>
                        <tr><td>Двигатель</td><td>${d.engine || '-'}</td></tr>
                        <tr><td>Топливо</td><td>${d.fuel || '-'}</td></tr>
                        <tr><td>Пробег</td><td>${d.mileage || '-'}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Дополнительно</h6>
                    <table class="table table-sm">
                        <tr><td>Владельцев</td><td>${d.owners || '-'}</td></tr>
                        <tr><td>Передач</td><td>${d.transmissions || '-'}</td></tr>
                        <tr><td>Кузов</td><td>${d.body || '-'}</td></tr>
                        <tr><td>Багажник</td><td>${d.trunk || '-'}</td></tr>
                    </table>
                </div>
            </div>`;
                document.getElementById('carDetailsContent').innerHTML = html;
                document.getElementById('managePhotosBtn').href =
                    '{{ route('admin.auctions.index') }}?manage_photos=' + d.id;
                document.getElementById('editCarFromViewBtn').dataset.carId = d.id;
                new bootstrap.Modal(document.getElementById('viewRowModal')).show();
            }

            document.querySelectorAll('.row-more-btn, .view-car-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openCarDetailsModal(this.dataset.carId);
                });
            });

            function openEditCarModal(carId) {
                const row = document.querySelector(`tr[data-id="${carId}"]`);
                if (!row) return;
                const d = row.dataset;

                document.getElementById('edit_car_id').value = carId;
                document.getElementById('edit_car_model').value = d.model || '';
                document.getElementById('edit_car_drive').value = d.drive || '';
                document.getElementById('edit_car_engine').value = d.engine || '';
                document.getElementById('edit_car_fuel').value = d.fuel || '';
                document.getElementById('edit_car_mileage').value = d.mileage || '';
                document.getElementById('edit_car_condition').value = d.condition || '';
                document.getElementById('edit_car_owners').value = d.owners || '';
                document.getElementById('edit_car_transmissions').value = d.transmissions || '';
                document.getElementById('edit_car_trunk').value = d.trunk || '';
                document.getElementById('edit_car_gearbox').value = d.gearbox || '';
                document.getElementById('edit_car_body').value = d.body || '';
                document.getElementById('edit_car_price').value = d.priceRaw || '';
                document.getElementById('edit_car_description').value = d.description || '';

                const preview = document.getElementById('edit_car_photo_preview');
                if (d.photo) {
                    preview.src = d.photo;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }

                document.getElementById('editCarForm').action = '{{ route('admin.auctions.cars.update', '') }}/' +
                    carId;

                const counter = document.getElementById('edit_description_counter');
                if (counter) counter.textContent = (d.description || '').length;

                new bootstrap.Modal(document.getElementById('editCarModal')).show();
            }

            document.querySelectorAll('.edit-car-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openEditCarModal(this.dataset.carId);
                });
            });

            document.getElementById('editCarFromViewBtn').addEventListener('click', function() {
                const carId = this.dataset.carId;
                if (carId) {
                    const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewRowModal'));
                    if (viewModal) viewModal.hide();
                    openEditCarModal(carId);
                }
            });

            document.querySelectorAll('.manage-auction-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const d = this.dataset;
                    document.getElementById('edit_auction_id').value = d.auctionId;
                    document.getElementById('edit_start_date').value = d.startDate;
                    document.getElementById('edit_end_date').value = d.endDate;
                    document.getElementById('edit_starting_price').value = d.startingPrice;
                    document.getElementById('edit_reserve_price').value = d.reservePrice;
                    document.getElementById('edit_winner_notes').value = d.winnerNotes || '';
                    document.getElementById('editAuctionForm').action =
                        '{{ route('admin.auctions.update', '') }}/' + d.auctionId;
                    new bootstrap.Modal(document.getElementById('editAuctionModal')).show();
                });
            });

            const backToTop = document.getElementById('backToTop');
            if (backToTop) {
                window.addEventListener('scroll', () => backToTop.classList.toggle('show', window.pageYOffset >
                    300));
                backToTop.addEventListener('click', e => {
                    e.preventDefault();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            initDetermineWinnerForms();
        });
</script>
@endpush