@extends('layouts.app')

@section('title', 'Автокредит')



@section('content')
    <div class="container-xxl wrap">
        <nav class="breadcrumb mt-5">
            <a href="{{ route('home') }}" class="breadcrumb-item">Главная</a>
            <span class="breadcrumb-item active">Автокредит</span>
        </nav>
        <h2 class="text-center mt-5">АВТОКРЕДИТ</h2>
        <div class="title mt-5 mb-4">Подбор автокредита</div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="credit-wrapper">
            <div class="left-card">
                <div class="top-pill">Консультация</div>
                <div class="left-head">
                    <div class="title-small">Рассчитайте автокредит за 2 минуты</div>
                </div>
                <div class="left-content">
                    <div style="margin-bottom:20px;">
                        <div class="label-small">Тип автомобиля</div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="car_type_radio" id="newCar" value="new" {{ old('car_type', 'new') == 'new' ? 'checked' : '' }}>
                                <label class="form-check-label" for="newCar">Новый</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="car_type_radio" id="usedCar" value="used" {{ old('car_type') == 'used' ? 'checked' : '' }}>
                                <label class="form-check-label" for="usedCar">С пробегом</label>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <div class="label-small">Сумма кредита</div>
                        <div class="sum-display" id="sumDisplay">{{ number_format(old('credit_amount', 1000000), 0, '.', ' ') }} ₽</div>
                        <input id="creditRange" type="range" min="100000" max="20000000" step="10000" value="{{ old('credit_amount', 1000000) }}">
                        <div class="range-labels">
                            <span>100 тыс.</span>
                            <span>20 млн.</span>
                        </div>
                    </div>

                    <div class="rate-block">
                        <div>
                            <div class="label-small">Ставка, %</div>
                            <div class="input-group rate-input">
                                <span class="input-group-text">%</span>
                                <input id="rateInput" class="form-control" type="number" step="0.1" min="1" max="50" value="{{ old('interest_rate', 9.5) }}">
                            </div>
                        </div>
                        <div class="monthly">
                            <div class="label-small">Платёж в месяц</div>
                            <div class="sum" id="monthlyAmount">—</div>
                            <div class="muted" id="monthlyNote"></div>
                        </div>
                    </div>

                    <div style="margin: 16px 0;">
                        <div class="label-small">Срок кредита</div>
                        <div class="terms" id="terms">
                            @php $loanTerm = old('loan_term', 4); @endphp
                            <button type="button" class="term-btn {{ $loanTerm == 1 ? 'active' : '' }}" data-years="1">1 год</button>
                            <button type="button" class="term-btn {{ $loanTerm == 2 ? 'active' : '' }}" data-years="2">2 года</button>
                            <button type="button" class="term-btn {{ $loanTerm == 3 ? 'active' : '' }}" data-years="3">3 года</button>
                            <button type="button" class="term-btn {{ $loanTerm == 4 ? 'active' : '' }}" data-years="4">4 года</button>
                            <button type="button" class="term-btn {{ $loanTerm == 5 ? 'active' : '' }}" data-years="5">5 лет</button>
                            <button type="button" class="term-btn {{ $loanTerm == 6 ? 'active' : '' }}" data-years="6">6 лет</button>
                            <button type="button" class="term-btn {{ $loanTerm == 7 ? 'active' : '' }}" data-years="7">7 лет</button>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #f0f2f5;">
                        <div class="label-small">Дополнительно</div>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="opt1" name="insurance_kasko" value="1" {{ old('insurance_kasko') ? 'checked' : '' }}>
                                <label class="form-check-label" for="opt1">КАСКО</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="opt2" name="insurance_as_z" value="1" {{ old('insurance_as_z') ? 'checked' : '' }}>
                                <label class="form-check-label" for="opt2">Страхование жизни</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="opt3" name="early_repayment" value="1" {{ old('early_repayment') ? 'checked' : '' }}>
                                <label class="form-check-label" for="opt3">Досрочное погашение</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <textarea class="form-control" name="notes" rows="2" placeholder="Комментарий" style="resize: vertical; font-size: 0.85rem;">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-card">
                <div class="right-head">
                    Оставить заявку
                    <span>Ответим в течение 15 минут</span>
                </div>

                <form id="creditForm" method="POST" action="{{ route('credit.store') }}">
                    @csrf
                    <input type="hidden" name="car_type" id="car_type" value="{{ old('car_type', 'new') }}">
                    <input type="hidden" name="credit_amount" id="credit_amount" value="{{ old('credit_amount', 1000000) }}">
                    <input type="hidden" name="interest_rate" id="interest_rate" value="{{ old('interest_rate', 9.5) }}">
                    <input type="hidden" name="loan_term" id="loan_term" value="{{ old('loan_term', 4) }}">
                    <input type="hidden" name="monthly_payment" id="monthly_payment" value="{{ old('monthly_payment', '') }}">

                    <!-- ФИО -->
                    <div class="mb-3">
                        <div class="label-small">ФИО</div>
                        <div class="input-icons">
                            <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                <path d="M12 12a4 4 0 100-8 4 4 0 000 8zM4 20a8 8 0 0116 0" stroke="#adb5bd" stroke-width="1.2"/>
                            </svg>
                            @auth
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly style="background: #f8f9fa;">
                                <input type="hidden" name="fio" value="{{ Auth::user()->name }}">
                            @else
                                <input type="text" name="fio" class="form-control" placeholder="Иванов Иван Иванович" value="{{ old('fio') }}">
                            @endauth
                        </div>
                    </div>

                    <!-- Телефон -->
                    <div class="mb-3">
                        <div class="label-small">Телефон</div>
                        <div class="input-icons">
                            <svg viewBox="0 0 24 24" fill="none" width="18" height="18">
                                <path d="M22 16.92v3a2 2 0 01-2.18 2 19.8 19.8 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.8 19.8 0 013.08 4.18 2 2 0 015 2h3a2 2 0 012 1.72c.12.99.38 1.95.77 2.85a2 2 0 01-.45 2.11L9.91 9.91a14 14 0 006 6l1.23-1.23a2 2 0 012.11-.45c.9.39 1.86.65 2.85.77A2 2 0 0122 16.92z" stroke="#adb5bd" stroke-width="1.1"/>
                            </svg>
                            @auth
                                @php
                                    $userPhone = Auth::user()->phone ?? '';
                                    $formattedPhone = '';
                                    if ($userPhone) {
                                        $digits = preg_replace('/\D/', '', $userPhone);
                                        if (strlen($digits) === 11) {
                                            $formattedPhone = '+7 (' . substr($digits, 1, 3) . ') ' . substr($digits, 4, 3) . '-' . substr($digits, 7, 2) . '-' . substr($digits, 9, 2);
                                        } else {
                                            $formattedPhone = $userPhone;
                                        }
                                    }
                                @endphp
                                <input type="text" class="form-control" value="{{ $formattedPhone }}" readonly style="background: #f8f9fa;">
                                <input type="hidden" name="phone" value="{{ $formattedPhone }}">
                            @else
                                <input type="tel" name="phone" class="form-control" placeholder="+7 (___) ___-__-__" value="{{ old('phone') }}">
                            @endauth
                        </div>
                    </div>

                    <!-- Согласие -->
                    <div class="consent mb-3">
                        <input id="consent" name="consent" type="checkbox" value="1" checked>
                        <label for="consent">Согласен на обработку <a href="#">персональных данных</a></label>
                    </div>

                    <button type="submit" class="submit">Отправить заявку</button>
                    <div class="muted text-center mt-2">Никакого спама, только полезная информация</div>
                </form>
            </div>
        </div>

        <!-- Информационные блоки -->
        <div class="credit-info">
            <div class="title mb-5">Чем удобен автокредит</div>
            <div class="trade-in-steps" id="tradeSteps">
                <div class="step-card">
                    <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                    <div class="step-title">Не у всех есть возможность приобрести новый автомобиль или даже подержанный высокого класса, выплатив сразу его полную стоимость.</div>
                    <div class="step-desc">Самый простой способ решения этой проблемы — автокредит. Дилерские центры ФОРСАЖ, расположенные в Казани, предлагают услугу автокредитования на комфортных условиях.</div>
                </div>
                <div class="step-card">
                    <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                    <div class="step-title">По госпрограмме предоставляется скидка 10% при покупке автомобиля 2020 и 2021 годов выпуска на сумму не более 1 500 000 рублей.</div>
                    <div class="step-desc">Граждане, приобретающие авто впервые, семьи и медицинские работники могут воспользоваться государственной поддержкой по определенным программам с выгодными ставками и льготами.</div>
                </div>
            </div>
        </div>

        <div class="container-xxl game-stages mb-5 credit-info" id="stage">
            <div class="title mt-5">Особенности автокредита</div>
            <div class="row gy-sm-5 align-items-stretch">
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">01</div>
                        <div class="game-block d-flex align-items-start">
                            <div class="mt-4">
                                <h3 class="stage-title">Чтобы купить автомобиль в кредит, нужно всего два документа</h3>
                                <p class="stage-description">Паспорт и справка, подтверждающая наличие стабильного дохода.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">02</div>
                        <div class="game-block d-flex align-items-start">
                            <div class="mt-4">
                                <h3 class="stage-title">Транспортное средство может быть оформлено как на заемщика, так и на другое лицо</h3>
                                <p class="stage-description">Если хотите сделать подарок близкому человеку, вам не придется заниматься переоформлением.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">03</div>
                        <div class="game-block d-flex align-items-start">
                            <div class="mt-4">
                                <h3 class="stage-title">Наши специалисты помогут подать заявку в банк</h3>
                                <p class="stage-description">Благодаря сотрудничеству с большим количеством кредитных организаций (более 20 партнеров) процент одобрения заявок на автокредит составляет свыше 95%.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="game-card col-lg-6">
                    <div class="stage-image d-flex justify-content-end">
                        <div class="stage-number">04</div>
                        <div class="game-block d-flex align-items-start">
                            <div class="mt-4">
                                <h3 class="stage-title">Быстрое оформление и выдача авто уже через 1–2 дня</h3>
                                <p class="stage-description">После одобрения заявки автомобиль можно получить в кратчайшие сроки.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Модальное окно успеха --}}
    <div id="successModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px; border: none; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                <div class="modal-body p-4 text-center">
                    <div style="width:56px; height:56px; background:rgba(64,113,203,0.1); border-radius:50%; margin:0 auto 20px; display:flex; align-items:center; justify-content:center;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="#4071CB" stroke-width="1.5"/>
                            <path d="M22 4L12 14.01l-3-3" stroke="#4071CB" stroke-width="1.5"/>
                        </svg>
                    </div>
                    <h5 class="mb-2" style="font-weight:600;">Заявка отправлена!</h5>
                    <p class="mb-3" style="color:#6c757d; font-size:0.9rem;">Мы свяжемся с вами в ближайшее время.</p>
                   <button id="closeModalBtn" class="btn w-100" data-bs-dismiss="modal" style="background:#f3f4f6; color:#374151; border-radius:12px; padding:12px; font-weight:600; font-size:.95rem;">Закрыть</button>
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
        @if(session('success'))
            new bootstrap.Modal(document.getElementById('successModal')).show();
        @endif

        // Калькулятор
        const creditRange = document.getElementById('creditRange');
        const sumDisplay = document.getElementById('sumDisplay');
        const rateInput = document.getElementById('rateInput');
        const monthlyAmount = document.getElementById('monthlyAmount');
        const monthlyNote = document.getElementById('monthlyNote');
        const termBtns = document.querySelectorAll('.term-btn');
        const carTypeRadios = document.querySelectorAll('input[name="car_type_radio"]');
        
        const creditAmountHidden = document.getElementById('credit_amount');
        const interestRateHidden = document.getElementById('interest_rate');
        const loanTermHidden = document.getElementById('loan_term');
        const monthlyPaymentHidden = document.getElementById('monthly_payment');
        const carTypeHidden = document.getElementById('car_type');

        function formatRub(num) {
            return new Intl.NumberFormat('ru-RU').format(num) + ' ₽';
        }

        function calcMonthlyPayment(sum, years, rate) {
            const months = years * 12;
            const monthlyRate = (rate / 100) / 12;
            if (months === 0) return 0;
            if (monthlyRate === 0) return sum / months;
            const pow = Math.pow(1 + monthlyRate, months);
            return sum * (monthlyRate * pow) / (pow - 1);
        }

        function updateRangeTrack() {
            const min = 100000;
            const max = 20000000;
            const val = parseInt(creditRange.value);
            const pct = ((val - min) / (max - min)) * 100;
            creditRange.style.background = `linear-gradient(90deg, #4071CB ${pct}%, #eef0f5 ${pct}%)`;
            sumDisplay.textContent = formatRub(val);
            creditAmountHidden.value = val;
            updateMonthly();
        }

        function updateMonthly() {
            const sum = parseInt(creditRange.value);
            const rate = parseFloat(rateInput.value);
            const activeBtn = document.querySelector('.term-btn.active');
            const years = activeBtn ? parseInt(activeBtn.dataset.years) : 4;
            const payment = calcMonthlyPayment(sum, years, rate);
            
            if (isFinite(payment) && payment > 0) {
                monthlyAmount.textContent = formatRub(Math.round(payment));
                const yearsWord = years === 1 ? 'год' : (years <= 4 ? 'года' : 'лет');
                monthlyNote.textContent = `${rate}% · ${years} ${yearsWord}`;
                monthlyPaymentHidden.value = Math.round(payment);
            } else {
                monthlyAmount.textContent = '—';
                monthlyNote.textContent = '';
            }
        }

        creditRange?.addEventListener('input', updateRangeTrack);
        rateInput?.addEventListener('input', () => {
            interestRateHidden.value = rateInput.value;
            updateMonthly();
        });
        termBtns.forEach(btn => btn.addEventListener('click', function() {
            termBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loanTermHidden.value = this.dataset.years;
            updateMonthly();
        }));
        carTypeRadios.forEach(radio => radio.addEventListener('change', () => carTypeHidden.value = radio.value));

        updateRangeTrack();
        if (interestRateHidden) interestRateHidden.value = rateInput.value;
        
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true, offset: 100 });
    });
</script>
@endpush