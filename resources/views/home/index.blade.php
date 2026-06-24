@extends('layouts.app')

@section('title', 'ФОРСАЖ - Автосалон')
@section('body-class', 'body-index')
@section('body-id', 'main')

@section('content')

    {{-- HEADER --}}
    @include('partials.header-dark')

    @include('partials.modal-review-full')

    {{-- HERO --}}
    <section class="hero" id="hero">
        <div class="hero-inner container-xxl">
            <div class="title-back" data-aos="fade-up" data-aos-delay="100">ФОРСАЖ</div>

            <div class="hero-slider" data-aos="fade-up" data-aos-delay="200">
                <div class="hero-slides">
                    <div class="hero-slide active">
                        <img src="{{ asset('assets/images/banner/car.png') }}" alt="BMW X7" class="hero-img">
                    </div>
                    <div class="hero-slide">
                        <img src="{{ asset('assets/images/banner/car2.png') }}" alt="Audi Q8" class="hero-img">
                    </div>
                </div>
            </div>

            <div class="title-front" data-aos="fade-up" data-aos-delay="100">
                ФОРСАЖ
                <span class="subtitle" data-aos="fade-up" data-aos-delay="200">Автосалон</span>
            </div>

            <!-- Точки справа -->
            <div class="hero-dots">
                <button type="button" class="hero-dot active" data-slide="0" aria-label="Слайд 1"></button>
                <button type="button" class="hero-dot" data-slide="1" aria-label="Слайд 2"></button>
            </div>
        </div>
    </section>


    <section class="after-hero">
        <div class="pill">LET'S GO</div>
        <div class="container-xxl">
            <div class="car-block" data-aos="fade-up" data-aos-duration="500">
                <h2 class="section-title">ВЫБЕРИТЕ СВОЙ АВТОМОБИЛЬ</h2>
                <div class="tabs_index">
                    <button class="tab-btn active" data-tab="body">КУЗОВ</button>
                    <button class="tab-btn" data-tab="brand">МАРКА</button>
                </div>
                <div id="body" class="tab-content">
                    <div class="cars">
                        <a href="{{ route('catalog.index') }}?body=кроссовер" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-1.png') }}" alt="Кроссовер">
                                <p>Кроссовер</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?body=седан" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-2.png') }}" alt="Седан">
                                <p>Седан</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?body=универсал" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-3.png') }}" alt="Универсал">
                                <p>Универсал</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?body=хэтчбек" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-4.png') }}" alt="Хэтчбек">
                                <p>Хэтчбек</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?body=пикап" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-5.png') }}" alt="Пикап">
                                <p>Пикап</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?body=кабриолет" class="car-type-link">
                            <div>
                                <img src="{{ asset('assets/images/car/image-6.png') }}" alt="Кабриолет">
                                <p>Кабриолет</p>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex align-items-center space-center">
                        <a href="{{ route('catalog.index') }}" class="show-all">Показать все</a>
                    </div>
                </div>
                <div id="brand" class="tab-content" style="display:none">
                    <div class="cars">
                        <a href="{{ route('catalog.index') }}?brand=bmw" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/1.png') }}" alt="BMW" class="marks">
                                <p>BMW</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?brand=audi" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/2.png') }}" alt="Audi" class="marks">
                                <p>Audi</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?brand=mercedes" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/3.png') }}" alt="Mercedes" class="marks">
                                <p>Mercedes</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?brand=toyota" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/4.png') }}" alt="Toyota" class="marks">
                                <p>Toyota</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?brand=kia" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/5.png') }}" alt="Kia" class="marks">
                                <p>Kia</p>
                            </div>
                        </a>
                        <a href="{{ route('catalog.index') }}?brand=subaru" class="car-brand-link">
                            <div>
                                <img src="{{ asset('assets/images/marks/6.png') }}" alt="Subaru" class="marks">
                                <p>Subaru</p>
                            </div>
                        </a>
                    </div>
                    <div class="d-flex align-items-center space-center">
                        <a href="{{ route('catalog.index') }}" class="show-all">Показать все</a>
                    </div>
                </div>
            </div>



            {{-- FEATURES --}}
            <style>
                .container-xxl {
                    --bs-gutter-x: 0;
                }

                @media (max-width: 1199px) {
                    .col-lg-6:first-child .feature-card img {
                        height: 400px;
                    }

                    .col-lg-6:last-child .feature-card.small img {
                        height: 190px;
                    }
                }

                @media (max-width: 991px) {
                    .col-lg-6:first-child .feature-card img {
                        height: 300px;
                    }

                    .col-lg-6:last-child .feature-card.small img {
                        height: 240px;
                    }

                    .feature-content {
                        padding: 20px;
                    }

                    .btn-more {
                        padding: 10px 16px 6px 16px;
                        font-size: 13px;
                    }
                }

                @media (max-width: 767px) {
                    .col-lg-6:first-child .feature-card img {
                        height: 250px;
                    }

                    .col-lg-6:last-child .feature-card.small img {
                        height: 200px;
                    }

                    .feature-content {
                        padding: 16px;
                    }

                    .title-back {
                        left: 50px;
                    }

                    .title-front {
                        left: 50px;
                    }
                }

                @media (max-width: 575px) {
                    .col-lg-6:first-child .feature-card img {
                        height: 250px;
                    }

                    .col-lg-6:last-child .feature-card.small img {
                        height: 170px;
                    }

                    .feature-content {
                        padding: 12px;
                    }

                    .btn-more {
                        padding: 8px 12px 4px 12px;
                        font-size: 12px;
                    }

                    .cars>div>img {
                        max-width: 130px;
                    }

                    .cars {
                        gap: 40px;
                    }

                    .title-back {
                        left: 20px;
                    }

                    .title-front {
                        left: 20px;
                    }
                }
            </style>
            <section class="features my-100" data-aos="fade-up" data-aos-delay="500">
                <div class="container-xxl">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="feature-card">
                                <img src="{{ asset('assets/images/features/features-1.jpg') }}" alt="Конфигурация">
                                <div class="feature-content">
                                    <h3>ПРОСМОТР<br>КОНФИГУРАЦИЙ</h3>
                                    <a href="{{ route('configurator.index') }}" class="btn-more">ПОДРОБНЕЕ</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 d-flex flex-column gap-4">
                            <div class="feature-card small">
                                <img src="{{ asset('assets/images/features/features-2.jpg') }}" alt="Trade-in">
                                <div class="feature-content">
                                    <h3>АВТОМОБИЛЬ<br>В TRADE-IN</h3>
                                    <a href="{{ route('trade-in') }}" class="btn-more mb-0">ПОДРОБНЕЕ</a>
                                </div>
                            </div>
                            <div class="feature-card small">
                                <img src="{{ asset('assets/images/features/features-3.jpg') }}" alt="Сервис">
                                <div class="feature-content">
                                    <h3>ЗАПИСЬ НА<br>СЕРВИС</h3>
                                    <a href="#" class="btn-more" data-bs-toggle="modal"
                                        data-bs-target="#requestModal">ПОДРОБНЕЕ</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- HOT OFFERS --}}
            <section class="hot-offers container-xxl" data-aos="fade-up" data-aos-delay="500">
                <h2 class="section-title my-100">ГОРЯЧИЕ ПРЕДЛОЖЕНИЯ</h2>
                <div class="offers-grid">
                    @forelse($hotCars as $car)
                        @php
                            $badgeClass = $car->condition === 'Новая' ? 'badge-new' : 'badge-used';
                            $badgeText = $car->condition === 'Новая' ? 'NEW' : 'USED';
                            $formattedPrice = number_format($car->price, 0, '', ' ');
                            $mileageText = $car->mileage ? number_format($car->mileage, 0, '', ' ') . ' км' : 'Новый';
                            $photoPath = $car->catalog_photo ?: $car->photo ?: 'assets/images/offer/default.jpg';
                            $engineText = $car->engine ? $car->engine . ' L' : '';
                            $spec3Text = $car->mileage ? $mileageText : ($car->body ?: '');
                            $modelName = htmlspecialchars($car->model);
                            $displayModel =
                                mb_strlen($modelName) > 20 ? mb_substr($modelName, 0, 20) . '...' : $modelName;
                        @endphp
                        <div class="offer-card">
                            <div class="offer-header mb-0">
                                <h3 class="mb-0 offer-title" title="{{ $car->model }}">
                                    {{ $car->display_model }}
                                </h3>
                                <span class="{{ $car->badge_class }}">• {{ $car->badge_text }}</span>
                            </div>

                            @if ($car->gearbox)
                                <p class="year d-flex align-items-start">{{ $car->gearbox }}</p>
                            @endif

                            <div class="offer-image">
                                <img src="{{ $car->catalog_photo_url }}" alt="{{ $car->model }}"
                                    onerror="this.src='{{ asset('assets/images/offer/1.jpg') }}'">
                                <span class="brand-watermark">{{ strtoupper($car->brand_name) }}</span>
                            </div>

                            <div class="offer-specs text-uppercase">
                                @if ($car->engine)
                                    <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt="">
                                        {{ $car->engine }} L</span>
                                @endif
                                @if ($car->drive)
                                    <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                        {{ $car->drive }}</span>
                                @endif
                                @if (!$car->is_new && $car->mileage)
                                    <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                        {{ number_format($car->mileage, 0, ',', ' ') }} км</span>
                                @elseif($car->body)
                                    <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                        {{ $car->body }}</span>
                                @endif
                            </div>

                            <div class="offer-price">
                                <span class="price-text">{{ $car->formatted_price }}</span>
                                @auth
                                    <a href="{{ route('catalog.show', $car) }}" class="more-text">ПОДРОБНЕЕ</a>
                                @else
                                    <a href="{{ route('catalog.index') }}" class="more-text">ПОДРОБНЕЕ</a>
                                @endauth
                            </div>
                        </div>


                    @empty
                        {{-- Fallback  --}}
                        <div class="offer-card">
                            <div class="offer-header mb-0">
                                <h3 class="mb-0">Lexus LX LX500d</h3>
                                <span class="badge-used">• USED</span>
                            </div>
                            <p class="year d-flex align-items-start">Автомат</p>
                            <div class="offer-image">
                                <img src="{{ asset('assets/images/offer/1.png') }}" alt="Lexus LX500d">
                                <span class="brand-watermark">LEXUS</span>
                            </div>
                            <div class="offer-specs">
                                <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt=""> 5.7
                                    L</span>
                                <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                    ПОЛНЫЙ</span>
                                <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt=""> 60 000
                                    км</span>
                            </div>
                            <div class="offer-price">
                                <span class="price-text">1 000 000 РУБ</span>
                                <a href="{{ route('catalog.index') }}" class="more-text">СМОТРЕТЬ ВСЕ</a>
                            </div>
                        </div>
                        <div class="offer-card">
                            <div class="offer-header mb-0">
                                <h3 class="mb-0">Mitsubishi Pajero Sport</h3>
                                <span class="badge-new">• NEW</span>
                            </div>
                            <p class="year d-flex align-items-start">Автомат</p>
                            <div class="offer-image">
                                <img src="{{ asset('assets/images/offer/2.png') }}" alt="Mitsubishi Pajero Sport">
                                <span class="brand-watermark">MITSUBISHI</span>
                            </div>
                            <div class="offer-specs">
                                <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt=""> 5.7
                                    L</span>
                                <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                    ПОЛНЫЙ</span>
                                <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                    КРОССОВЕР</span>
                            </div>
                            <div class="offer-price">
                                <span class="price-text">1 000 000 РУБ</span>
                                <a href="{{ route('catalog.index') }}" class="more-text">СМОТРЕТЬ ВСЕ</a>
                            </div>
                        </div>
                        <div class="offer-card">
                            <div class="offer-header mb-0">
                                <h3 class="mb-0">Lexus UX UX200 Luxury</h3>
                                <span class="badge-new">• NEW</span>
                            </div>
                            <p class="year d-flex align-items-start">Механика</p>
                            <div class="offer-image">
                                <img src="{{ asset('assets/images/offer/3.png') }}" alt="Lexus UX UX200 Luxury">
                                <span class="brand-watermark">LEXUS</span>
                            </div>
                            <div class="offer-specs">
                                <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt=""> 5.7
                                    L</span>
                                <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                    ПЕРЕДНИЙ</span>
                                <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                    КРОССОВЕР</span>
                            </div>
                            <div class="offer-price">
                                <span class="price-text">1 000 000 РУБ</span>
                                <a href="{{ route('catalog.index') }}" class="more-text">СМОТРЕТЬ ВСЕ</a>
                            </div>
                        </div>
                        <div class="offer-card">
                            <div class="offer-header mb-0">
                                <h3 class="mb-0">Mercedes-Benz GLE</h3>
                                <span class="badge-new">• NEW</span>
                            </div>
                            <p class="year d-flex align-items-start">Автомат</p>
                            <div class="offer-image">
                                <img src="{{ asset('assets/images/offer/4.png') }}" alt="Mercedes GLE">
                                <span class="brand-watermark">MERCEDES</span>
                            </div>
                            <div class="offer-specs">
                                <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt=""> 5.7
                                    L</span>
                                <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt="">
                                    ПОЛНЫЙ</span>
                                <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt="">
                                    КРОССОВЕР</span>
                            </div>
                            <div class="offer-price">
                                <span class="price-text">1 000 000 РУБ</span>
                                <a href="{{ route('catalog.index') }}" class="more-text">СМОТРЕТЬ ВСЕ</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- STEPS --}}
            <div class="container-xxl my-100" data-aos="fade-up" data-aos-delay="500">
                <h2 class="section-title text-center mb-5">КАК КУПИТЬ АВТОМОБИЛЬ С АУКЦИОНА</h2>
                <div class="row g-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="step-card-index">
                            <div class="step-number">1</div>
                            <h3 class="steps-title">ВЫБОР</h3>
                            <p class="steps-description">Вы выбираете автомобиль на аукционе — по своим критериям.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
                        <div class="step-card-index">
                            <div class="step-number">2</div>
                            <h3 class="steps-title">ПРОВЕРКА</h3>
                            <p class="steps-description">Мы проверяем полную историю авто: ДТП, штрафы и реальное
                                состояние.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-12" data-aos="fade-up" data-aos-delay="300">
                        <div class="step-card-index">
                            <div class="step-number">3</div>
                            <h3 class="steps-title">ПОКУПКА</h3>
                            <p class="steps-description">Оформляем и выкупаем автомобиль на аукционе.</p>
                        </div>
                    </div>
                    <div class="col-lg-5 col-md-6 col-12" data-aos="fade-up" data-aos-delay="400">
                        <div class="step-card-index">
                            <div class="step-number">4</div>
                            <h3 class="steps-title">ФИНАЛЬНЫЙ КОНТРОЛЬ</h3>
                            <p class="steps-description">Убеждаемся, что автомобиль полностью готов к передаче вам.</p>
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-12 col-12" data-aos="fade-up" data-aos-delay="500">
                        <div class="step-card-index d-flex flex-column">
                            <div class="step-number">5</div>
                            <h3 class="steps-title">ОТПРАВИТЬ ЗАПРОС</h3>
                            <div
                                class="step-block d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-md-5">
                                <p class="steps-description">Оставьте контактные данные и мы обязательно свяжемся с вами
                                </p>
                                <a href="{{ route('auction.index') }}" class="btn btn-custom">УЗНАТЬ БОЛЬШЕ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ADVANTAGES --}}
            <section class="advantages-section my-100" data-aos="fade-up" data-aos-delay="500">
                <div class="advantages-bg">
                    <img src="{{ asset('assets/images/advantages/image.jpg') }}" alt="Car" class="bg-img">
                </div>
                <div class="container-xxl advantages-content">
                    <div class="row g-3 justify-content-center">
                        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="100">
                            <div class="adv-card adv-desktop">
                                <h3 class="adv-title">СОТРУДНИЧЕСТВО С НАМИ</h3>
                                <p class="adv-text">Гарантия надежного партнерства, доступных цен и индивидуального подхода
                                    к каждому клиенту ежедневно.</p>
                            </div>
                            <details class="adv-card adv-mobile">
                                <summary class="adv-title">СОТРУДНИЧЕСТВО С НАМИ</summary>
                                <p class="adv-text">Гарантия надежного партнерства, доступных цен и индивидуального подхода
                                    к каждому клиенту ежедневно.</p>
                            </details>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
                            <div class="adv-card adv-desktop">
                                <h3 class="adv-title">ИНДИВИДУАЛЬНЫЙ ПОДХОД</h3>
                                <p class="adv-text">В спектр наших услуг дополнительно входит продажа и индивидуальный
                                    подбор транспортного средства.</p>
                            </div>
                            <details class="adv-card adv-mobile">
                                <summary class="adv-title">ИНДИВИДУАЛЬНЫЙ ПОДХОД</summary>
                                <p class="adv-text">В спектр наших услуг дополнительно входит продажа и индивидуальный
                                    подбор транспортного средства.</p>
                            </details>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="300">
                            <div class="adv-card adv-desktop">
                                <h3 class="adv-title">ПОКУПКА АВТОМОБИЛЯ</h3>
                                <p class="adv-text">В нашем автосалоне можно приобрести машины с пробегом. Также можно
                                    воспользоваться услугой заказа авто с аукционов.</p>
                            </div>
                            <details class="adv-card adv-mobile">
                                <summary class="adv-title">ПОКУПКА АВТОМОБИЛЯ</summary>
                                <p class="adv-text">В нашем автосалоне можно приобрести машины с пробегом. Также можно
                                    воспользоваться услугой заказа авто с аукционов.</p>
                            </details>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="400">
                            <div class="adv-card adv-desktop">
                                <h3 class="adv-title">ОПЫТ И НАДЕЖНОСТЬ</h3>
                                <p class="adv-text">Более 9 лет успешной работы, за это время мы обслужили огромное
                                    количество корпоративных и частных клиентов.</p>
                            </div>
                            <details class="adv-card adv-mobile">
                                <summary class="adv-title">ОПЫТ И НАДЕЖНОСТЬ</summary>
                                <p class="adv-text">Более 9 лет успешной работы, за это время мы обслужили огромное
                                    количество корпоративных и частных клиентов.</p>
                            </details>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CARE --}}
            <section class="care-section container-xxl my-100" data-aos="fade-up" data-aos-delay="500">
                <div class="row align-items-stretch g-5">
                    <div class="col-lg-6">
                        <div class="care-wrapper position-relative h-100">
                            <img src="{{ asset('assets/images/care/image.jpg') }}" alt="Cars"
                                class="care-bg img-fluid w-100 h-100 object-fit-cover">
                            <div class="care-content">
                                <h2 class="care-title mb-0">ВАШ <br>АВТОМОБИЛЬ — <br> НАША ЗАБОТА</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex flex-column justify-content-center">
                        <ul class="care-list d-flex flex-column gap-4">
                            <li>
                                <h3 class="care-item-title">
                                    <span class="dot"></span>
                                    СТРАХОВАНИЕ «ОТ ВСЕХ РИСКОВ»
                                </h3>
                                <p class="care-item-text">Мы заботимся о вашем автомобиле — предлагаем полное страхование
                                    КАСКО и ОСАГО по выгодным тарифам. Узнайте об этом подробнее.</p>
                                <a href="{{ route('insurance') }}" class="care-link">Подробнее</a>
                            </li>
                            <li>
                                <h3 class="care-item-title">
                                    <span class="dot"></span>
                                    АВТОКРЕДИТ
                                </h3>
                                <p class="care-item-text">Получите автомобиль в кредит быстро и без лишних сложностей. Мы
                                    работаем с ведущими банками и предлагаем выгодные условия.</p>
                                <a href="{{ route('credit') }}" class="care-link">Подробнее</a>
                            </li>
                            <li>
                                <h3 class="care-item-title">
                                    <span class="dot"></span>
                                    ОБМЕН НА НОВЫЙ АВТОМОБИЛЬ
                                </h3>
                                <p class="care-item-text">Обменяйте старый авто на новый по выгодной цене. Мы оценим вашу
                                    машину бесплатно и предложим лучшее предложение.</p>
                                <a href="{{ route('trade-in') }}" class="care-link">Подробнее</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </section>

            {{-- REVIEWS --}}
            <section class="reviews-section container-xxl my-100" data-aos="fade-up" data-aos-delay="500">
                <h2 class="section-title text-center">ОТЗЫВЫ О НАС</h2>
                <div class="reviews-wrapper position-relative">
                    <div class="reviews-track">
                        <div class="reviews-container d-flex gap-4">
                            @forelse($reviews as $review)
                                @php
                                    $previewText = $review->comment;
                                    $isTruncated = false;
                                    if (mb_strlen($previewText) > 155) {
                                        $previewText = mb_substr($previewText, 0, 155) . '...';
                                        $isTruncated = true;
                                    }
                                @endphp
                                <div class="review-card" data-review-id="{{ $review->id }}">
                                    <div class="review-header d-flex align-items-center gap-3 mb-3">
                                        <img src="{{ asset('assets/images/reviews/user.svg') }}" alt="user"
                                            class="review-avatar">
                                        <div>
                                            <h3 class="review-name">{{ htmlspecialchars($review->user_name) }}</h3>
                                            <div class="review-stars">
                                                {!! str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <p class="review-text">
                                        {{ htmlspecialchars($previewText) }}
                                        @if ($isTruncated)
                                            <button class="read-more-btn" data-bs-toggle="modal"
                                                data-bs-target="#fullReviewModal" data-review-id="{{ $review->id }}"
                                                data-review-name="{{ $review->user_name }}"
                                                data-review-rating="{{ $review->rating }}"
                                                data-review-date="{{ $review->created_at->format('d.m.Y в H:i') }}"
                                                data-review-comment="{{ $review->comment }}">
                                                Читать полностью
                                            </button>
                                        @endif
                                    </p>
                                    <div class="review-footer d-flex justify-content-between align-items-center mt-auto">
                                        <div class="review-date text-muted small">
                                            {{ $review->created_at->format('d.m.Y') }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center">
                                    <p>Пока нет отзывов. Будьте первым!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    <button class="scroll-btn scroll-left" aria-label="Предыдущий отзыв">
                        <img src="{{ asset('assets/images/reviews/icon2.svg') }}" alt="Назад" class="scroll">
                    </button>
                    <button class="scroll-btn scroll-right" aria-label="Следующий отзыв">
                        <img src="{{ asset('assets/images/reviews/icon.svg') }}" alt="Вперёд" class="scroll">
                    </button>
                </div>
                <div class="text-center mt-4">
                    @auth
                        <button class="show-all" data-bs-toggle="modal" data-bs-target="#reviewModal">Оставить отзыв</button>
                    @endauth
                </div>
            </section>

            {{-- CONTACTS --}}
            <div class="contacts container-xxl my-100" id="contacts" data-aos="fade-up" data-aos-delay="500">
                <h2 class="pb-4 cont-title d-block d-md-none text-center">Контакты</h2>
                <div class="map-container img-fluid" id="map"></div>
                <a href="tel:+79874161010" class="btn-all d-flex justify-content-center d-md-none w-100 mt-3">
                    Связаться с нами
                </a>
                <div class="contacts-info justify-content-between align-items-center pt-5 pb-4 d-none d-md-flex">
                    <h2 class="cont-title mb-0">Контакты</h2>
                    <a href="tel:+79874161010" class="btn-all float-right">
                        Связаться с нами
                    </a>
                </div>
                <div class="lines"></div>
                <div
                    class="contacts-inform mx-0 px-0 row d-flex justify-content-between align-items-start pt-5 flex-md-row container-xxl">
                    <div class="col-md-4 col-12 contact-info text-md-start px-0" id="contacts-block">
                        <h3 class="pb-3">Свяжитесь с нами</h3>
                        <div class="d-flex gap-3">
                            <img src="{{ asset('assets/images/contacts/phone-icon.svg') }}" alt="Телефон"
                                class="phone-img">
                            <a href="tel:+79874161010" class="contacts-block d-flex gap-3 m-0">8 (987) 416-10-10</a>
                        </div>
                        <div class="d-flex gap-3">
                            <img src="{{ asset('assets/images/contacts/time.svg') }}" alt="Часы работы"
                                class="phone-img">
                            <p class="contacts-block d-flex gap-3 m-0">Пн - вс: 11:00 - 23:00</p>
                        </div>
                    </div>
                    <div class="col-md-5 col-12 contact-info p-0 m-0">
                        <h3 class="pb-3">Адреса</h3>
                        <div class="blocks d-flex align-items-center gap-3">
                            <img src="{{ asset('assets/images/contacts/ellipse.svg') }}" alt="Адрес"
                                class="contacts-ellipse">
                            <p class="contacts-block">г. Казань, ул. Ямашева, д. 76</p>
                        </div>
                        <div class="blocks d-flex align-items-center gap-3">
                            <img src="{{ asset('assets/images/contacts/ellipse.svg') }}" alt="Адрес"
                                class="contacts-ellipse">
                            <p class="contacts-block m-0">г. Казань, ул. Чистопольская, д. 9а</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-12 social-media align-items-md-start align gap-sm-3">
                        <h3 class="pb-4 con-title text-start text-md-end">Мы в соцсетях</h3>
                        <div class="contact-icon d-flex gap-4 gap-sm-3">
                            <a href="https://vk.com/trattoria_group"><img class="icon-adaptives"
                                    src="{{ asset('assets/images/contacts/vk.svg') }}" alt="ВКонтакте"></a>
                            {{-- <a href="https://www.facebook.com/trattoriagroup1/"><img class="icon-adaptives"
                                    src="{{ asset('assets/images/contacts/facebook.svg') }}" alt="Facebook"></a> --}}
                            <a href="tel:+78432102828"><img class="icon-adaptives"
                                    src="{{ asset('assets/images/contacts/phone.svg') }}" alt="Телефон"></a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FOOTER --}}
            @include('partials.footer')

        </div>

    </section>



