@extends('layouts.app')

@section('title', 'Аукцион автомобилей')


@section('content')
<section class="auction-filter-section" aria-label="Фильтр автомобилей">
    <div class="container-xxl px-4">
        <h2 class="auction-title my-5">АУКЦИОН АВТОМОБИЛЕЙ</h2>

        <div class="tabs-wrap" aria-label="Фильтр по маркам">
            <nav class="tabs" id="brandTabs" role="tablist">
                <button class="tab tab-auc active" data-filter="all" role="tab" aria-selected="true">Все модели</button>
                @foreach($brands as $brand)
                    <button class="tab tab-auc" data-filter="{{ $brand }}" role="tab" aria-selected="false" title="{{ $brand }}">
                        {{ Str::limit($brand, 15) }}
                    </button>
                @endforeach
                <div class="tab-indicator" aria-hidden="true"></div>
            </nav>
            <div class="tabs-line" aria-hidden="true"></div>
        </div>

        <div class="offers-grid" id="carsGrid">
            @forelse($cars as $car)
                @php
                    $brand = explode(' ', $car->model)[0];
                    $auction = $car->auctions->first();
                    $mileage = is_numeric($car->mileage) ? (int)$car->mileage : (int)preg_replace('/[^0-9]/', '', $car->mileage);
                    $price = is_numeric($car->price) ? (int)$car->price : (int)preg_replace('/[^0-9]/', '', $car->price);
                @endphp
                <article class="offer-card" data-brand="{{ $brand }}">
                    <div class="offer-header mb-0">
                        <h3 class="mb-0" title="{{ $car->model }}">{{ Str::limit($car->model, 20) }}</h3>
                        <span class="badge-used">• USED</span>
                    </div>
                    <p class="year d-flex align-items-start">{{ $car->condition ?? 'Не битая' }}</p>
                    <div class="offer-image">
                        @if($car->photo && file_exists(public_path('storage/'.$car->photo)))
                            <img src="{{ asset('storage/'.$car->photo) }}" alt="{{ $car->model }}">
                        @else
                            <div class="no-photo-placeholder"><span>Фото нет</span></div>
                        @endif
                        <span class="brand-watermark" title="{{ $brand }}">{{ $brand }}</span>
                    </div>
                    <div class="offer-specs">
                        <span><img src="{{ asset('assets/images/offer/info-1.svg') }}" alt=""> {{ $car->engine }} L</span>
                        <span><img src="{{ asset('assets/images/offer/info-2.svg') }}" alt=""> {{ $car->drive ?? 'AWD' }}</span>
                        <span><img src="{{ asset('assets/images/offer/info-3.svg') }}" alt=""> {{ number_format($mileage, 0, '', ' ') }} км</span>
                    </div>
                    <div class="offer-price">
                        <span class="price-text">{{ number_format($price, 0, '', ' ') }} ₽</span>
                        @auth
                            <a href="{{ route('auction.show', $car) }}" class="more-text">ПОДРОБНЕЕ</a>
                        @else
                            <a href="#" class="more-text" data-bs-toggle="modal" data-bs-target="#registerModal">ПОДРОБНЕЕ</a>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="col-12 py-5 text-center">
                    <h4>Нет доступных автомобилей</h4>
                    <p>В данный момент все аукционы завершены. Следите за обновлениями!</p>
                </div>
            @endforelse
        </div>
    </div>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>
</section>

@guest
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
            <div class="modal-content modal-custom" style="padding: 24px;">
                <div class="text-center">
                    <div class="modal-icon warning" style="margin: 0 auto 16px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.29 3.86L1.82 18C1.6453 18.3024 1.55298 18.6453 1.55129 18.998C1.5496 19.3506 1.6386 19.6998 1.80962 20.0114C1.98064 20.3231 2.22744 20.5859 2.525 20.7732C2.82256 20.9605 3.1605 21.0659 3.505 21.079H20.495C20.8395 21.0659 21.1774 20.9605 21.475 20.7732C21.7726 20.5859 22.0194 20.3231 22.1904 20.0114C22.3614 19.6998 22.4504 19.3506 22.4487 18.998C22.447 18.6453 22.3547 18.3024 22.18 18L13.71 3.86C13.5318 3.5575 13.2828 3.3031 12.9866 3.1209C12.6904 2.9387 12.357 2.8349 12 2.8199C11.643 2.8349 11.3096 2.9387 11.0134 3.1209C10.7172 3.3031 10.4682 3.5575 10.29 3.86Z"
                                stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 9V13" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" />
                            <path d="M12 17H12.01" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="mb-2" style="font-weight: 600; color: #111;">Требуется регистрация</h5>
                    <p class="mb-4" style="color: #6B7280; font-size: 15px; line-height: 1.5;">
                        Пожалуйста, зарегистрируйтесь или войдите в аккаунт, чтобы просматривать подробную информацию об
                        автомобиле.
                    </p>
                    <div class="d-flex gap-2" style="justify-content: center;">
                        <button type="button" class="btn btn-sm"
                            style="flex: 1; padding: 8px 16px; border-radius: 10px; font-weight: 600; color: white; background: #4071CB; border: none;"
                            data-bs-dismiss="modal">ОК</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endguest
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabsWrap = document.querySelector('.tabs-wrap');
    const tabsContainer = document.getElementById('brandTabs');
    const tabs = tabsContainer.querySelectorAll('.tab');
    const indicator = tabsContainer.querySelector('.tab-indicator');
    const cars = document.querySelectorAll('#carsGrid .offer-card');

    function updateIndicator(activeTab) {
        if (!indicator || !activeTab) return;
        
        const wrapRect = tabsWrap.getBoundingClientRect();
        const tabRect = activeTab.getBoundingClientRect();
        
        const left = tabRect.left - wrapRect.left;
        const width = tabRect.width;
        
        indicator.style.transform = 'translateX(' + left + 'px)';
        indicator.style.width = width + 'px';
    }

    function filterCars(filter) {
        cars.forEach(car => {
            const brand = car.getAttribute('data-brand');
            if (filter === 'all' || brand === filter) {
                car.style.display = '';
                car.classList.remove('hidden');
            } else {
                car.style.display = 'none';
                car.classList.add('hidden');
            }
        });
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const filter = this.getAttribute('data-filter');
            filterCars(filter);
            updateIndicator(this);
        });
    });

    const activeTab = tabsContainer.querySelector('.tab.active');
    if (activeTab) {
        setTimeout(() => updateIndicator(activeTab), 100);
    }

    window.addEventListener('resize', function() {
        const currentActive = tabsContainer.querySelector('.tab.active');
        if (currentActive) updateIndicator(currentActive);
    });
});
</script>
@endpush