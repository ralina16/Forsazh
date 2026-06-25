@extends('layouts.app')

@section('title', 'Автострахование')


@section('content')
    <div class="container-xxl wrap">
        <nav class="breadcrumb mt-5">
            <a href="{{ route('home') }}" class="breadcrumb-item">Главная</a>
            <span class="breadcrumb-item active">Автострахование</span>
        </nav>
        <h2 class="text-center mt-5">АВТОСТРАХОВАНИЕ</h2>
        <div class="title mt-5 mb-4">Подбор автострахования на выгодных условиях</div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- БЛОК КАЛЬКУЛЯТОРА И ФОРМЫ -->
        <div class="credit-wrapper">
            <div class="left-card">
                <div class="top-pill">Получить консультацию</div>
                <div class="left-head">
                    <div class="title-small">Рассчитайте стоимость страхования за 2 минуты</div>
                </div>
                <div class="left-content">
                    <div style="margin-bottom:20px;">
                        <div class="label-small">Тип страхования</div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="carType" id="newCar" value="osago"
                                    checked>
                                <label class="form-check-label" for="newCar">ОСАГО</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="carType" id="usedCar" value="kasko">
                                <label class="form-check-label" for="usedCar">КАСКО</label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <div class="label-small">Стоимость автомобиля</div>
                        <div class="sum-display" id="sumDisplay">1 000 000 ₽</div>
                        <input id="creditRange" type="range" min="100000" max="20000000" step="10000" value="1000000">
                        <div class="range-labels">
                            <span>100 тыс.</span>
                            <span>20 млн.</span>
                        </div>
                    </div>

                    <div style="margin: 16px 0;">
                        <div class="label-small">Возраст автомобиля</div>
                        <div class="terms" id="terms">
                            <button type="button" class="term-btn" data-years="1" data-age="1-3 года">1-3 года</button>
                            <button type="button" class="term-btn" data-years="2" data-age="3-5 лет">3-5 лет</button>
                            <button type="button" class="term-btn" data-years="3" data-age="5-9 лет">5-9 лет</button>
                            <button type="button" class="term-btn active" data-years="4" data-age="10+ лет">10+
                                лет</button>
                        </div>
                    </div>

                    <div class="insurance-result">
                        <div>
                            <div class="label-small">Годовая премия</div>
                            <div class="premium-amount" id="premiumAmount">—</div>
                        </div>
                        <div class="d-flex align-items-center" style="gap:12px;">
                            <svg class="risk-circle" viewBox="0 0 36 36" width="56" height="56">
                                <circle class="ring-bg" cx="18" cy="18" r="16" stroke-width="3"
                                    fill="none"></circle>
                                <circle class="ring-fg" cx="18" cy="18" r="16" stroke-width="3" fill="none"
                                    stroke-linecap="round"></circle>
                            </svg>
                            <div>
                                <div class="label-small">Риск</div>
                                <div id="riskLabel" class="muted" style="font-weight:600;">—</div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center" style="margin-top: 8px;">
                        <div class="label-small">В месяц</div>
                        <div id="premiumMonthly" class="muted" style="font-weight:700; color:#1a1a1a;">—</div>
                    </div>
                </div>
            </div>

            <div class="right-card">
                <div class="right-head">
                    Оставить заявку
                    <span>и получить лучшие условия страхования</span>
                </div>

                <form method="POST" action="{{ route('insurance.store') }}" id="insuranceForm">
                    @csrf
                    <input type="hidden" name="insurance_type" id="insurance_type" value="osago">
                    <input type="hidden" name="car_price" id="car_price" value="1000000">
                    <input type="hidden" name="car_age" id="car_age" value="10+ лет">
                    <input type="hidden" name="estimated_premium" id="estimated_premium" value="">
                    <input type="hidden" name="monthly_payment" id="monthly_payment" value="">
                    <input type="hidden" name="risk_level" id="risk_level" value="">

                    <div class="mb-3">
                        <div class="label-small">Имя <span style="color:#d02626">*</span></div>
                        <div class="input-icons">
                            <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                <path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 20a8 8 0 0116 0" stroke="#adb5bd"
                                    stroke-width="1.2" />
                            </svg>
                            @auth
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly
                                    style="background: #f8f9fa;">
                                <input type="hidden" name="fio" value="{{ Auth::user()->name }}">
                            @else
                                <input id="fio" name="fio" class="form-control" type="text"
                                    placeholder="Введите ФИО" value="{{ old('fio') }}">
                            @endauth
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="label-small">Номер телефона <span style="color:#d02626">*</span></div>
                        <div class="input-icons">
                            <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                <path
                                    d="M22 16.92v3a2 2 0 01-2.18 2 19.8 19.8 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.8 19.8 0 013.08 4.18 2 2 0 015 2h3a2 2 0 012 1.72c.12.99.38 1.95.77 2.85a2 2 0 01-.45 2.11L9.91 9.91a14 14 0 006 6l1.23-1.23a2 2 0 012.11-.45c.9.39 1.86.65 2.85.77A2 2 0 0122 16.92z"
                                    stroke="#adb5bd" stroke-width="1.1" />
                            </svg>
                            @auth
                                @php
                                    $userPhone = Auth::user()->phone ?? '';
                                    $formattedPhone = '';
                                    if ($userPhone) {
                                        $digits = preg_replace('/\D/', '', $userPhone);
                                        if (strlen($digits) === 11) {
                                            $formattedPhone =
                                                '+7 (' .
                                                substr($digits, 1, 3) .
                                                ') ' .
                                                substr($digits, 4, 3) .
                                                '-' .
                                                substr($digits, 7, 2) .
                                                '-' .
                                                substr($digits, 9, 2);
                                        } else {
                                            $formattedPhone = $userPhone;
                                        }
                                    }
                                @endphp
                                <input type="text" id="phone" class="form-control" value="{{ $formattedPhone }}"
                                    readonly style="background: #f8f9fa;">
                                <input type="hidden" name="phone" value="{{ $formattedPhone }}">
                            @else
                                <input id="phone" name="phone" class="form-control" type="tel"
                                    placeholder="+7 (___) ___-__-__" value="{{ old('phone') }}">
                            @endauth
                        </div>
                    </div>

                    <div class="consent mb-3">
                        <input id="consent" name="consent" type="checkbox" value="1" checked>
                        <label for="consent">Даю согласие на обработку <a href="#">персональных данных</a></label>
                    </div>

                    <button type="submit" class="submit" id="submitBtn">Отправить заявку</button>
                    <div class="muted text-center mt-2">Никакого спама, только полезная информация</div>
                </form>
            </div>
        </div>

        <div class="insurance-info">
            <div class="container-xxl">
                <div class="section-header">
                    <div class="top-pill"
                        style="background: rgba(64, 113, 203, 0.08); width: fit-content; margin: 0 auto 1rem;">Выберите
                        защиту</div>
                    <h2 class="title mb-3">Виды страхования</h2>
                    <p class="section-subtitle">ОСАГО или КАСКО – подберём оптимальный вариант</p>
                </div>
                <div class="insurance-row" style="display: flex; flex-wrap: wrap; gap: 2rem;">
                    <!-- Карточка ОСАГО -->
                    <div class="card-container insurance-card" style="flex: 1; min-width: 280px;">
                        <div class="card-body insurance-card-body">
                            <div class="insurance-number">01</div>
                            <h3 class="insurance-title">ОСАГО</h3>
                            <p class="insurance-desc">Обязательная защита ответственности перед другими участниками ДТП.
                            </p>
                            <ul class="insurance-features">
                                <li><strong>Выплаты пострадавшим:</strong> до 400 000 ₽</li>
                                <li><strong>Два варианта:</strong> с ограничением водителей / без</li>
                                <li><strong>Сроки действия:</strong> 3, 6 или 12 месяцев</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Карточка КАСКО -->
                    <div class="card-container insurance-card" style="flex: 1; min-width: 280px;">
                        <div class="card-body insurance-card-body">
                            <div class="insurance-number">02</div>
                            <h3 class="insurance-title">КАСКО</h3>
                            <p class="insurance-desc">Добровольное страхование автомобиля от угона, ущерба и ДТП.</p>
                            <ul class="insurance-features">
                                <li><strong>Покрытие:</strong> ущерб даже по вашей вине</li>
                                <li><strong>Полная защита:</strong> угон, стихия, противоправные действия</li>
                                <li><strong>Исключения:</strong> алкоголь, оставление места ДТП</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-xxl game-stages" id="stage">
            <h2 class="title mb-4">Особенности автострахования</h2>
            <p class="mb-3 insurance-title">
                Договор страхования необходим для защиты финансовых интересов участников ДТП. В зависимости от типа
                страхования
                могут быть компенсированы убытки, понесенные как пострадавшим водителем, так и виновником аварии.
            </p>
            <p class="mb-4 insurance-title">
                Оформить страховой полис на транспортное средство может любая компания, которая имеет лицензию на такую
                деятельность.
            </p>
            <p class="mb-4 insurance-title">
                Страхование бывает:
            </p>
            <div class="row gy-sm-5 align-items-stretch">
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">01</div>
                        <div class="game-blocks d-flex align-items-start">
                            <div class="mt-2">
                                <h3 class="stage-title">Обязательным (ОСАГО)</h3>
                                <p class="stage-descriptions mb-0">
                                    Отсутствие полиса обязательного страхования является основанием для привлечения
                                    автовладельца к административной ответственности.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">02</div>
                        <div class="game-blocks d-flex align-items-start">
                            <div class="mt-2">
                                <h3 class="stage-title">Добровольным (КАСКО)</h3>
                                <p class="stage-descriptions mb-0">
                                    Позволяет компенсировать ущерб, понесенный при попадании в ДТП по собственной вине, а
                                    также
                                    в результате несчастного случая.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <p class="mb-3 insurance-title">
                    Для удобства клиентов крупные автосалоны также заключают договоры с автостраховщиками, предоставляя их
                    агентам офисы на своей территории. Это позволяет при покупке автомобиля сразу же оформить страховой
                    полис
                    ОСАГО, без которого эксплуатация транспортного средства запрещена, а при желании — и документ КАСКО.
                </p>
                <p class="mb-0 insurance-title">
                    При аварии убытки по восстановлению застрахованного авто полностью или в большей степени возмещает
                    страховая
                    компания.
                </p>
            </div>
        </div>
    </div>

    <!-- Модальное окно успеха -->
    <div id="successModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:16px; border: none; box-shadow: 0 12px 32px rgba(0,0,0,0.12);">
                <div class="modal-body p-4 text-center">
                    <div
                        style="width:64px; height:64px; background:rgba(47,107,240,0.12); border-radius:50%; margin:0 auto 20px; display:flex; align-items:center; justify-content:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="#2f6bf0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22 4L12 14.01l-3-3" stroke="#2f6bf0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="mb-3" style="color:#1f2937; font-weight:700;">Заявка отправлена!</h5>
                    <p class="mb-4" style="color:#4b5563; font-size:.95rem;">Мы свяжемся с вами в ближайшее время. <br>А
                        пока вы можете начать диалог в чате.</p>
                    <button id="closeModalBtn" class="btn w-100" data-bs-dismiss="modal"
                        style="background:#f3f4f6; color:#374151; border-radius:12px; padding:12px; font-weight:600; font-size:.95rem;">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <section class="container-xxl mb-5">
        @include('partials.footer')
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Калькулятор страхования
            const creditRange = document.getElementById('creditRange');
            const sumDisplay = document.getElementById('sumDisplay');
            const premiumAmount = document.getElementById('premiumAmount');
            const premiumMonthly = document.getElementById('premiumMonthly');
            const riskLabel = document.getElementById('riskLabel');
            const ringFg = document.querySelector('.ring-fg');
            const carTypeInputs = document.querySelectorAll('input[name="carType"]');
            const termButtons = document.querySelectorAll('.term-btn');
            const insuranceTypeInput = document.getElementById('insurance_type');
            const carPriceInput = document.getElementById('car_price');
            const carAgeInput = document.getElementById('car_age');
            const estimatedPremiumInput = document.getElementById('estimated_premium');
            const monthlyPaymentInput = document.getElementById('monthly_payment');
            const riskLevelInput = document.getElementById('risk_level');

            function formatRub(num) {
                return Number(num).toLocaleString('ru-RU') + ' ₽';
            }

            function updateRangeTrack() {
                const min = Number(creditRange.min || 0);
                const max = Number(creditRange.max || 100);
                const val = Number(creditRange.value);
                const perc = Math.round(((val - min) / (max - min)) * 100);
                creditRange.style.background = `linear-gradient(90deg, #4071CB ${perc}%, #e9ecef ${perc}%)`;
            }

            function updateInsurancePreview() {
                const value = Number(creditRange.value || 0);
                const type = document.querySelector('input[name="carType"]:checked')?.value || 'osago';
                const activeBtn = document.querySelector('.term-btn.active');
                const activeYears = activeBtn?.dataset.years || '4';
                const ageLabels = {
                    '1': '1-3 года',
                    '2': '3-5 лет',
                    '3': '5-9 лет',
                    '4': '10+ лет'
                };

                insuranceTypeInput.value = type;
                carPriceInput.value = value;
                carAgeInput.value = ageLabels[activeYears];

                const baseRate = (type === 'osago') ? 0.0025 : 0.01;
                const ageFactorMap = {
                    '1': 1.0,
                    '2': 1.05,
                    '3': 1.12,
                    '4': 1.25
                };
                const ageFactor = ageFactorMap[activeYears] || 1.0;

                const premium = Math.max(0, Math.round(value * baseRate * ageFactor));
                const monthly = Math.round(premium / 12);

                premiumAmount.textContent = formatRub(premium);
                premiumMonthly.textContent = formatRub(monthly);

                estimatedPremiumInput.value = premium;
                monthlyPaymentInput.value = monthly;

                const min = Number(creditRange.min || 0);
                const max = Number(creditRange.max || 1);
                const valueScore = ((value - min) / (max - min)) * 60;
                const ageScore = (ageFactor - 1) * 100;
                let riskPercent = Math.round(Math.min(100, valueScore + ageScore));
                let riskText = riskPercent > 65 ? 'высокий' : (riskPercent > 40 ? 'средний' : 'низкий');
                riskLabel.textContent = `${riskText} (${riskPercent}%)`;
                riskLevelInput.value = riskText;

                if (ringFg) {
                    const radius = 16;
                    const circumference = 2 * Math.PI * radius;
                    ringFg.style.strokeDasharray = `${circumference}`;
                    ringFg.style.strokeDashoffset = `${circumference - (riskPercent / 100) * circumference}`;
                }
            }

            if (creditRange) {
                creditRange.addEventListener('input', () => {
                    sumDisplay.textContent = formatRub(creditRange.value);
                    updateRangeTrack();
                    updateInsurancePreview();
                });
                carTypeInputs.forEach(inp => inp.addEventListener('change', updateInsurancePreview));
                termButtons.forEach(btn => btn.addEventListener('click', () => {
                    termButtons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    updateInsurancePreview();
                }));
                updateRangeTrack();
                updateInsurancePreview();
            }

            // Закрытие модального окна
            const closeModalBtn = document.getElementById('closeModalBtn');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    const modalEl = document.getElementById('successModal');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                });
            }

            @if (session('success'))
                new bootstrap.Modal(document.getElementById('successModal')).show();
            @endif
        });
    </script>
@endpush
