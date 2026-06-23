@extends('layouts.app')

@section('title', 'Личный кабинет')

@section('content')
    <div class="container-xxl py-5">

        @if (session('success_message'))
            <div class="alert alert-success profile-message alert-dismissible fade show" role="alert">
                {{ session('success_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error_message'))
            <div class="alert alert-danger profile-message alert-dismissible fade show" role="alert">
                {{ session('error_message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="profile-row">
            <div class="profile-left">
                <div class="avatar" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    {{ $user->name ? strtoupper(mb_substr($user->name, 0, 1)) : 'П' }}
                </div>
                <div>
                    <div class="profile-name">{{ $user->name ?? 'Пользователь' }}</div>
                    <div class="profile-email">{{ $user->email ?? 'user@example.com' }}</div>
                </div>
            </div>
        </div>

        <div class="tabs-wrap" aria-label="Фильтр по разделам">
            <nav class="tabs" id="brandTabs" role="tablist">
                <button class="tab tab-auc active" data-target="tab-configs" role="tab" aria-selected="true">Мои
                    конфигурации</button>
                <button class="tab tab-auc" data-target="tab-bids" role="tab" aria-selected="false">Текущие
                    ставки</button>
                <button class="tab tab-auc" data-target="tab-favorites" role="tab"
                    aria-selected="false">Избранное</button>
                <div class="tab-indicator" aria-hidden="true"></div>
            </nav>
            <div class="tabs-line" aria-hidden="true"></div>
        </div>

        <div class="row-main">

            <section id="tab-configs" class="tab-section w-100" style="display:block;">
                <div class="row">
                    <div class="col-12">
                        <div class="offers-grid" id="offersGrid">
                            @forelse($configurations as $config)
                                @php
                                    $carConfig = $config->carConfig;
                                    $carName = $carConfig->name ?? 'Автомобиль';
                                    $brand = explode(' ', $carName)[0] ?? 'CAR';
                                    $totalPrice = $config->total_price ?? ($carConfig->base_price ?? 0);
                                    $photo = $carConfig->main_image ?? asset('assets/images/offer/2.png');
                                @endphp
                                <div class="offer-card" data-id="c{{ $config->id }}" data-type="config">
                                    <div class="card-top-right">
                                        <button class="dot-btn btn-open-menu" title="Ещё" aria-expanded="false">
                                            <svg width="16" height="16" viewBox="0 0 24 24">
                                                <circle cx="5" cy="12" r="1.6" />
                                                <circle cx="12" cy="12" r="1.6" />
                                                <circle cx="19" cy="12" r="1.6" />
                                            </svg>
                                        </button>
                                        <div class="context-menu" style="display: none;">
                                            <button class="menu-open"
                                                onclick="openConfigurator({{ $carConfig->id }}, '{{ $config->selected_engine }}', '{{ $config->selected_color }}', '{{ $config->selected_interior }}')">
                                                Перейти к конфигурации
                                            </button>
                                            <button class="menu-delete text-danger"
                                                onclick="showDeleteConfigModal({{ $config->id }}, '{{ $config->config_name ?? 'Конфигурация' }}')">Удалить
                                                конфигурацию</button>
                                        </div>
                                    </div>
                                    <div class="offer-header mb-0">
                                        <h3 class="mb-0">{{ $carName }}</h3>
                                    </div>
                                    <p class="year d-flex align-items-start">
                                        {{ $config->config_name ?? 'Моя конфигурация' }}</p>
                                    <div class="offer-image">
                                        <img src="{{ $photo }}" alt="{{ $carName }}"
                                            onerror="this.src='{{ asset('assets/images/offer/2.png') }}'">
                                        <span class="brand-watermark">{{ strtoupper($brand) }}</span>
                                    </div>
                                    <div class="offer-specs">
                                        <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                            {{ $carConfig->variant ?? 'Standard' }}</span>
                                        <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                            СОХРАНЕНО</span>
                                    </div>
                                    <div class="offer-price">
                                        <span class="price-text">{{ number_format($totalPrice, 0, '', ' ') }} ₽</span>
                                        <a href="{{ route('configurator.show', $carConfig->id) }}?engine={{ urlencode($config->selected_engine) }}&color={{ urlencode($config->selected_color) }}&interior={{ urlencode($config->selected_interior) }}"
                                            class="more-text">
                                            ПОДРОБНЕЕ
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="py-5">
                                    <p>У вас пока нет сохраненных конфигураций</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-favorites" class="tab-section w-100" style="display:none;">
                <div class="row w-100">
                    <div class="col-12">
                        <div class="offers-grid" id="favoritesGrid">
                            @forelse($favorites as $fav)
                                @php $car = $fav->car; @endphp
                                <div class="offer-card" data-id="f{{ $fav->id }}" data-type="favorite">
                                    <div class="card-top-right">
                                        <button class="dot-btn btn-open-menu" title="Ещё" aria-expanded="false">
                                            <svg width="16" height="16" viewBox="0 0 24 24">
                                                <circle cx="5" cy="12" r="1.6" />
                                                <circle cx="12" cy="12" r="1.6" />
                                                <circle cx="19" cy="12" r="1.6" />
                                            </svg>
                                        </button>
                                        <div class="context-menu" style="display: none;">
                                            <button class="menu-view"
                                                onclick="window.location.href='{{ route('catalog.show', $car->id) }}'">Перейти
                                                к авто</button>
                                            <button class="menu-delete text-danger"
                                                onclick="showDeleteFavoriteModal({{ $fav->id }}, '{{ $car->model ?? 'Автомобиль' }}')">Удалить
                                                из избранного</button>
                                        </div>
                                    </div>
                                    <div class="offer-header mb-0">
                                        <h3 class="mb-0">{{ $car->model ?? 'Автомобиль' }}</h3>
                                    </div>
                                    <p class="year d-flex align-items-start">{{ date('Y') }}</p>
                                    <div class="offer-image">
                                        @if ($car->catalog_photo_path)
                                            <img src="{{ asset($car->catalog_photo_path) }}" alt="{{ $car->model }}"
                                                onerror="this.src='{{ asset('assets/images/offer/2.png') }}'">
                                        @else
                                            <img src="{{ asset('assets/images/offer/2.png') }}"
                                                alt="{{ $car->model }}">
                                        @endif
                                        <span class="brand-watermark">{{ strtoupper($car->brand ?? 'AUTO') }}</span>
                                    </div>
                                    <div class="offer-specs">
                                        <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt="">
                                            {{ $car->engine ?? '2.0' }} L</span>
                                        <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                            {{ $car->drive ?? 'AWD' }}</span>
                                        <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                            {{ $car->body ?? 'КРОССОВЕР' }}</span>
                                    </div>
                                    <div class="offer-price">
                                        <span class="price-text">{{ number_format($car->price ?? 0, 0, '', ' ') }}
                                            ₽</span>
                                        <a href="{{ route('catalog.show', $car->id) }}" class="more-text">ПОДРОБНЕЕ</a>
                                    </div>
                                </div>
                            @empty
                                <div class="py-5">
                                    <p>У вас пока нет избранных автомобилей</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <section id="tab-bids" class="tab-section w-100" style="display:none;">
                <div class="row">
                    <div class="col-12">
                        <div class="offers-grid" id="bidsGrid">
                            @forelse($bids as $bid)
                                @php $bid = (object) $bid; @endphp
                                <div class="offer-card {{ $bid->overall_status === 'won' ? 'won-bid-card' : '' }}"
                                    data-id="a{{ $bid->auction_id }}" data-type="bid"
                                    data-status="{{ $bid->overall_status }}"
                                    data-winner-notes="{{ $bid->winner_notes ?? '' }}"
                                    data-final-price="{{ $bid->final_price ? number_format($bid->final_price, 0, '', ' ') . ' ₽' : 'По договорённости' }}">

                                    <div class="card-top-right">
                                        @if (in_array($bid->overall_status, ['active', 'leading', 'outbid']))
                                            <button class="dot-btn btn-open-menu" title="Ещё" aria-expanded="false">
                                                <svg width="16" height="16" viewBox="0 0 24 24">
                                                    <circle cx="5" cy="12" r="1.6" />
                                                    <circle cx="12" cy="12" r="1.6" />
                                                    <circle cx="19" cy="12" r="1.6" />
                                                </svg>
                                            </button>
                                            <div class="context-menu" style="display: none;">
                                                <button class="menu-view"
                                                    onclick="window.location.href='{{ route('auction.show', $bid->auction_id) }}'">Перейти
                                                    к лоту</button>
                                                <button class="menu-info"
                                                    onclick="showBidDetailsModal({{ $bid->auction_id }})">Информация о
                                                    ставках</button>
                                                <button class="menu-cancel text-danger"
                                                    onclick="showCancelAllBidsModal({{ $bid->auction_id }}, '{{ $bid->model ?? 'Автомобиль' }}')">Отменить
                                                    все ставки</button>
                                            </div>
                                        @elseif($bid->overall_status === 'won')
                                            <span class="badge status-badge text-success">ПОБЕДА</span>
                                        @elseif($bid->overall_status === 'lost')
                                            <span class="badge status-badge text-secondary">ПРОИГРЫШ</span>
                                        @endif
                                    </div>

                                    <div class="offer-header mb-0">
                                        <h3 class="mb-0">{{ $bid->model ?? 'Автомобиль' }}</h3>
                                    </div>

                                    <p class="year d-flex align-items-start">
                                        @if ($bid->overall_status === 'active')
                                            <span class="badge status-badge text-primary">АКТИВНЫЕ СТАВКИ</span>
                                        @elseif($bid->overall_status === 'leading')
                                            <span class="badge status-badge text-success">ЛИДИРУЕТЕ</span>
                                        @elseif($bid->overall_status === 'outbid')
                                            <span class="badge status-badge text-danger">СТАВКИ ПЕРЕБИТЫ</span>
                                        @elseif($bid->overall_status === 'won')
                                            <span class="badge status-badge text-success">ВЫ ВЫИГРАЛИ</span>
                                        @elseif($bid->overall_status === 'lost')
                                            <span class="badge status-badge text-secondary">АУКЦИОН ПРОИГРАН</span>
                                        @endif
                                    </p>

                                    <div class="offer-image {{ $bid->overall_status === 'won' ? 'won-bid-image' : '' }}">
                                        @if ($bid->photo)
                                            <img src="{{ asset('storage/' . $bid->photo) }}"
                                                alt="{{ $bid->model ?? 'Автомобиль' }}"
                                                onerror="this.src='{{ asset('assets/images/offer/2.png') }}'">
                                        @else
                                            <img src="{{ asset('assets/images/offer/2.png') }}"
                                                alt="{{ $bid->model ?? 'Автомобиль' }}">
                                        @endif

                                        @if ($bid->overall_status === 'won')
                                            <div class="won-bid-overlay">
                                                <button class="claim-prize-btn"
                                                    onclick="startVictoryAnimation({{ $bid->auction_id }})">
                                                    <svg width="20" height="20" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                                    </svg>
                                                    ЗАБРАТЬ ПРИЗ
                                                </button>
                                            </div>
                                        @endif

                                        <span
                                            class="brand-watermark">{{ strtoupper(explode(' ', $bid->model ?? 'AUTO')[0] ?? 'AUTO') }}</span>
                                    </div>

                                    <div class="offer-specs">
                                        <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt="">
                                            {{ $bid->engine ?? '2.0' }} L</span>
                                        <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                            {{ $bid->drive ?? 'AWD' }}</span>
                                        <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                            {{ $bid->body ?? 'КРОССОВЕР' }}</span>
                                    </div>

                                    <div class="offer-price">
                                        @if ($bid->overall_status === 'won')
                                            <span class="price-text">{{ number_format($bid->max_user_bid, 0, '', ' ') }}
                                                ₽</span>
                                            <a href="#" class="more-text"
                                                onclick="startVictoryAnimation({{ $bid->auction_id }}); return false;">ЗАБРАТЬ</a>
                                        @elseif($bid->overall_status === 'lost')
                                            <span class="price-text">Ваши ставки: {{ $bid->bid_count }} шт.</span>
                                            <a href="#" class="more-text"
                                                onclick="showLostAuctionModal({{ $bid->auction_id }}); return false;">ПОДРОБНЕЕ</a>
                                        @else
                                            <span class="price-text">Ваши ставки: {{ $bid->bid_count }} шт.</span>
                                            <a href="{{ route('auction.show', $bid->auction_id) }}"
                                                class="more-text">ПОДРОБНЕЕ</a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="py-5">
                                    <p>У вас пока нет активных ставок</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-top">
                    <div class="modal-content">
                        <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Закрыть">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                            </svg>
                        </button>

                        <div class="modal-inner-box">
                            <div class="content">
                                <div class="svg-vector">
                                    <svg width="421" height="578" viewBox="0 0 421 578" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M-133.22 1.40758C-133.22 1.40758 124.267 100.639 139.145 217.357C154.023 334.075 33.8168 309.992 37.1622 451.076C40.5076 592.16 361.784 651.036 405.765 739.023C449.745 827.01 371.441 913.95 371.441 913.95"
                                            stroke="white" stroke-opacity="0.8" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>
                                <div class="svg-vector-1">
                                    <svg width="648" height="451" viewBox="0 0 648 451" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M892.5 1.4448C892.5 1.4448 616.816 -10.5824 556.5 90.4448C496.184 191.472 615.99 217.473 556.5 345.445C497.01 473.417 179.633 359.331 103.5 461.945C27.3667 564.559 112.745 599.207 50 681.945C-12.7447 764.683 -274 735.445 -274 735.445"
                                            stroke="white" stroke-opacity="0.8" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </div>

                                <h2 class="modal-title">РЕДАКТИРОВАТЬ ПРОФИЛЬ</h2>
                                <div class="divider"></div>

                                <form class="profile-edit-form" method="POST" action="{{ route('profile.update') }}"
                                    novalidate>
                                    @csrf
                                    <input type="hidden" name="update_profile" value="1">

                                    <div class="field mb-4 mt-4" data-name="name">
                                        <div class="left-icon" aria-hidden="true">
                                            <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                            </svg>
                                        </div>
                                        <input id="edit-name" name="name" type="text" placeholder=" " required
                                            pattern=".{2,}" autocomplete="name"
                                            value="{{ old('name', $user->name) }}" />
                                        <label for="edit-name">Ваше имя</label>
                                        @error('name')
                                            <span class="text-danger small field-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="field mb-4" data-name="email">
                                        <div class="left-icon" aria-hidden="true">
                                            <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                                <path
                                                    d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                            </svg>
                                        </div>
                                        <input id="edit-email" name="email" type="email" placeholder=" " required
                                            autocomplete="email" value="{{ old('email', $user->email) }}" />
                                        <label for="edit-email">Email</label>
                                        @error('email')
                                            <span class="text-danger small field-error">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <button class="submit" type="submit">СОХРАНИТЬ ИЗМЕНЕНИЯ</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon danger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 9V13M12 17H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0378 2.66667 10.268 4L3.33978 16C2.56997 17.3333 3.53223 19 5.07183 19Z"
                                        stroke="#ef4444" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 class="mb-2" style="font-weight: 600; color: #111;">Подтверждение удаления</h5>
                            <p id="deleteModalText" class="mb-4"
                                style="color: var(--muted); font-size: 15px; line-height: 1.5;">Вы действительно хотите
                                удалить этот элемент?</p>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: var(--muted); background: transparent; border: 1px solid rgba(0,0,0,0.08);"
                                    data-bs-dismiss="modal">Отмена</button>
                                <button id="confirmDeleteBtn" type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="cancelBidModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon warning">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 9V13M12 17H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0378 2.66667 10.268 4L3.33978 16C2.56997 17.3333 3.53223 19 5.07183 19Z"
                                        stroke="#f59e0b" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 class="mb-2" style="font-weight: 600; color: #111;">Отмена ставок</h5>
                            <p id="cancelBidModalText" class="mb-4"
                                style="color: var(--muted); font-size: 15px; line-height: 1.5;">Вы действительно хотите
                                отменить все ставки на этот лот?</p>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: var(--muted); background: transparent; border: 1px solid rgba(0,0,0,0.08);"
                                    data-bs-dismiss="modal">Отмена</button>
                                <button id="confirmCancelBidBtn" type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;">Отменить
                                    все</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon success">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20 6L9 17L4 12" stroke="#22c55e" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 class="mb-2" style="font-weight: 600; color: #111;">Успешно</h5>
                            <p id="successModalText" class="mb-4"
                                style="color: var(--muted); font-size: 15px; line-height: 1.5;">Операция выполнена успешно
                            </p>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;"
                                    data-bs-dismiss="modal">ОК</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="infoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon info">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 16V12M12 8H12.01M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                        stroke="#3b82f6" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 id="infoModalTitle" class="mb-2" style="font-weight: 600; color: #111;">Информация о
                                ставках</h5>
                            <div id="infoModalContent" class="mb-4">
                            </div>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 14px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;"
                                    data-bs-dismiss="modal">Понятно</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteConfigModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon danger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 9V13M12 17H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0378 2.66667 10.268 4L3.33978 16C2.56997 17.3333 3.53223 19 5.07183 19Z"
                                        stroke="#ef4444" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 class="mb-2" style="font-weight: 600; color: #111;">Удаление конфигурации</h5>
                            <p id="deleteConfigModalText" class="mb-4"
                                style="color: var(--muted); font-size: 15px; line-height: 1.5;">Вы действительно хотите
                                удалить эту конфигурацию?</p>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: var(--muted); background: transparent; border: 1px solid rgba(0,0,0,0.08);"
                                    data-bs-dismiss="modal">Отмена</button>
                                <button id="confirmDeleteConfigBtn" type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deleteFavoriteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                    <div class="modal-content modal-custom" style="padding: 24px;">
                        <div class="text-center">
                            <div class="modal-icon danger">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12 9V13M12 17H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0378 2.66667 10.268 4L3.33978 16C2.56997 17.3333 3.53223 19 5.07183 19Z"
                                        stroke="#ef4444" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </div>
                            <h5 class="mb-2" style="font-weight: 600; color: #111;">Удаление из избранного</h5>
                            <p id="deleteFavoriteModalText" class="mb-4"
                                style="color: var(--muted); font-size: 15px; line-height: 1.5;">Вы действительно хотите
                                удалить этот автомобиль из избранного?</p>
                            <div class="d-flex gap-2" style="justify-content: center;">
                                <button type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: var(--muted); background: transparent; border: 1px solid rgba(0,0,0,0.08);"
                                    data-bs-dismiss="modal">Отмена</button>
                                <button id="confirmDeleteFavoriteBtn" type="button" class="btn btn-sm"
                                    style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: var(--accent); border: none;">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade claim-modal" id="claimInfoModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5>Ваш автомобиль готов к выдаче</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Закрыть"></button>
                        </div>
                        <div class="modal-body">
                            <div id="winnerNotesBlock" style="display:none;">
                                <div
                                    style="background: linear-gradient(135deg, rgba(255,215,0,0.08), rgba(255,165,0,0.04)); border: 1px solid rgba(255,193,7,0.2); border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                                    <h6
                                        style="font-weight: 700; font-size: 13px; color: #856404; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                            stroke="#856404" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"
                                            style="vertical-align: text-bottom; margin-right: 6px;">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                        </svg>
                                        Сообщение от администратора
                                    </h6>
                                    <p id="winnerNotesText"
                                        style="margin: 0; font-size: 14px; color: #856404; line-height: 1.5; white-space: pre-wrap;">
                                    </p>
                                </div>
                            </div>
                            <div class="claim-congrats">
                                <p>Поздравляем с победой на аукционе! Следуйте инструкции ниже, чтобы забрать свой
                                    автомобиль.</p>
                            </div>

                            <div class="claim-step">
                                <div class="profile-step-number">1</div>
                                <div class="step-content">
                                    <h6>Свяжитесь с менеджером</h6>
                                    <p>В течение 24 часов с вами свяжется наш менеджер для уточнения деталей сделки</p>
                                </div>
                            </div>
                            <div class="claim-step">
                                <div class="profile-step-number">2</div>
                                <div class="step-content">
                                    <h6>Приезжайте к нам</h6>
                                    <p>Приезжайте в наш офис для осмотра автомобиля и оформления документов</p>
                                </div>
                            </div>
                            <div class="claim-step">
                                <div class="profile-step-number">3</div>
                                <div class="step-content">
                                    <h6>Оплатите на месте</h6>
                                    <p>Оплата производится непосредственно в офисе после подписания договора купли-продажи
                                    </p>
                                </div>
                            </div>
                            <div class="claim-step">
                                <div class="profile-step-number">4</div>
                                <div class="step-content">
                                    <h6>Заберите автомобиль</h6>
                                    <p>Получите ключи и документы — автомобиль ваш!</p>
                                </div>
                            </div>

                            <div class="claim-contacts">
                                <h6>Контакты для связи</h6>
                                <div class="contact-items">
                                    <div class="contact-item">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                        <span>+7 (999) 123-45-67</span>
                                    </div>
                                    <div class="contact-item">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                            <polyline points="22,6 12,13 2,6" />
                                        </svg>
                                        <span>auction@cardealer.ru</span>
                                    </div>
                                    <div class="contact-item">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                            <circle cx="12" cy="10" r="3" />
                                        </svg>
                                        <span>г. Москва, ул. Автомобильная, 15</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-claim-primary" data-bs-dismiss="modal">Понятно, жду
                                звонка</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade lost-modal" id="lostAuctionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
                    <div class="modal-content"
                        style="border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 48px rgba(0,0,0,0.15);">
                        <div class="modal-header" style="border-bottom: none; padding: 24px 28px 0;">
                            <h5 style="font-weight: 700; font-size: 18px; color: #1a1a1a; margin: 0;">Аукцион завершён</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Закрыть"></button>
                        </div>
                        <div class="modal-body" style="padding: 20px 28px;">
                            <div class="text-center mb-4">
                                <div
                                    style="width: 64px; height: 64px; background: rgba(108,117,125,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#6c757d" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                </div>
                                <h6 style="font-weight: 600; color: #1a1a1a; margin-bottom: 8px;">К сожалению, вы не
                                    выиграли этот лот</h6>
                                <p style="color: #6c757d; font-size: 14px; line-height: 1.5; margin: 0;">
                                    Аукцион по данному автомобилю завершён. Ниже краткая сводка по лоту.
                                </p>
                            </div>
                            <div style="background: #f8f9fa; border-radius: 16px; padding: 20px; margin-bottom: 20px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span style="color: #6c757d; font-size: 13px;">Автомобиль</span>
                                    <span
                                        style="font-weight: 600; font-size: 14px; color: #1a1a1a; text-align: right; max-width: 60%;"
                                        id="lostCarTitle">—</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span style="color: #6c757d; font-size: 13px;">Финальная цена</span>
                                    <span style="font-weight: 700; font-size: 14px; color: #4071CB;"
                                        id="lostFinalPrice">—</span>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span style="color: #6c757d; font-size: 13px;">Ваша последняя ставка</span>
                                    <span style="font-weight: 600; font-size: 14px; color: #1a1a1a;"
                                        id="lostUserBid">—</span>
                                </div>
                            </div>
                            <div
                                style="background: linear-gradient(135deg, rgba(64,113,203,0.06), rgba(91,141,239,0.03)); border: 1px solid rgba(64,113,203,0.1); border-radius: 12px; padding: 16px;">
                                <p style="margin: 0; font-size: 13px; color: #4a5568; line-height: 1.5;">
                                    <strong>Совет:</strong> следите за новыми поступлениями в каталоге. Похожие автомобили
                                    появляются регулярно.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="victory-stage" id="victoryStage">
                <canvas class="victory-canvas" id="confettiCanvas"></canvas>
                <div class="victory-car-showcase" id="victoryShowcase">
                    <div class="victory-car-glow"></div>
                    <div class="victory-car-frame" id="victoryFrame">
                        <button class="victory-close-btn" onclick="closeVictory()">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 6 6 18M6 6l12 12" />
                            </svg>
                        </button>
                        <img src="" alt="" class="victory-car-image" id="victoryCarImage">
                        <div class="victory-car-info">
                            <h3 id="victoryCarTitle"></h3>
                            <div class="win-price" id="victoryCarPrice"></div>
                            <div class="win-price-label">финальная ставка</div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>
            <script>
                (function() {
                    // ---------- 1. ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ (без изменений) ----------
                    window.getCsrfToken = function() {
                        return document.querySelector('meta[name="csrf-token"]')?.content || '';
                    };

                    window.ajaxRequest = function(action, params = {}) {
                        const body = new URLSearchParams({
                            action,
                            ...params
                        });
                        return fetch('{{ route('profile.action') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded',
                                    'X-CSRF-TOKEN': window.getCsrfToken()
                                },
                                body: body.toString()
                            })
                            .then(response => {
                                if (!response.ok) throw new Error('HTTP ' + response.status);
                                return response.json();
                            });
                    };

                    window.removeCardFromDom = function(dataId, gridId, emptyMessage) {
                        const element = document.querySelector(`[data-id="${dataId}"]`);
                        if (element) {
                            element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            element.style.opacity = '0';
                            element.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                element.remove();
                                const grid = document.getElementById(gridId);
                                if (grid) {
                                    const cards = grid.querySelectorAll('.offer-card');
                                    if (cards.length === 0 && emptyMessage) {
                                        grid.innerHTML =
                                            `<div class="text-center py-5"><p>${emptyMessage}</p></div>`;
                                    }
                                }
                            }, 300);
                            return true;
                        }
                        return false;
                    };

                    window.showSuccessModal = function(message) {
                        const modalEl = document.getElementById('successModal');
                        if (!modalEl) return;
                        document.getElementById('successModalText').textContent = message;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                        window.closeAllMenus?.();
                    };

                    window.closeAllMenus = function() {
                        document.querySelectorAll('.context-menu').forEach(menu => {
                            menu.style.display = 'none';
                            const btn = menu.closest('.card-top-right')?.querySelector('.btn-open-menu');
                            if (btn) btn.setAttribute('aria-expanded', 'false');
                        });
                    };

                    // ---------- 2. МОДАЛЬНЫЕ ОКНА ПОДТВЕРЖДЕНИЯ ----------
                    window.showDeleteConfigModal = function(configId, configName) {
                        const modalEl = document.getElementById('deleteConfigModal');
                        if (!modalEl) return;
                        document.getElementById('deleteConfigModalText').textContent =
                            `Вы действительно хотите удалить конфигурацию "${configName}"?`;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                        window.closeAllMenus();
                        window._currentDeleteConfigTarget = configId;
                    };

                    window.showDeleteFavoriteModal = function(favoriteId, carName) {
                        const modalEl = document.getElementById('deleteFavoriteModal');
                        if (!modalEl) return;
                        document.getElementById('deleteFavoriteModalText').textContent =
                            `Вы действительно хотите удалить автомобиль "${carName}" из избранного?`;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                        window.closeAllMenus();
                        window._currentDeleteFavoriteTarget = favoriteId;
                    };

                    window.showCancelAllBidsModal = function(auctionId, carName) {
                        const modalEl = document.getElementById('cancelBidModal');
                        if (!modalEl) return;
                        document.getElementById('cancelBidModalText').textContent =
                            `Вы действительно хотите отменить ВСЕ ставки на "${carName}"?`;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                        window.closeAllMenus();
                        window._currentCancelBidTarget = 'a' + auctionId;
                    };

                    window.showDeleteModal = function(elementId, elementType, elementName) {
                        const modalEl = document.getElementById('deleteModal');
                        if (!modalEl) return;
                        document.getElementById('deleteModalText').textContent =
                            `Вы действительно хотите удалить ${elementType} "${elementName}"?`;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                        window.closeAllMenus();
                        window._currentDeleteTarget = elementId;
                    };

                    window.showBidDetailsModal = function(auctionId) {
                        const modalEl = document.getElementById('infoModal');
                        if (!modalEl) return;
                        document.getElementById('infoModalContent').innerHTML = `
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Загрузка...</span>
                        </div>
                        <p class="mt-2">Загрузка информации о ставках...</p>
                    </div>`;
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();

                        window.ajaxRequest('get_bid_details', {
                                auction_id: auctionId
                            })
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('infoModalContent').innerHTML = data.html;
                                } else {
                                    document.getElementById('infoModalContent').innerHTML =
                                        `<div class="alert alert-danger"><strong>Ошибка:</strong> ${data.error || 'Неизвестная ошибка'}</div>`;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                document.getElementById('infoModalContent').innerHTML =
                                    `<div class="alert alert-danger"><strong>Ошибка сети:</strong> Не удалось загрузить информацию о ставках</div>`;
                            });
                        window.closeAllMenus();
                    };

                    window.openConfigurator = function(carId, engine, color, interior) {
                        let url = '{{ route('configurator.show', '') }}/' + carId;
                        const params = new URLSearchParams();
                        if (engine) params.append('engine', engine);
                        if (color) params.append('color', color);
                        if (interior) params.append('interior', interior);
                        const query = params.toString();
                        if (query) url += '?' + query;
                        window.location.href = url;
                        window.closeAllMenus?.();
                    };

                    // ---------- 3. АНИМАЦИЯ ПОБЕДЫ ----------
                                      window.startVictoryAnimation = function(auctionId) {
                        clearTimeout(window._victoryTimeout);

                        const card = document.querySelector(`[data-id="a${auctionId}"]`);
                        if (!card) return;

                        const claimModalEl = document.getElementById('claimInfoModal');
                        if (!claimModalEl) return;
                        
                        const notesBlock = document.getElementById('winnerNotesBlock');
                        const notesText = document.getElementById('winnerNotesText');
                        if (notesBlock && notesText) {
                            const notes = card.dataset.winnerNotes?.trim();
                            if (notes) {
                                notesText.textContent = notes;
                                notesBlock.style.display = 'block';
                            } else {
                                notesBlock.style.display = 'none';
                            }
                        }

                        const celebratedKey = 'celebrated_auctions';
                        let celebrated = [];
                        try {
                            celebrated = JSON.parse(localStorage.getItem(celebratedKey) || '[]');
                        } catch (e) {
                            celebrated = [];
                        }
                        const aid = String(auctionId);

                        if (celebrated.includes(aid)) {
                            const claimModal = bootstrap.Modal.getInstance(claimModalEl) || new bootstrap.Modal(claimModalEl);
                            claimModal.show();
                            return;
                        }

                        celebrated.push(aid);
                        localStorage.setItem(celebratedKey, JSON.stringify(celebrated));

                        const victoryStage = document.getElementById('victoryStage');
                        const victoryShowcase = document.getElementById('victoryShowcase');
                        if (!victoryStage) return;

                        const carData = {
                            title: card.querySelector('h3')?.textContent?.trim() || 'Автомобиль',
                            image: card.querySelector('.offer-image img')?.src || '',
                            price: card.querySelector('.price-text')?.textContent?.trim() || ''
                        };

                        victoryStage.classList.add('active');
                        document.body.style.overflow = 'hidden';
                        window.fireConfettiCelebration?.();

                        setTimeout(() => {
                            const img = document.getElementById('victoryCarImage');
                            if (img) {
                                img.src = carData.image;
                                img.alt = carData.title;
                                document.getElementById('victoryCarTitle').textContent = carData.title;
                                document.getElementById('victoryCarPrice').textContent = carData.price;
                            }
                            victoryShowcase?.classList.add('revealed');
                        }, 1200);

                        window._victoryTimeout = setTimeout(() => {
                            window.closeVictory?.();
                            setTimeout(() => {
                                const claimModal = bootstrap.Modal.getInstance(claimModalEl) || new bootstrap.Modal(claimModalEl);
                                claimModal.show();
                            }, 300);
                        }, 3500);
                    };

                    window.showLostAuctionModal = function(auctionId) {
                        const card = document.querySelector(`[data-id="a${auctionId}"]`);
                        if (!card) return;
                        const carTitle = card.querySelector('h3')?.textContent?.trim() || 'Автомобиль';
                        const userBid = card.querySelector('.price-text')?.textContent?.trim() || '—';
                        const finalPrice = card.dataset.finalPrice || 'По договорённости';
                        document.getElementById('lostCarTitle').textContent = carTitle;
                        document.getElementById('lostUserBid').textContent = userBid;
                        document.getElementById('lostFinalPrice').textContent = finalPrice;
                        const modalEl = document.getElementById('lostAuctionModal');
                        const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                        modal.show();
                    };

                    window.closeVictory = function() {
                        clearTimeout(window._victoryTimeout);
                        const victoryStage = document.getElementById('victoryStage');
                        const victoryShowcase = document.getElementById('victoryShowcase');
                        if (victoryStage) victoryStage.classList.remove('active');
                        if (victoryShowcase) victoryShowcase.classList.remove('revealed');
                        document.body.style.overflow = '';
                        if (window.myConfetti) window.myConfetti.reset();
                    };

                    window.fireConfettiCelebration = function() {
                        if (!window.myConfetti) return;
                        const colors = ['#4071CB', '#5B8DEF', '#FFD700', '#FF6B6B', '#4ECDC4', '#95E1D3'];
                        const end = Date.now() + 3000;

                        window.myConfetti({
                            particleCount: 150,
                            spread: 100,
                            origin: {
                                y: 0.6
                            },
                            colors,
                            startVelocity: 55,
                            gravity: 1.2,
                            scalar: 1.2,
                            shapes: ['square', 'circle']
                        });

                        setTimeout(() => {
                            window.myConfetti({
                                particleCount: 80,
                                angle: 60,
                                spread: 55,
                                origin: {
                                    x: 0,
                                    y: 0.65
                                },
                                colors,
                                startVelocity: 50
                            });
                            window.myConfetti({
                                particleCount: 80,
                                angle: 120,
                                spread: 55,
                                origin: {
                                    x: 1,
                                    y: 0.65
                                },
                                colors,
                                startVelocity: 50
                            });
                        }, 400);

                        setTimeout(() => {
                            window.myConfetti({
                                particleCount: 100,
                                spread: 120,
                                origin: {
                                    y: 0.3
                                },
                                colors,
                                startVelocity: 45,
                                gravity: 0.8,
                                drift: 0.5
                            });
                        }, 800);

                        const interval = setInterval(() => {
                            if (Date.now() > end) {
                                clearInterval(interval);
                                return;
                            }
                            window.myConfetti({
                                particleCount: 30,
                                spread: 120,
                                origin: {
                                    y: 0.7
                                },
                                colors,
                                startVelocity: 30,
                                gravity: 1.5
                            });
                        }, 400);
                    };

                    // ---------- 4. ОСНОВНАЯ ЛОГИКА ----------
                    document.addEventListener('DOMContentLoaded', () => {
                        // Инициализация конфетти
                        const confettiCanvas = document.getElementById('confettiCanvas');
                        if (confettiCanvas) {
                            window.myConfetti = confetti.create(confettiCanvas, {
                                resize: true,
                                useWorker: true
                            });
                        }

                        // ---------- ТАБЫ  ----------
                        const tabsWrap = document.querySelector('.tabs-wrap');
                        const tabsContainer = document.querySelector('.tabs');
                        if (tabsWrap && tabsContainer) {
                            const tabs = Array.from(tabsContainer.querySelectorAll('.tab:not(.tab-indicator)'));
                            const sections = Array.from(document.querySelectorAll('.tab-section'));
                            const indicator = tabsContainer.querySelector('.tab-indicator');

                            function updateIndicator(activeTab) {
                                if (!indicator || !activeTab || !tabsWrap) return;
                                const wrapRect = tabsWrap.getBoundingClientRect();
                                const tabRect = activeTab.getBoundingClientRect();
                                const left = tabRect.left - wrapRect.left;
                                const width = tabRect.width;
                                indicator.style.transform = `translateX(${left}px)`;
                                indicator.style.width = `${width}px`;
                            }

                            function switchToTab(targetTab) {
                                if (!targetTab) return;
                                tabs.forEach(tab => {
                                    tab.classList.remove('active');
                                    tab.setAttribute('aria-selected', 'false');
                                });
                                targetTab.classList.add('active');
                                targetTab.setAttribute('aria-selected', 'true');
                                sections.forEach(section => {
                                    section.style.display = 'none';
                                    section.classList.remove('active');
                                });
                                const targetSection = document.getElementById(targetTab.dataset.target);
                                if (targetSection) {
                                    targetSection.style.display = 'block';
                                    setTimeout(() => targetSection.classList.add('active'), 10);
                                }
                                requestAnimationFrame(() => updateIndicator(targetTab));
                            }

                            const activeTab = tabs.find(tab => tab.classList.contains('active')) || tabs[0];
                            if (activeTab) {
                                sections.forEach(section => section.style.display = 'none');
                                const activeSection = document.getElementById(activeTab.dataset.target);
                                if (activeSection) activeSection.style.display = 'block';
                                setTimeout(() => updateIndicator(activeTab), 50);
                            }

                            tabs.forEach(tab => {
                                tab.addEventListener('click', () => switchToTab(tab));
                            });

                            window.addEventListener('resize', () => {
                                const currentActive = tabs.find(tab => tab.classList.contains('active'));
                                if (currentActive) updateIndicator(currentActive);
                            });
                        }

                        // ---------- 4.2 КОНТЕКСТНЫЕ МЕНЮ ----------
                        document.addEventListener('click', function(e) {
                            const btn = e.target.closest('.btn-open-menu');
                            if (!btn) {
                                window.closeAllMenus();
                                return;
                            }

                            const cardTopRight = btn.closest('.card-top-right');
                            const menu = cardTopRight.querySelector('.context-menu');
                            window.closeAllMenus();

                            const isExpanded = btn.getAttribute('aria-expanded') === 'true';
                            if (!isExpanded) {
                                menu.style.display = 'block';
                                btn.setAttribute('aria-expanded', 'true');
                                const handleClickOutside = (ev) => {
                                    if (!cardTopRight.contains(ev.target)) {
                                        menu.style.display = 'none';
                                        btn.setAttribute('aria-expanded', 'false');
                                        window.removeEventListener('click', handleClickOutside);
                                    }
                                };
                                setTimeout(() => window.addEventListener('click', handleClickOutside), 0);
                            }
                        });

                        // ---------- 4.3 ОБРАБОТЧИКИ УДАЛЕНИЯ КОНФИГУРАЦИЙ, ИЗБРАННОГО, СТАВОК ----------
                        const confirmDeleteConfigBtn = document.getElementById('confirmDeleteConfigBtn');
                        if (confirmDeleteConfigBtn) {
                            confirmDeleteConfigBtn.addEventListener('click', function() {
                                const configId = window._currentDeleteConfigTarget;
                                if (!configId) return;
                                window._currentDeleteConfigTarget = null;

                                const modalEl = document.getElementById('deleteConfigModal');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();

                                window.ajaxRequest('delete_config', {
                                        config_id: configId
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            if (window.removeCardFromDom(`c${configId}`, 'offersGrid',
                                                    'У вас пока нет сохраненных конфигураций')) {
                                                window.showSuccessModal('Конфигурация удалена');
                                            }
                                        } else {
                                            alert('Ошибка: ' + (data.error ||
                                                'Не удалось удалить конфигурацию'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Произошла ошибка при удалении');
                                    });
                            });
                        }

                        const confirmDeleteFavoriteBtn = document.getElementById('confirmDeleteFavoriteBtn');
                        if (confirmDeleteFavoriteBtn) {
                            confirmDeleteFavoriteBtn.addEventListener('click', function() {
                                const favoriteId = window._currentDeleteFavoriteTarget;
                                if (!favoriteId) return;
                                window._currentDeleteFavoriteTarget = null;

                                const modalEl = document.getElementById('deleteFavoriteModal');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();

                                window.ajaxRequest('remove_favorite', {
                                        favorite_id: favoriteId
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            if (window.removeCardFromDom(`f${favoriteId}`, 'favoritesGrid',
                                                    'У вас пока нет избранных автомобилей')) {
                                                window.showSuccessModal('Автомобиль удален из избранного');
                                            }
                                        } else {
                                            alert('Ошибка: ' + (data.error ||
                                                'Не удалось удалить из избранного'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Произошла ошибка при удалении');
                                    });
                            });
                        }

                        const confirmCancelBidBtn = document.getElementById('confirmCancelBidBtn');
                        if (confirmCancelBidBtn) {
                            confirmCancelBidBtn.addEventListener('click', function() {
                                const target = window._currentCancelBidTarget;
                                if (!target || !target.startsWith('a')) return;
                                const auctionId = target.substring(1);
                                window._currentCancelBidTarget = null;

                                const modalEl = document.getElementById('cancelBidModal');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();

                                window.ajaxRequest('cancel_all_bids', {
                                        auction_id: auctionId
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            if (window.removeCardFromDom(`a${auctionId}`, 'bidsGrid',
                                                    'У вас пока нет активных ставок')) {
                                                window.showSuccessModal('Все ставки успешно отменены');
                                            }
                                        } else {
                                            alert('Ошибка: ' + (data.error ||
                                            'Не удалось отменить ставки'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Произошла ошибка при отмене ставок');
                                    });
                            });
                        }

                        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
                        if (confirmDeleteBtn) {
                            confirmDeleteBtn.addEventListener('click', function() {
                                const targetId = window._currentDeleteTarget;
                                if (!targetId) return;
                                window._currentDeleteTarget = null;

                                const modalEl = document.getElementById('deleteModal');
                                const modal = bootstrap.Modal.getInstance(modalEl);
                                if (modal) modal.hide();

                                const element = document.querySelector(`[data-id="${targetId}"]`);
                                if (!element) return;

                                const type = element.dataset.type;
                                let action, paramKey, paramValue, gridId, emptyMsg;

                                if (type === 'config') {
                                    action = 'delete_config';
                                    paramKey = 'config_id';
                                    paramValue = targetId.replace('c', '');
                                    gridId = 'offersGrid';
                                    emptyMsg = 'У вас пока нет сохраненных конфигураций';
                                } else if (type === 'favorite') {
                                    action = 'remove_favorite';
                                    paramKey = 'favorite_id';
                                    paramValue = targetId.replace('f', '');
                                    gridId = 'favoritesGrid';
                                    emptyMsg = 'У вас пока нет избранных автомобилей';
                                } else {
                                    return;
                                }

                                window.ajaxRequest(action, {
                                        [paramKey]: paramValue
                                    })
                                    .then(data => {
                                        if (data.success) {
                                            if (window.removeCardFromDom(targetId, gridId, emptyMsg)) {
                                                window.showSuccessModal('Элемент успешно удален');
                                            }
                                        } else {
                                            alert('Ошибка: ' + (data.error || 'Не удалось удалить'));
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Произошла ошибка при удалении');
                                    });
                            });
                        }

                        // ---------- 4.4 ЗАКРЫТИЕ VICTORY ПО ESC И КЛИКУ НА ОВЕРЛЕЙ ----------
                        document.addEventListener('keydown', (e) => {
                            if (e.key === 'Escape') {
                                const victoryStage = document.getElementById('victoryStage');
                                if (victoryStage?.classList.contains('active')) {
                                    window.closeVictory();
                                }
                            }
                        });

                        const victoryStage = document.getElementById('victoryStage');
                        if (victoryStage) {
                            victoryStage.addEventListener('click', (e) => {
                                if (e.target === victoryStage) {
                                    window.closeVictory();
                                }
                            });
                        }

                        setTimeout(() => {
                            document.querySelectorAll('.profile-message').forEach(el => {
                                el.style.transition = 'opacity 0.5s';
                                el.style.opacity = '0';
                                setTimeout(() => el.remove(), 500);
                            });
                        }, 5000);
                    });
                })();
            </script>
        @endpush
