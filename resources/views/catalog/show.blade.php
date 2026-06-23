@extends('layouts.app')

@section('title', $car->brand . ' ' . $car->model)

@section('content')
    <div class="container-xxl py-4 px-4 admin-info">
        <nav class="breadcrumb mt-4">
            <a href="{{ route('home') }}" class="breadcrumb-item">Главная</a>
            <a href="{{ route('catalog.index') }}" class="breadcrumb-item">Каталог</a>
            @if ($car->brand)
                <a href="{{ route('catalog.index', ['brand' => $car->brand]) }}" class="breadcrumb-item">
                    {{ $car->brand }}
                </a>
            @endif
            <span class="breadcrumb-item active">{{ $car->model }}</span>
        </nav>

        <h1 class="page-title my-5">{{ $car->model }}</h1>

        {{-- Галерея фото --}}
        <div class="photo-wrap">
            <div class="photo-inner position-relative {{ $car->images->count() <= 1 ? 'no-thumbnails' : '' }}"
                data-aos="fade-up">
                @if ($car->images->count() > 1)
                    <div class="thumbs-col">
                        <div class="thumbs" role="list">
                            @foreach ($car->images as $index => $image)
                                @php
                                    $imagePath = $image->path;
                                    if (!Str::startsWith($imagePath, 'http') && !Str::startsWith($imagePath, '/storage')) {
                                        $imagePath = asset('storage/' . ltrim($imagePath, '/'));
                                    } elseif (!Str::startsWith($imagePath, 'http')) {
                                        $imagePath = asset($imagePath);
                                    }
                                @endphp
                                <img src="{{ $imagePath }}" alt="Фото {{ $index + 1 }}"
                                    data-large="{{ $imagePath }}"
                                    class="img-fluid {{ $index === 0 ? 'active' : '' }}">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="main-photo position-relative">
                    @php
                        $mainImagePath = $mainImage;
                        if (!Str::startsWith($mainImagePath, 'http') && !Str::startsWith($mainImagePath, '/storage')) {
                            $mainImagePath = asset('storage/' . ltrim($mainImagePath, '/'));
                        } elseif (!Str::startsWith($mainImagePath, 'http')) {
                            $mainImagePath = asset($mainImagePath);
                        }
                    @endphp
                    <img id="mainPhoto" src="{{ $mainImagePath }}" alt="{{ $car->model }}">

                    @if ($car->images->count() > 1)
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

        {{-- Контент: описание + характеристики --}}
        <div class="row content-row gx-4 equal-height" data-aos="fade-up">
            <div class="col-lg-7 d-flex">
                <div class="desc-card w-100">
                    <div class="desc-title">Описание</div>
                    <div class="desc-text">
                        @if ($car->description)
                            {!! nl2br(e($car->description)) !!}
                        @else
                            @if ($car->condition === 'Новая')
                                Новый автомобиль в наличии. Полная комплектация, гарантия производителя.
                                Доступен тест-драйв. Все документы готовы к оформлению.
                            @else
                                Автомобиль с пробегом в отличном состоянии. Полностью проверен,
                                технически исправен. Все документы в порядке.
                            @endif
                        @endif
                    </div>

                    <div class="spec-grid mt-3">
                        <div class="spec-row one-car">
                            @if ($car->engine)
                                <div class="spec-cell">
                                    <img src="{{ asset('assets/images/one_car/1.svg') }}" alt="power" class="spec-icon">
                                    Объем двигателя
                                    <span class="spec-strong">{{ $car->engine }} L</span>
                                </div>
                            @endif

                            @if ($car->drive)
                                <div class="spec-cell">
                                    <img src="{{ asset('assets/images/one_car/2.svg') }}" alt="awd" class="spec-icon">
                                    Привод
                                    <span class="spec-strong">{{ $car->drive }}</span>
                                </div>
                            @endif

                            <div class="spec-cell">
                                <img src="{{ asset('assets/images/one_car/3.svg') }}" alt="seats" class="spec-icon">
                                Тип кузова
                                <span class="spec-strong">{{ $car->body ?? '—' }}</span>
                            </div>
                        </div>

                        <div class="spec-row">
                            @if ($car->gearbox)
                                <div class="spec-cell">
                                    <img src="{{ asset('assets/images/one_car/4.svg') }}" alt="gear" class="spec-icon">
                                    Коробка передач
                                    <span class="spec-strong">{{ $car->gearbox }}</span>
                                </div>
                            @endif

                            @if ($car->fuel)
                                <div class="spec-cell">
                                    <img src="{{ asset('assets/images/one_car/5.svg') }}" alt="fuel" class="spec-icon">
                                    Тип топлива
                                    <span class="spec-strong">{{ $car->fuel }}</span>
                                </div>
                            @endif

                            @if ($car->condition !== 'Новая' && $car->mileage)
                                <div class="spec-cell">
                                    <img src="{{ asset('assets/images/one_car/6.svg') }}" alt="mileage" class="spec-icon">
                                    Пробег
                                    <span class="spec-strong">{{ number_format($car->mileage, 0, '.', ' ') }} км</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Правая колонка: цена и кнопки + конфигуратор --}}
            <div class="col-lg-5 d-flex">
                <div class="info-card w-100">
                    <div>
                        <div class="info-title">{{ $car->model }}</div>
                        <div class="info-list">
                            @if ($car->drive)
                                <p><span class="label">Привод:</span><span class="value">{{ $car->drive }}</span></p>
                            @endif
                            @if ($car->engine)
                                <p><span class="label">Объем двигателя:</span><span class="value">{{ $car->engine }}
                                        L</span></p>
                            @endif
                            @if ($car->fuel)
                                <p><span class="label">Тип топлива:</span><span class="value">{{ $car->fuel }}</span>
                                </p>
                            @endif
                            @if ($car->condition !== 'Новая' && $car->mileage)
                                <p><span class="label">Пробег:</span><span
                                        class="value">{{ number_format($car->mileage, 0, '.', ' ') }} км</span></p>
                            @endif
                            @if ($car->condition !== 'Новая' && $car->owners)
                                <p><span class="label">Количество владельцев:</span><span
                                        class="value">{{ $car->owners }}</span></p>
                            @endif
                            @if ($car->condition !== 'Новая' && $car->condition)
                                <p><span class="label">Состояние:</span><span class="value">{{ $car->condition }}</span>
                                </p>
                            @endif
                            @if ($car->transmissions)
                                <p><span class="label">Количество передач:</span><span
                                        class="value">{{ $car->transmissions }}</span></p>
                            @endif
                            @if ($car->trunk)
                                <p><span class="label">Объем багажника:</span><span class="value">{{ $car->trunk }}
                                        L</span></p>
                            @endif
                            @if ($car->gearbox)
                                <p><span class="label">Коробка передач:</span><span
                                        class="value">{{ $car->gearbox }}</span></p>
                            @endif
                            @if ($car->body)
                                <p><span class="label">Тип кузова:</span><span class="value">{{ $car->body }}</span>
                                </p>
                            @endif
                            @if ($car->price)
                                <p class="price-item">
                                    <span class="label">Цена:</span>
                                    <span class="value price">{{ number_format($car->price, 0, '.', ' ') }} ₽</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="buttons-row">
                            <a href="#" class="btn-contact" data-bs-toggle="modal" data-bs-target="#requestModal">
                                Связаться с нами
                            </a>
                            @auth
                                <button class="btn-fav heart-icon {{ $isFavorite ? 'active' : '' }}" id="favBtn"
                                    aria-label="{{ $isFavorite ? 'Удалить из избранного' : 'Добавить в избранное' }}"
                                    data-car-id="{{ $car->id }}">
                                    <span class="heart">
                                        <svg width="20" height="18" viewBox="0 0 23 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.5001 20.5791L21.2395 11.4449L21.2719 11.4125C23.6884 8.83083 23.5543 4.76442 20.9725 2.34739L20.7636 2.15198C19.5711 1.03567 18.0151 0.420992 16.3823 0.420992C14.6139 0.420992 12.9068 1.16113 11.705 2.44504L11.5 2.65839L11.3013 2.45152C10.0932 1.16103 8.3861 0.420898 6.61775 0.420898C4.98489 0.420898 3.42894 1.03558 2.23667 2.15169L2.02736 2.34757C-0.554089 4.76423 -0.688368 8.83073 1.72819 11.4125L11.5001 20.5791ZM2.98976 3.37599L3.19907 3.18029C4.12963 2.30917 5.34378 1.82952 6.61784 1.82952C8.01829 1.82952 9.31639 2.39227 10.2793 3.42078L11.5001 4.69174L12.7271 3.4142C13.6838 2.39227 14.9819 1.82952 16.3823 1.82952C17.6563 1.82952 18.8704 2.30917 19.8013 3.18048L20.0101 3.3758C22.0192 5.25674 22.1288 8.41823 20.2586 10.4336L11.5001 18.648L2.74148 10.4336C0.871433 8.41813 0.980922 5.25665 2.98976 3.37599Z"
                                                fill="{{ $isFavorite ? '#dc3545' : '#4F4F4F' }}" />
                                        </svg>
                                    </span>
                                </button>
                            @else
                                <button class="btn-fav heart-icon" onclick="window.location.href='{{ route('login') }}'"
                                    title="Войдите, чтобы добавить в избранное">
                                    <span class="heart">
                                        <svg width="20" height="18" viewBox="0 0 23 21" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.5001 20.5791L21.2395 11.4449L21.2719 11.4125C23.6884 8.83083 23.5543 4.76442 20.9725 2.34739L20.7636 2.15198C19.5711 1.03567 18.0151 0.420992 16.3823 0.420992C14.6139 0.420992 12.9068 1.16113 11.705 2.44504L11.5 2.65839L11.3013 2.45152C10.0932 1.16103 8.3861 0.420898 6.61775 0.420898C4.98489 0.420898 3.42894 1.03558 2.23667 2.15169L2.02736 2.34757C-0.554089 4.76423 -0.688368 8.83073 1.72819 11.4125L11.5001 20.5791ZM2.98976 3.37599L3.19907 3.18029C4.12963 2.30917 5.34378 1.82952 6.61784 1.82952C8.01829 1.82952 9.31639 2.39227 10.2793 3.42078L11.5001 4.69174L12.7271 3.4142C13.6838 2.39227 14.9819 1.82952 16.3823 1.82952C17.6563 1.82952 18.8704 2.30917 19.8013 3.18048L20.0101 3.3758C22.0192 5.25674 22.1288 8.41823 20.2586 10.4336L11.5001 18.648L2.74148 10.4336C0.871433 8.41813 0.980922 5.25665 2.98976 3.37599Z"
                                                fill="#4F4F4F" />
                                        </svg>
                                    </span>
                                </button>
                            @endauth
                        </div>

                        {{-- Блок-ссылка на конфигуратор --}}
                        <div class="configurator-hint mt-4 pt-2">
                            <a href="{{ route('configurator.index') }}" class="configurator-link">
                                <div class="configurator-icon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.33-.02-.64-.06-.94l2.02-1.58c.18-.14.23-.38.12-.56l-1.89-3.28c-.12-.19-.36-.26-.56-.18l-2.38.96c-.5-.38-1.06-.68-1.66-.88L14.45 3.5c-.04-.2-.2-.34-.4-.34h-3.78c-.2 0-.36.14-.4.34l-.3 2.52c-.6.2-1.16.5-1.66.88l-2.38-.96c-.2-.08-.44-.01-.56.18l-1.89 3.28c-.12.19-.07.42.12.56l2.02 1.58c-.04.3-.06.61-.06.94 0 .33.02.64.06.94l-2.02 1.58c-.18.14-.23.38-.12.56l1.89 3.28c.12.19.36.26.56.18l2.38-.96c.5.38 1.06.68 1.66.88l.3 2.52c.04.2.2.34.4.34h3.78c.2 0 .36-.14.4-.34l.3-2.52c.6-.2 1.16-.5 1.66-.88l2.38.96c.2.08.44.01.56-.18l1.89-3.28c.12-.19.07-.42-.12-.56l-2.02-1.58zM12 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z" fill="currentColor"/>
                                    </svg>
                                </div>
                                <div class="configurator-text">
                                    <span class="configurator-title">Соберите свой автомобиль</span>
                                    <span class="configurator-subtitle">Уникальная комплектация в конфигураторе</span>
                                </div>
                                <div class="configurator-arrow">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // === Галерея фото ===
            const thumbs = Array.from(document.querySelectorAll('.thumbs img'));
            const mainPhoto = document.getElementById('mainPhoto');

            if (thumbs.length > 0 && mainPhoto) {
                const normalize = src => {
                    const a = document.createElement('a');
                    a.href = src;
                    return a.href;
                };

                let currentIndex = thumbs.findIndex(t =>
                    normalize(t.dataset.large || t.src) === normalize(mainPhoto.src)
                );
                if (currentIndex === -1) currentIndex = 0;

                function updatePhoto(index) {
                    currentIndex = ((index % thumbs.length) + thumbs.length) % thumbs.length;
                    const src = thumbs[currentIndex].dataset.large || thumbs[currentIndex].src;
                    mainPhoto.src = src;
                    thumbs.forEach(i => i.classList.remove('active'));
                    thumbs[currentIndex].classList.add('active');
                }

                thumbs.forEach((t, i) => t.addEventListener('click', () => updatePhoto(i)));

                const prevBtn = document.querySelector('.slider-controls .arrow.left');
                const nextBtn = document.querySelector('.slider-controls .arrow.right');
                if (prevBtn) prevBtn.addEventListener('click', () => updatePhoto(currentIndex - 1));
                if (nextBtn) nextBtn.addEventListener('click', () => updatePhoto(currentIndex + 1));

                updatePhoto(currentIndex);
            }

            // === Избранное ===
            const favBtn = document.getElementById('favBtn');
            @auth
            if (favBtn) {
                favBtn.addEventListener('click', function() {
                    const carId = this.dataset.carId;
                    const heartIcon = this;
                    const svgPath = heartIcon.querySelector('svg path');
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    if (!csrfToken) {
                        showNotification('Ошибка безопасности. Обновите страницу.', 'error');
                        return;
                    }

                    fetch("{{ route('favorites.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ car_id: carId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            if (response.status === 419 || response.status === 401) {
                                throw new Error('Сессия истекла. Пожалуйста, обновите страницу.');
                            }
                            return response.json().then(err => { throw new Error(err.error || 'Ошибка'); });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            if (data.action === 'added') {
                                heartIcon.classList.add('active');
                                heartIcon.setAttribute('aria-label', 'Удалить из избранного');
                                if (svgPath) svgPath.setAttribute('fill', '#dc3545');
                                showNotification('Автомобиль добавлен в избранное', 'success');
                            } else {
                                heartIcon.classList.remove('active');
                                heartIcon.setAttribute('aria-label', 'Добавить в избранное');
                                if (svgPath) svgPath.setAttribute('fill', '#4F4F4F');
                                showNotification('Автомобиль удален из избранного', 'info');
                            }
                        } else {
                            showNotification('Ошибка: ' + (data.error || 'Неизвестная ошибка'), 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        showNotification(error.message, 'error');
                    });
                });
            }
            @endauth

            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
                notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                notification.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
                document.body.appendChild(notification);
                setTimeout(() => notification.remove(), 3000);
            }
        });
    </script>
    @endpush
@endsection