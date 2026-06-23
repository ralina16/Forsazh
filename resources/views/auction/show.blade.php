@extends('layouts.app')

@section('title', $car->model . ' — аукцион')



@section('content')

    <div class="container-xxl">
        @if (session('success'))
            <div class="alert alert-success alert-fixed alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-fixed alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Модальное окно подтверждения ставки -->
    <div class="modal fade" id="confirmBidModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
            <div class="modal-content modal-custom" style="padding: 24px;">
                <div class="text-center">
                    <div class="modal-icon success">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                stroke="#22c55e" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M8 12L11 15L16 9" stroke="#22c55e" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="mb-2" style="font-weight: 600; color: #111;">Подтверждение ставки</h5>
                    <p class="mb-2" style="color: var(--muted); font-size: 15px;">Вы действительно хотите сделать ставку?
                    </p>
                    <div class="bid-amount-highlight" id="confirmBidAmount">{{ number_format($nextBidAmount, 0, '', ' ') }}
                        ₽</div>
                    <div class="d-flex gap-2" style="justify-content: center; margin-top: 20px;">
                        <button type="button" class="btn btn-sm"
                            style="flex:1; background:transparent; border:1px solid rgba(0,0,0,0.08); border-radius:10px; font-weight:600; color:var(--muted); padding: 10px 0;"
                            data-bs-dismiss="modal">Отмена</button>
                        <button id="confirmBidBtn" type="button" class="btn btn-sm"
                            style="flex:1; background:var(--accent); color:white; border:none; border-radius:10px; font-weight:600; padding: 10px 0;">Да,
                            ставлю!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно отмены ставки -->
    <div class="modal fade" id="cancelBidModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
            <div class="modal-content modal-custom" style="padding: 24px;">
                <div class="text-center">
                    <div class="modal-icon warning">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 9V13M12 17H12.01M5.07183 19H18.9282C20.4678 19 21.4301 17.3333 20.6603 16L13.7321 4C12.9623 2.66667 11.0378 2.66667 10.268 4L3.33978 16C2.56997 17.3333 3.53223 19 5.07183 19Z"
                                stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="mb-2" style="font-weight: 600; color: #111;">Отмена ставки</h5>
                    <p id="cancelBidModalText" class="mb-4" style="color: var(--muted); font-size: 15px;">Вы действительно
                        хотите отменить свою ставку?</p>
                    <div class="d-flex gap-2" style="justify-content: center;">
                        <button type="button" class="btn btn-sm"
                            style="flex:1; background:transparent; border:1px solid rgba(0,0,0,0.08); border-radius:10px; font-weight:600; color:var(--muted); padding: 10px 0;"
                            data-bs-dismiss="modal">Нет, оставить</button>
                        <button id="confirmCancelBidBtn" type="button" class="btn btn-sm"
                            style="flex:1; background:var(--accent); color:white; border:none; border-radius:10px; font-weight:600; padding: 10px 0;">Да,
                            отменить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5 px-4">
        <nav class="breadcrumb">
            <a href="{{ route('auction.index') }}" class="breadcrumb-item">Главная</a>
            <a href="{{ route('auction.index') }}" class="breadcrumb-item">Каталог</a>
            <span class="breadcrumb-item active">{{ $car->model }}</span>
        </nav>

        <h1 class="page-title my-5">{{ $car->model }}</h1>

        @php
            $allPhotos = [];
            if ($car->photo) {
                $allPhotos[] = $car->photo;
            }
            if ($car->additional_photos && is_array($car->additional_photos)) {
                $allPhotos = array_merge($allPhotos, $car->additional_photos);
            }

            $photosCount = count($allPhotos);
            $hasMultiplePhotos = $photosCount > 1;

            $mileageNum = (int) preg_replace('/[^0-9]/', '', $car->mileage);
            $ownersNum = (int) preg_replace('/[^0-9]/', '', $car->owners);
            $transmissionsNum = (int) preg_replace('/[^0-9]/', '', $car->transmissions);
            $trunkNum = (int) preg_replace('/[^0-9]/', '', $car->trunk);
            $engineNum = preg_replace('/[^0-9\.]/', '', $car->engine);

            $now = now();
            if ($auction->status === 'ended' || $now > $auction->end_date) {
                $auctionStatusText = 'ended';
            } elseif ($now < $auction->start_date) {
                $auctionStatusText = 'upcoming';
            } else {
                $auctionStatusText = 'active';
            }

            $currentMaxBid = $auction->bids()->orderBy('amount', 'desc')->first();
            $currentPrice = $currentMaxBid ? $currentMaxBid->amount : $auction->starting_price;
            $bidCount = $auction->bids()->count();
            $bidStep = 150000;
            $nextBidAmount = $currentPrice + $bidStep;
            $onePercentFee = $auction->starting_price / 100;

            $userPaid = \App\Models\Payment::where('user_id', Auth::id())
                ->where('auction_id', $auction->id)
                ->where('status', 'succeeded')
                ->exists();

            $userHasActiveBids = false;
            $isUserCurrentMaxBidder = false;
            $userCurrentBid = null;

            if (Auth::check()) {
                $userCurrentBid = $auction->bids()->where('user_id', Auth::id())->latest()->first();
                $userHasActiveBids = !is_null($userCurrentBid);
                $isUserCurrentMaxBidder =
                    $userCurrentBid && $currentMaxBid && $userCurrentBid->id == $currentMaxBid->id;
            }

            $bidTitle = $currentMaxBid ? 'Текущая ставка' : 'Начальная ставка';
            $displayCurrentBid = $currentPrice;
        @endphp

        <div class="photo-wrap">
            <div class="photo-inner {{ $hasMultiplePhotos ? 'has-thumbs' : 'no-thumbs' }}">
                @if ($hasMultiplePhotos)
                    <div class="thumbs-col">
                        <div class="thumbs" role="list">
                            @foreach (array_slice($allPhotos, 1) as $index => $photoPath)
                                <img src="{{ asset('storage/' . $photoPath) }}" alt="Фото {{ $index + 2 }}"
                                    data-large="{{ asset('storage/' . $photoPath) }}" class="img-fluid thumb-image"
                                    onerror="this.src='{{ asset('assets/images/default-car.jpg') }}'"
                                    {{ $index === 0 ? 'style="outline:3px solid rgba(47,109,213,0.18)"' : '' }}>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="main-photo position-relative">
                    @if ($photosCount > 0)
                        <img id="mainPhoto" src="{{ asset('storage/' . $allPhotos[0]) }}" alt="{{ $car->model }}"
                            onerror="this.src='{{ asset('assets/images/default-car.jpg') }}'">
                    @else
                        <div class="photo-placeholder">Фото автомобиля отсутствует</div>
                    @endif

                    @if ($hasMultiplePhotos)
                        <div class="slider-controls">
                            <button class="arrow left">
                                <img src="{{ asset('assets/images/one_car/left.svg') }}" alt="Предыдущее">
                            </button>
                            <div class="divider"></div>
                            <button class="arrow right">
                                <img src="{{ asset('assets/images/one_car/right.svg') }}" alt="Следующее">
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row content-row gx-4 equal-height" style="margin-top:22px;">
            <div class="col-lg-12 d-flex">
                <div class="right-area w-100">
                    <div class="info-card">
                        <div>
                            <div class="info-title">{{ $car->model }}</div>
                            <div class="info-list">
                                <p><span class="label">Привод:</span><span
                                        class="value">{{ $car->drive ?? 'Не указано' }}</span></p>
                                <p><span class="label">Объем двигателя:</span><span class="value">{{ $engineNum }}
                                        L</span></p>
                                <p><span class="label">Тип топлива:</span><span
                                        class="value">{{ $car->fuel }}</span></p>
                                <p><span class="label">Пробег:</span><span
                                        class="value">{{ number_format($mileageNum, 0, '', ' ') }} км</span></p>
                                <p><span class="label">Количество владельцев:</span><span
                                        class="value">{{ $ownersNum }}</span></p>
                                <p><span class="label">Состояние:</span><span
                                        class="value">{{ $car->condition }}</span></p>
                                <p><span class="label">Количество передач:</span><span
                                        class="value">{{ $transmissionsNum }}</span></p>
                                <p><span class="label">Объем багажника:</span><span class="value">{{ $trunkNum }}
                                        L</span></p>
                                <p><span class="label">Коробка передач:</span><span
                                        class="value">{{ $car->gearbox }}</span></p>
                                <p><span class="label">Тип кузова:</span><span class="value">{{ $car->body }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ($auction)
                        @if (!$userPaid && $auctionStatusText !== 'ended')
                            <div id="payBlock" class="auction-card">
                                <div class="auction-top">
                                    <img src="{{ asset('assets/images/one_car/time.svg') }}" alt="time"
                                        style="width:28px;height:28px;opacity:.9;">
                                    <div class="auction-countdown" id="auctionCountdown">
                                        @if ($auctionStatusText === 'upcoming')
                                            До начала аукциона: <span id="countdownTimer"></span>
                                        @elseif($auctionStatusText === 'active')
                                            До конца аукциона: <span id="countdownTimer"></span>
                                        @endif
                                    </div>
                                </div>

                                <div class="auc-text d-flex flex-column align-items-center mt-5">
                                    <div class="auction-note">Чтобы участвовать - оплатите</div>
                                    <div class="auction-sub d-flex gap-3 align-items-center">
                                        <span class="auction-big">1%</span>От стартовой цены авто
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <div class="auction-text">Взнос за участие в аукционе</div>

                                    <div style="margin-top:18px;">
                                        <form method="POST" action="{{ route('auction.payment', $auction) }}"
                                            id="paymentForm">
                                            @csrf
                                            <button class="auction-cta w-100" type="submit" id="payButton">
                                                <span
                                                    class="auction-price">{{ number_format($onePercentFee, 0, '', ' ') }}
                                                    ₽</span>
                                                <span class="auction-pay" aria-hidden="true">Оплатить взнос через
                                                    ЮКассу</span>
                                            </button>
                                        </form>

                                        <div id="paymentLoading" class="payment-loading"
                                            style="display: none !important;">
                                            <div class="spinner-border text-primary mb-2" role="status"
                                                style="width: 28px; height: 28px;"></div>
                                            <p style="margin: 8px 0 0 0; font-size: 14px; color: #555;">Перенаправление на
                                                страницу оплаты...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($userPaid && $auctionStatusText !== 'ended')
                            <div id="biddingBlock" class="auction-card">
                                <div class="auction-top">
                                    <img src="{{ asset('assets/images/one_car/time.svg') }}" alt="time"
                                        style="width:28px;height:28px;opacity:.9;">
                                    <div class="auction-countdown bidding-countdown">
                                        @if ($auctionStatusText === 'upcoming')
                                            До начала аукциона: <span id="biddingCountdownTimer"></span>
                                        @else
                                            До конца аукциона: <span id="biddingCountdownTimer"></span>
                                        @endif
                                    </div>
                                </div>

                                <div style="margin:40px 0 30px; text-align:center;">
                                    <div style="font-size:14px; color:#8a8a8a; text-transform:uppercase;">
                                        {{ $bidTitle }}</div>
                                    <div style="font-size:42px; font-weight:800; margin:12px 0;">
                                        {{ number_format($displayCurrentBid, 0, '', ' ') }} ₽</div>
                                    <div style="font-size:14px; color:#8a8a8a; text-transform:uppercase;">Количество
                                        ставок: {{ $bidCount }}</div>

                                    @if ($currentMaxBid)
                                        <div class="current-bidder-info">
                                            @if ($isUserCurrentMaxBidder)
                                                Текущая ставка - ваша
                                            @else
                                                Текущая ставка от другого участника
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                @if ($userHasActiveBids && $isUserCurrentMaxBidder)
                                    <div class="cancel-bid-section">
                                        <div style="text-align:center; margin-bottom:15px; color:#6c6c6c;">
                                            У вас есть активная ставка
                                        </div>
                                        <form method="POST" action="{{ route('auction.bid.destroy', $userCurrentBid) }}"
                                            id="cancelBidForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-outline-danger w-100"
                                                data-bs-toggle="modal" data-bs-target="#cancelBidModal">
                                                Отменить свою ставку
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="mt-5">
                                        <div
                                            style="text-align:center; margin-bottom:18px; color:#6c6c6c; text-transform:uppercase;">
                                            Каждый шаг 150 000 ₽
                                        </div>

                                        @if ($isUserCurrentMaxBidder)
                                            <div class="alert alert-info text-center">
                                                <strong>Вы лидируете!</strong> Ждите, пока другие участники сделают ставки.
                                            </div>
                                        @else
                                            <form method="POST" action="{{ route('auction.bid.store', $auction) }}"
                                                id="bidForm">
                                                @csrf
                                                <input type="hidden" name="amount" id="bidAmountInput"
                                                    value="{{ $nextBidAmount }}">
                                                <button id="makeBidBtn" class="auction-cta w-100" type="button"
                                                    {{ $auctionStatusText !== 'active' ? 'disabled' : '' }}>
                                                    @if ($userHasActiveBids)
                                                        ПЕРЕБИТЬ СТАВКУ
                                                    @else
                                                        СДЕЛАТЬ СТАВКУ
                                                    @endif
                                                </button>
                                            </form>

                                            @if ($userHasActiveBids && !$isUserCurrentMaxBidder)
                                                <div class="mt-3 text-center">
                                                    <small class="text-muted">
                                                        Ваша текущая ставка:
                                                        <strong>{{ number_format($userCurrentBid->amount ?? 0, 0, '', ' ') }}
                                                            ₽</strong>
                                                    </small>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if ($auctionStatusText === 'ended')
                            <div class="auction-card">
                                <div class="auction-top">
                                    <img src="{{ asset('assets/images/one_car/time.svg') }}" alt="time"
                                        style="width:28px;height:28px;opacity:.9;">
                                    <div class="auction-countdown">Аукцион завершен</div>
                                </div>
                                <div style="margin:40px 0 30px; text-align:center;">
                                    <div style="font-size:14px; color:#8a8a8a; text-transform:uppercase;">Итоговая цена
                                    </div>
                                    <div style="font-size:42px; font-weight:800; margin:12px 0;">
                                        {{ number_format($displayCurrentBid, 0, '', ' ') }} ₽</div>
                                    <div style="font-size:14px; color:#8a8a8a; text-transform:uppercase;">Всего ставок:
                                        {{ $bidCount }}</div>
                                </div>
                            </div>
                        @endif
                    @else
                        <p>Для этого автомобиля пока нет аукциона.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Фото-галерея
            const mainPhoto = document.getElementById('mainPhoto');
            const thumbs = document.querySelectorAll('.thumb-image');

            if (thumbs.length && mainPhoto && thumbs.length > 0) {
                thumbs.forEach(thumb => {
                    thumb.addEventListener('click', function() {
                        const largeSrc = this.getAttribute('data-large') || this.src;
                        if (mainPhoto) mainPhoto.src = largeSrc;
                        thumbs.forEach(t => t.style.outline = 'none');
                        this.style.outline = '3px solid rgba(47,109,213,0.18)';
                    });
                });
            }

            // Слайдер
            const prevBtn = document.querySelector('.slider-controls .arrow.left');
            const nextBtn = document.querySelector('.slider-controls .arrow.right');
            const thumbsImages = document.querySelectorAll('.thumb-image');

            if (prevBtn && nextBtn && thumbsImages.length > 0) {
                let currentIndex = 0;

                function updatePhoto(index) {
                    if (!thumbsImages.length) return;
                    currentIndex = index;
                    const src = thumbsImages[currentIndex].getAttribute('data-large') || thumbsImages[currentIndex]
                        .src;
                    if (mainPhoto) mainPhoto.src = src;
                    thumbsImages.forEach(t => t.style.outline = 'none');
                    thumbsImages[currentIndex].style.outline = '3px solid rgba(47,109,213,0.18)';
                }

                prevBtn.addEventListener('click', () => {
                    let newIndex = currentIndex - 1;
                    if (newIndex < 0) newIndex = thumbsImages.length - 1;
                    updatePhoto(newIndex);
                });

                nextBtn.addEventListener('click', () => {
                    updatePhoto((currentIndex + 1) % thumbsImages.length);
                });

                updatePhoto(0);
            }

            // Обработчик формы оплаты
            const paymentForm = document.getElementById('paymentForm');
            const payButton = document.getElementById('payButton');
            const paymentLoading = document.getElementById('paymentLoading');

            if (paymentForm && payButton) {
                paymentForm.addEventListener('submit', function(e) {
                    payButton.style.display = 'none';
                    if (paymentLoading) paymentLoading.style.display = 'block';
                });
            }

            // Таймер
            @if ($auction)
                const startTime = new Date("{{ $auction->start_date }}").getTime();
                const endTime = new Date("{{ $auction->end_date }}").getTime();

                function updateCountdownCompact(targetTime, elementId) {
                    const now = new Date().getTime();
                    const distance = targetTime - now;

                    if (distance < 0) {
                        const element = document.getElementById(elementId);
                        if (element) element.textContent = "Завершен";
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    let timeString;
                    if (days > 0) {
                        timeString = `${days}д ${hours}ч ${minutes}м ${seconds}с`;
                    } else if (hours > 0) {
                        timeString = `${hours}ч ${minutes}м ${seconds}с`;
                    } else {
                        timeString = `${minutes}м ${seconds}с`;
                    }

                    const element = document.getElementById(elementId);
                    if (element) element.textContent = timeString;
                }

                setInterval(() => {
                    const now = new Date().getTime();

                    if (now < startTime) {
                        updateCountdownCompact(startTime, 'countdownTimer');
                        updateCountdownCompact(startTime, 'biddingCountdownTimer');
                    } else if (now < endTime) {
                        updateCountdownCompact(endTime, 'countdownTimer');
                        updateCountdownCompact(endTime, 'biddingCountdownTimer');
                    } else {
                        document.querySelectorAll('#countdownTimer, #biddingCountdownTimer').forEach(el => {
                            if (el) el.textContent = "Завершен";
                        });
                    }
                }, 1000);
            @endif

            // Подтверждение ставки
            const makeBidBtn = document.getElementById('makeBidBtn');
            const confirmBidBtn = document.getElementById('confirmBidBtn');
            const confirmBidModal = new bootstrap.Modal(document.getElementById('confirmBidModal'));

            if (makeBidBtn && confirmBidBtn) {
                makeBidBtn.addEventListener('click', function() {
                    const amount = document.getElementById('bidAmountInput')?.value;
                    if (amount) {
                        document.getElementById('confirmBidAmount').textContent = parseInt(amount)
                            .toLocaleString('ru-RU') + ' ₽';
                    }
                    confirmBidModal.show();
                });

                confirmBidBtn.addEventListener('click', function() {
                    document.getElementById('bidForm').submit();
                });
            }

            // Отмена ставки
            const confirmCancelBtn = document.getElementById('confirmCancelBidBtn');
            const cancelBidModal = new bootstrap.Modal(document.getElementById('cancelBidModal'));

            if (confirmCancelBtn) {
                confirmCancelBtn.addEventListener('click', function() {
                    document.getElementById('cancelBidForm').submit();
                });
            }
        });
    </script>
@endpush