@endsection

@push('scripts')
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script>
        // Yandex Maps
        ymaps.ready(init);

        function init() {
            var map = new ymaps.Map("map", {
                center: [55.825, 49.09],
                zoom: 13,
                controls: ['zoomControl', 'fullscreenControl']
            });
            var placemark1 = new ymaps.Placemark([55.831903, 49.067341], {
                balloonContent: "г. Казань, ул. Проспект Ямашева, д. 76"
            }, {
                preset: 'islands#redIcon'
            });
            var placemark2 = new ymaps.Placemark([55.818146, 49.108074], {
                balloonContent: "г. Казань, ул. Чистопольская, д. 9а"
            }, {
                preset: 'islands#redIcon'
            });
            map.geoObjects.add(placemark1).add(placemark2);
            map.setBounds(map.geoObjects.getBounds(), {
                checkZoomRange: true,
                zoomMargin: 40
            });
        }

        // Tabs functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.tab-btn');
            const contents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(btn => btn.classList.remove('active'));
                    contents.forEach(c => c.style.display = 'none');

                    tab.classList.add('active');
                    const targetTab = document.getElementById(tab.dataset.tab);
                    if (targetTab) {
                        targetTab.style.display = 'block';
                    }
                });
            });

            // Reviews slider
            const track = document.querySelector('.reviews-track');
            const scrollLeftBtn = document.querySelector('.scroll-left');
            const scrollRightBtn = document.querySelector('.scroll-right');

            if (track && scrollLeftBtn && scrollRightBtn) {
                let isScrolling = false;

                function getScrollStep() {
                    const firstCard = track.querySelector('.review-card');
                    if (!firstCard) return 400;

                    const cardWidth = firstCard.offsetWidth;
                    const gap = parseFloat(getComputedStyle(track.querySelector('.reviews-container')).gap) || 24;

                    return cardWidth + gap;
                }

                function updateButtonVisibility() {
                    if (track.scrollLeft <= 0) {
                        scrollLeftBtn.style.display = 'none';
                    } else {
                        scrollLeftBtn.style.display = 'block';
                    }

                    const maxScrollLeft = track.scrollWidth - track.clientWidth;
                    if (track.scrollLeft >= maxScrollLeft - 1) {
                        scrollRightBtn.style.display = 'none';
                    } else {
                        scrollRightBtn.style.display = 'block';
                    }
                }

                updateButtonVisibility();
                track.addEventListener('scroll', updateButtonVisibility);
                window.addEventListener('resize', updateButtonVisibility);

                scrollRightBtn.addEventListener('click', function() {
                    if (isScrolling) return;
                    smoothScroll(track, 'right');
                });

                scrollLeftBtn.addEventListener('click', function() {
                    if (isScrolling) return;
                    smoothScroll(track, 'left');
                });

                function smoothScroll(container, direction) {
                    if (isScrolling) return;
                    isScrolling = true;
                    const scrollAmount = getScrollStep();
                    container.scrollBy({
                        left: direction === 'right' ? scrollAmount : -scrollAmount,
                        behavior: 'smooth'
                    });
                    setTimeout(() => {
                        isScrolling = false;
                        updateButtonVisibility();
                    }, 600);
                }
            }
        })();
    </script>

    <script>
        (function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');

            if (!slides.length) return;

            let currentSlide = 0;
            let autoPlayInterval;
            const autoPlayDelay = 4000;

            function goToSlide(index) {
                slides.forEach(s => s.classList.remove('active'));
                dots.forEach(d => d.classList.remove('active'));

                currentSlide = index;

                slides[currentSlide].classList.add('active');
                dots[currentSlide].classList.add('active');
            }

            function nextSlide() {
                let next = currentSlide + 1;
                if (next >= slides.length) {
                    next = 0;
                }
                goToSlide(next);
            }

            function prevSlide() {
                let prev = currentSlide - 1;
                if (prev < 0) {
                    prev = slides.length - 1;
                }
                goToSlide(prev);
            }

            function startAutoPlay() {
                autoPlayInterval = setInterval(nextSlide, autoPlayDelay);
            }

            function stopAutoPlay() {
                clearInterval(autoPlayInterval);
            }

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goToSlide(i);
                    stopAutoPlay();
                    startAutoPlay();
                });
            });

            const slider = document.querySelector('.hero-slider');
            if (slider) {
                slider.addEventListener('mouseenter', stopAutoPlay);
                slider.addEventListener('mouseleave', startAutoPlay);
            }

            startAutoPlay();
        })();
    </script>
@endpush
