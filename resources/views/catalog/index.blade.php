@extends('layouts.app')

@section('title', 'Каталог')

@section('content')

    <!-- Backdrop для мобильного фильтра -->
    <div class="filter-backdrop" id="filterBackdrop"></div>

    <!-- Лёгкая модалка: Требуется регистрация -->
    @guest
        <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width: 390px;">
                <div class="modal-content modal-custom" style="padding: 24px; border-radius: 20px; border: none; box-shadow: 0 20px 60px rgba(0,0,0,0.15);">
                    <div class="text-center">
                        <div class="modal-icon warning" style="margin: 0 auto 16px; width: 56px; height: 56px; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.29 3.86L1.82 18C1.6453 18.3024 1.55298 18.6453 1.55129 18.998C1.5496 19.3506 1.6386 19.6998 1.80962 20.0114C1.98064 20.3231 2.22744 20.5859 2.525 20.7732C2.82256 20.9605 3.1605 21.0659 3.505 21.079H20.495C20.8395 21.0659 21.1774 20.9605 21.475 20.7732C21.7726 20.5859 22.0194 20.3231 22.1904 20.0114C22.3614 19.6998 22.4504 19.3506 22.4487 18.998C22.447 18.6453 22.3547 18.3024 22.18 18L13.71 3.86C13.5318 3.5575 13.2828 3.3031 12.9866 3.1209C12.6904 2.9387 12.357 2.8349 12 2.8199C11.643 2.8349 11.3096 2.9387 11.0134 3.1209C10.7172 3.3031 10.4682 3.5575 10.29 3.86Z"
                                    stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 9V13" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" />
                                <path d="M12 17H12.01" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                        <h5 class="mb-2" style="font-weight: 700; color: var(--text-main); font-size: 18px;">Требуется регистрация</h5>
                        <p class="mb-4" style="color: var(--text-muted); font-size: 14px; line-height: 1.5;">
                            Пожалуйста, зарегистрируйтесь или войдите в аккаунт, чтобы просматривать подробную информацию об
                            автомобиле.
                        </p>
                        <div class="d-flex gap-2" style="justify-content: center;">
                            <button type="button" class="btn btn-sm"
                                style="flex: 1; padding: 10px 16px; border-radius: 12px; font-weight: 600; color: white; background: var(--primary); border: none; font-size: 14px;"
                                data-bs-dismiss="modal">Понятно</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest

    <div class="container-xxl mt-5">
        <h2 class="section-title">КАТАЛОГ</h2>

        <div class="d-md-none">
            <button id="filter-toggle" class="btn w-100">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                </svg>
                Фильтр
            </button>
        </div>

        <div class="row gx-4">
            <aside id="filter-col" class="col-lg-3 col-md-4 col-12 filter-col">
                <div class="filter-mobile-header">
                    <span class="filter-mobile-title">Фильтры</span>
                    <button class="filter-close-btn" id="filterCloseBtn" aria-label="Закрыть">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="filter-card" data-aos="fade-up">
                    
                    <!-- ===== ПОИСК ПО НАЗВАНИЮ ===== -->
                    <div class="search-box">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Поиск по названию..." autocomplete="off">
                        <button class="search-clear" id="searchClear" aria-label="Очистить поиск">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- ===== ЦЕНА ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="priceDropdownHeader">
                            <span class="title">Цена, ₽</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="priceDropdownContent">
                            <div class="range-inputs">
                                <input type="number" id="priceMin" placeholder="От" min="0">
                                <span>—</span>
                                <input type="number" id="priceMax" placeholder="До" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- ===== ПРОБЕГ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="mileageDropdownHeader">
                            <span class="title">Пробег, км</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="mileageDropdownContent">
                            <div class="range-inputs">
                                <input type="number" id="mileageMin" placeholder="От, км" min="0">
                                <span>—</span>
                                <input type="number" id="mileageMax" placeholder="До, км" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- ===== ПРИВОД ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="driveDropdownHeader">
                            <span class="title">Привод</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="driveDropdownContent">
                            @foreach(['Полный', 'Передний', 'Задний'] as $drive)
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="drive-checkbox" value="{{ $drive }}">
                                    <span>{{ $drive }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ===== КОРОБКА ПЕРЕДАЧ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="gearboxDropdownHeader">
                            <span class="title">Коробка передач</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="gearboxDropdownContent">
                            @foreach(['Автомат', 'Механика', 'Робот', 'Вариатор'] as $gearbox)
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="gearbox-checkbox" value="{{ $gearbox }}">
                                    <span>{{ $gearbox }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ===== ТИП ТОПЛИВА ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="fuelDropdownHeader">
                            <span class="title">Тип топлива</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="fuelDropdownContent">
                            @foreach(['Бензин', 'Дизель', 'Электро', 'Гибрид'] as $fuel)
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="fuel-checkbox" value="{{ $fuel }}">
                                    <span>{{ $fuel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ===== СОСТОЯНИЕ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="conditionDropdownHeader">
                            <span class="title">Состояние</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="conditionDropdownContent">
                            @foreach(['Новый', 'С пробегом'] as $condition)
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="condition-checkbox" value="{{ $condition }}">
                                    <span>{{ $condition }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ===== КОЛИЧЕСТВО ВЛАДЕЛЬЦЕВ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="ownersDropdownHeader">
                            <span class="title">Владельцы</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="ownersDropdownContent">
                            <label class="filter-checkbox">
                                <input type="checkbox" class="owners-checkbox" value="0">
                                <span>Не было владельцев (новый)</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="owners-checkbox" value="1-3">
                                <span>1–3 владельца</span>
                            </label>
                            <label class="filter-checkbox">
                                <input type="checkbox" class="owners-checkbox" value="3+">
                                <span>Более 3 владельцев</span>
                            </label>
                        </div>
                    </div>

                    <!-- ===== КУЗОВ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="bodyDropdownHeader">
                            <span class="title">Кузов</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="bodyDropdownContent">
                            @foreach($bodyTypes as $bodyType)
                                @php
                                    $count = $cars->where('body_type_lower', strtolower($bodyType))->count();
                                @endphp
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="body-checkbox" value="{{ strtolower($bodyType) }}">
                                    <span>{{ ucfirst($bodyType) }}</span>
                                    @if($count > 0)
                                        <span class="count">{{ $count }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- ===== МАРКИ ===== -->
                    <div class="dropdown-filter">
                        <div class="dropdown-header" id="brandDropdownHeader">
                            <span class="title">Марки</span>
                            <svg class="arrow-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </div>
                        <div class="dropdown-content" id="brandDropdownContent">
                            @foreach($brands as $brand)
                                <label class="filter-checkbox">
                                    <input type="checkbox" class="brand-checkbox" value="{{ $brand->name_lower }}">
                                    <span>{{ $brand->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Кнопки внутри фильтра -->
                    <div class="d-grid mt-3" style="gap: 8px;">
                        <button id="applyFilters" class="w-100">Применить фильтры</button>
                        <button id="resetFilters" class="w-100">Сбросить всё</button>
                    </div>
                </div>

                <!-- Мобильные кнопки убраны -->
                <div class="filter-mobile-footer">
                    <button id="resetFiltersMobile">Сбросить</button>
                    <button id="applyFiltersMobile">Показать</button>
                </div>
            </aside>

            <div class="col-lg-9 col-md-8 col-12">
                <div class="offers-grid" id="offersGrid">
                    @forelse($cars as $index => $car)
                        <div class="offer-card" 
                            data-brand="{{ $car->brand_lower }}" 
                            data-body="{{ $car->body_type_lower }}"
                            data-price="{{ $car->price }}" 
                            data-mileage="{{ $car->mileage ?? 0 }}"
                            data-drive="{{ $car->drive ?? '' }}"
                            data-gearbox="{{ $car->gearbox ?? '' }}"
                            data-fuel="{{ $car->fuel_type ?? '' }}"
                            data-condition="{{ $car->condition ?? ($car->is_new ? 'Новый' : 'С пробегом') }}"
                            data-owners="{{ $car->owners_count ?? ($car->is_new ? '0' : '1-3') }}"
                            data-model="{{ strtolower($car->model) }}"
                            data-aos="fade-up"
                            data-aos-delay="{{ ($loop->index % 6) * 100 }}">

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
                                    <a href="#" class="more-text" data-bs-toggle="modal"
                                        data-bs-target="#registerModal">ПОДРОБНЕЕ</a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Автомобили не найдены</p>
                        </div>
                    @endforelse
                </div>

                <nav>
                    <ul class="pagination" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // ========== МОБИЛЬНЫЙ ФИЛЬТР ==========
                const filterToggleBtn = document.getElementById('filter-toggle');
                const filterCol = document.getElementById('filter-col');
                const filterBackdrop = document.getElementById('filterBackdrop');
                const filterCloseBtn = document.getElementById('filterCloseBtn');

                function openFilter() {
                    if (!filterCol || !filterBackdrop) return;
                    filterCol.classList.add('open');
                    filterBackdrop.classList.add('open');
                    document.body.classList.add('filter-open');
                }

                function closeFilter() {
                    if (!filterCol || !filterBackdrop) return;
                    filterCol.classList.remove('open');
                    filterBackdrop.classList.remove('open');
                    document.body.classList.remove('filter-open');
                }

                if (filterToggleBtn) {
                    filterToggleBtn.addEventListener('click', openFilter);
                }

                if (filterCloseBtn) {
                    filterCloseBtn.addEventListener('click', closeFilter);
                }

                if (filterBackdrop) {
                    filterBackdrop.addEventListener('click', closeFilter);
                }

                // Закрытие свайпом вниз
                let touchStartY = 0;
                if (filterCol) {
                    filterCol.addEventListener('touchstart', e => {
                        touchStartY = e.changedTouches[0].screenY;
                    });
                    filterCol.addEventListener('touchend', e => {
                        const touchEndY = e.changedTouches[0].screenY;
                        if (touchEndY - touchStartY > 80) {
                            closeFilter();
                        }
                    });
                }

                // ========== ВЫПАДАЮЩИЕ СПИСКИ ==========
                const dropdowns = [
                    {header: 'priceDropdownHeader', content: 'priceDropdownContent'},
                    {header: 'mileageDropdownHeader', content: 'mileageDropdownContent'},
                    {header: 'driveDropdownHeader', content: 'driveDropdownContent'},
                    {header: 'gearboxDropdownHeader', content: 'gearboxDropdownContent'},
                    {header: 'fuelDropdownHeader', content: 'fuelDropdownContent'},
                    {header: 'conditionDropdownHeader', content: 'conditionDropdownContent'},
                    {header: 'ownersDropdownHeader', content: 'ownersDropdownContent'},
                    {header: 'bodyDropdownHeader', content: 'bodyDropdownContent'},
                    {header: 'brandDropdownHeader', content: 'brandDropdownContent'},
                ];

                dropdowns.forEach(({header, content}) => {
                    const h = document.getElementById(header);
                    const c = document.getElementById(content);
                    if (h && c) {
                        h.addEventListener('click', function(e) {
                            e.stopPropagation();
                            dropdowns.forEach(({header: oh, content: oc}) => {
                                if (oh !== header) {
                                    const otherH = document.getElementById(oh);
                                    const otherC = document.getElementById(oc);
                                    if (otherH && otherC) {
                                        otherH.classList.remove('open');
                                        otherC.classList.remove('show');
                                    }
                                }
                            });
                            this.classList.toggle('open');
                            c.classList.toggle('show');
                        });
                    }
                });

                document.addEventListener('click', function(event) {
                    dropdowns.forEach(({header, content}) => {
                        const h = document.getElementById(header);
                        const c = document.getElementById(content);
                        if (h && c && !h.contains(event.target) && !c.contains(event.target)) {
                            h.classList.remove('open');
                            c.classList.remove('show');
                        }
                    });
                });

                // ========== ПОИСК ==========
                const searchInput = document.getElementById('searchInput');
                const searchClear = document.getElementById('searchClear');

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        searchClear.classList.toggle('visible', this.value.length > 0);
                        currentPage = 1;
                        render();
                    });
                }

                if (searchClear) {
                    searchClear.addEventListener('click', function() {
                        searchInput.value = '';
                        this.classList.remove('visible');
                        currentPage = 1;
                        render();
                        searchInput.focus();
                    });
                }

                // ========== ФИЛЬТРАЦИЯ ==========
                const offersGrid = document.getElementById('offersGrid');
                if (!offersGrid) return;

                const allOfferCards = Array.from(offersGrid.querySelectorAll('.offer-card'));
                const originalOrder = [...allOfferCards];
                
                const bodyCheckboxes = document.querySelectorAll('.body-checkbox');
                const brandCheckboxes = document.querySelectorAll('.brand-checkbox');
                const driveCheckboxes = document.querySelectorAll('.drive-checkbox');
                const gearboxCheckboxes = document.querySelectorAll('.gearbox-checkbox');
                const fuelCheckboxes = document.querySelectorAll('.fuel-checkbox');
                const conditionCheckboxes = document.querySelectorAll('.condition-checkbox');
                const ownersCheckboxes = document.querySelectorAll('.owners-checkbox');
                
                const applyBtn = document.getElementById('applyFilters');
                const resetBtn = document.getElementById('resetFilters');
                const priceMin = document.getElementById('priceMin');
                const priceMax = document.getElementById('priceMax');
                const mileageMin = document.getElementById('mileageMin');
                const mileageMax = document.getElementById('mileageMax');
                const paginationEl = document.getElementById('pagination');
                
                let currentPage = 1;
                const itemsPerPage = 8;
                let currentSort = 'new';
                
                const urlParams = new URLSearchParams(window.location.search);
                const bodyParam = urlParams.get('body');
                const brandParam = urlParams.get('brand');
                
                const normalize = s => (s || '').toString().trim().toLowerCase().replace(/\s+/g, ' ');
                
                function getSelectedValues(checkboxes) {
                    return Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value.toLowerCase());
                }
                
                if (bodyParam) {
                    const normalizedBodyParam = bodyParam.trim().toLowerCase();
                    bodyCheckboxes.forEach(cb => {
                        if (cb.value === normalizedBodyParam) cb.checked = true;
                    });
                }
                
                if (brandParam) {
                    const normalizedBrandParam = brandParam.trim().toLowerCase();
                    brandCheckboxes.forEach(cb => {
                        if (cb.value === normalizedBrandParam) cb.checked = true;
                    });
                }
                
                function filterOffers() {
                    const minPrice = parseInt(priceMin?.value) || 0;
                    const maxPrice = parseInt(priceMax?.value) || 0;
                    const minMileage = parseInt(mileageMin?.value) || 0;
                    const maxMileage = parseInt(mileageMax?.value) || 0;
                    const searchQuery = normalize(searchInput?.value);
                    
                    const selectedBodies = getSelectedValues(bodyCheckboxes);
                    const selectedBrands = getSelectedValues(brandCheckboxes);
                    const selectedDrives = getSelectedValues(driveCheckboxes);
                    const selectedGearboxes = getSelectedValues(gearboxCheckboxes);
                    const selectedFuels = getSelectedValues(fuelCheckboxes);
                    const selectedConditions = getSelectedValues(conditionCheckboxes);
                    const selectedOwners = Array.from(ownersCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
                    
                    let filtered = allOfferCards.filter(card => {
                        const brand = normalize(card.dataset.brand);
                        const body = normalize(card.dataset.body);
                        const price = parseInt(card.dataset.price) || 0;
                        const mileage = parseInt(card.dataset.mileage) || 0;
                        const drive = normalize(card.dataset.drive);
                        const gearbox = normalize(card.dataset.gearbox);
                        const fuel = normalize(card.dataset.fuel);
                        const condition = normalize(card.dataset.condition);
                        const owners = card.dataset.owners;
                        const model = normalize(card.dataset.model);
                        
                        if (searchQuery && !model.includes(searchQuery)) return false;
                        if (selectedBrands.length > 0 && !selectedBrands.includes(brand)) return false;
                        if (selectedBodies.length > 0 && !selectedBodies.includes(body)) return false;
                        if (selectedDrives.length > 0 && !selectedDrives.some(d => drive.includes(d))) return false;
                        if (selectedGearboxes.length > 0 && !selectedGearboxes.some(g => gearbox.includes(g))) return false;
                        if (selectedFuels.length > 0 && !selectedFuels.some(f => fuel.includes(f))) return false;
                        if (selectedConditions.length > 0 && !selectedConditions.includes(condition)) return false;
                        
                        if (selectedOwners.length > 0) {
                            let matchOwners = false;
                            if (selectedOwners.includes('0') && owners === '0') matchOwners = true;
                            if (selectedOwners.includes('1-3') && (owners === '1-3' || (!owners && !condition.includes('новый')))) matchOwners = true;
                            if (selectedOwners.includes('3+') && owners === '3+') matchOwners = true;
                            if (!matchOwners) return false;
                        }
                        
                        if (minPrice && price < minPrice) return false;
                        if (maxPrice && price > maxPrice) return false;
                        if (minMileage && mileage < minMileage) return false;
                        if (maxMileage && mileage > maxMileage) return false;
                        
                        return true;
                    });
                    
                    if (currentSort === 'price-asc') {
                        filtered.sort((a, b) => (parseInt(a.dataset.price) || 0) - (parseInt(b.dataset.price) || 0));
                    } else if (currentSort === 'price-desc') {
                        filtered.sort((a, b) => (parseInt(b.dataset.price) || 0) - (parseInt(a.dataset.price) || 0));
                    } else {
                        filtered.sort((a, b) => originalOrder.indexOf(a) - originalOrder.indexOf(b));
                    }
                    
                    return filtered;
                }
                
                function createLi(label, disabled = false, active = false, handler = null) {
                    const li = document.createElement('li');
                    li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
                    const a = document.createElement('a');
                    a.className = 'page-link';
                    a.href = '#';
                    a.textContent = label;
                    a.addEventListener('click', e => {
                        e.preventDefault();
                        if (!disabled && handler) handler();
                    });
                    li.appendChild(a);
                    return li;
                }
                
                function renderPagination(totalItems) {
                    if (!paginationEl) return;
                    paginationEl.innerHTML = '';
                    
                    if (totalItems <= itemsPerPage) return;
                    
                    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
                    
                    paginationEl.appendChild(createLi('<<', currentPage === 1, false, () => {
                        currentPage = 1;
                        render();
                    }));
                    paginationEl.appendChild(createLi('<', currentPage === 1, false, () => {
                        currentPage = Math.max(1, currentPage - 1);
                        render();
                    }));
                    
                    const maxButtons = 7;
                    let start = Math.max(1, currentPage - Math.floor(maxButtons / 2));
                    let end = start + maxButtons - 1;
                    if (end > totalPages) {
                        end = totalPages;
                        start = Math.max(1, end - maxButtons + 1);
                    }
                    
                    if (start > 1) {
                        paginationEl.appendChild(createLi('1', false, 1 === currentPage, () => {
                            currentPage = 1;
                            render();
                        }));
                        if (start > 2) paginationEl.appendChild(createLi('...', true));
                    }
                    
                    for (let p = start; p <= end; p++) {
                        paginationEl.appendChild(createLi(String(p), false, p === currentPage, () => {
                            currentPage = p;
                            render();
                        }));
                    }
                    
                    if (end < totalPages) {
                        if (end < totalPages - 1) paginationEl.appendChild(createLi('...', true));
                        paginationEl.appendChild(createLi(String(totalPages), false, totalPages === currentPage, () => {
                            currentPage = totalPages;
                            render();
                        }));
                    }
                    
                    paginationEl.appendChild(createLi('>', currentPage === totalPages, false, () => {
                        currentPage = Math.min(totalPages, currentPage + 1);
                        render();
                    }));
                    paginationEl.appendChild(createLi('>>', currentPage === totalPages, false, () => {
                        currentPage = totalPages;
                        render();
                    }));
                }
                
                function render() {
                    const filtered = filterOffers();
                    const totalItems = filtered.length;
                    const totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));
                    
                    if (currentPage > totalPages) currentPage = totalPages;
                    if (currentPage < 1) currentPage = 1;
                    
                    allOfferCards.forEach(card => card.style.display = 'none');
                    
                    const start = (currentPage - 1) * itemsPerPage;
                    const end = start + itemsPerPage;
                    filtered.slice(start, end).forEach(card => card.style.display = '');
                    
                    renderPagination(totalItems);
                    
                    const noResultsMsg = offersGrid.querySelector('.no-results-message');
                    if (noResultsMsg) noResultsMsg.remove();
                    
                    if (totalItems === 0) {
                        const message = document.createElement('div');
                        message.className = 'no-results-message p-4 text-center';
                        message.innerHTML = '<em style="color: var(--text-muted);">Ничего не найдено по выбранным фильтрам.</em>';
                        offersGrid.appendChild(message);
                    }
                }

                function resetAll() {
                    bodyCheckboxes.forEach(cb => cb.checked = false);
                    brandCheckboxes.forEach(cb => cb.checked = false);
                    driveCheckboxes.forEach(cb => cb.checked = false);
                    gearboxCheckboxes.forEach(cb => cb.checked = false);
                    fuelCheckboxes.forEach(cb => cb.checked = false);
                    conditionCheckboxes.forEach(cb => cb.checked = false);
                    ownersCheckboxes.forEach(cb => cb.checked = false);
                    if (priceMin) priceMin.value = '';
                    if (priceMax) priceMax.value = '';
                    if (mileageMin) mileageMin.value = '';
                    if (mileageMax) mileageMax.value = '';
                    if (searchInput) {
                        searchInput.value = '';
                        searchClear.classList.remove('visible');
                    }
                    currentPage = 1;
                    render();
                }
                
                if (resetBtn) {
                    resetBtn.addEventListener('click', resetAll);
                }
                
                function applyFilters() {
                    currentPage = 1;
                    render();
                    if (window.innerWidth < 992) {
                        closeFilter();
                    }
                }
                
                if (applyBtn) {
                    applyBtn.addEventListener('click', applyFilters);
                }
                
                [priceMin, priceMax, mileageMin, mileageMax].forEach(input => {
                    if (input) input.addEventListener('input', () => {
                        currentPage = 1;
                        render();
                    });
                });
                
                [...bodyCheckboxes, ...brandCheckboxes, ...driveCheckboxes, ...gearboxCheckboxes, 
                 ...fuelCheckboxes, ...conditionCheckboxes, ...ownersCheckboxes].forEach(cb => {
                    cb.addEventListener('change', () => {
                        currentPage = 1;
                        render();
                    });
                });
                
                render();
            });
        </script>
    @endpush
@endsection