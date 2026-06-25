@extends('layouts.app')

@section('title', 'Trade-in')

@push('styles')
    <style>
        h2 {
            font-weight: 600;
            font-size: clamp(1.5rem, 4vw, 30px);
        }

        h3 {
            font-weight: 600;
            font-size: clamp(1.25rem, 3.5vw, 24px);
            line-height: 1.5;
        }

        .floating-label-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #4071CB;
    font-size: 16px;
    z-index: 2;
    pointer-events: none;
}

.floating-label-group .form-control {
    padding-left: 40px;
}

.floating-label-group label {
    left: 40px;
}

.floating-label-group .form-control:read-only {
    padding-left: 40px;
}
    </style>
@endpush

@section('content')

    <div class="trade-in container-xxl">
        <nav class="breadcrumb mt-5">
            <a href="{{ route('home') }}" class="breadcrumb-item">Главная</a>
            <span class="breadcrumb-item active">Trade-in</span>
        </nav>
        <h2 class="mb-5 mt-5 text-center">TRADE-IN</h2>

        <div class="trade-in-steps" id="tradeSteps">
            <div class="step-card">
                <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                <div class="step-title">1. Выбор автомобиля</div>
                <div class="step-desc">Подбор идеальной модели с учётом ваших предпочтений и бюджета</div>
            </div>

            <div class="step-card">
                <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                <div class="step-title">2. Заполнение анкеты</div>
                <div class="step-desc">Быстрое оформление заявки с указанием ваших контактных данных</div>
            </div>

            <div class="step-card">
                <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                <div class="step-title">3. Диагностика авто</div>
                <div class="step-desc">Профессиональная проверка состояния и рыночной стоимости</div>
            </div>

            <div class="step-card">
                <img src="{{ asset('assets/images/trade-in/car.svg') }}" alt="Автомобиль" />
                <div class="step-title">4. Получение нового авто</div>
                <div class="step-desc">Торжественная передача нового авто с полным пакетом документов</div>
            </div>
        </div>
    </div>

    <!-- Форма оценки -->
    <div class="container-xxl my-100">
        <div class="row g-4 align-items-center justify-content-between">
            <div class="col-md-7 d-flex flex-column trade justify-content-between">
                <h3 class="mb-4">Предварительная оценка стоимости <br> Вашего автомобиля</h3>

                <div class="contact-title d-flex align-items-center gap-3">
                    <div class="svg">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M15.1435 10.145C15.1435 10.5579 15.0621 10.9667 14.9041 11.3481C14.7461 11.7296 14.5146 12.0762 14.2226 12.3681C13.9307 12.6601 13.5841 12.8917 13.2026 13.0497C12.8212 13.2077 12.4123 13.289 11.9995 13.289C11.5866 13.289 11.1778 13.2077 10.7963 13.0497C10.4149 12.8917 10.0683 12.6601 9.77633 12.3681C9.48438 12.0762 9.25279 11.7296 9.09479 11.3481C8.93679 10.9667 8.85547 10.5579 8.85547 10.145C8.85547 9.31114 9.18671 8.51145 9.77633 7.92183C10.3659 7.33222 11.1656 7.00098 11.9995 7.00098C12.8333 7.00098 13.633 7.33222 14.2226 7.92183C14.8122 8.51145 15.1435 9.31114 15.1435 10.145Z"
                                stroke="#4071CB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M6.54769 21.5569C6.02771 20.9166 5.73343 20.1227 5.71046 19.2982V19.132C5.74015 18.1349 6.1643 17.1903 6.8898 16.5057C7.6153 15.821 8.58285 15.4522 9.58 15.4803H14.4169C15.4144 15.452 16.3823 15.8208 17.108 16.5057C17.8338 17.1905 18.2579 18.1355 18.2874 19.1329V19.2991C18.2639 20.1238 17.969 20.9178 17.4483 21.5578M6.54769 21.5569C8.20776 22.5058 10.0873 23.0035 11.9994 23.0006C13.9822 23.0006 15.8422 22.4754 17.4483 21.5578M6.54769 21.5569C4.86191 20.5952 3.46067 19.2045 2.4863 17.526C1.51193 15.8476 0.999132 13.9411 1 12.0003C0.999078 5.92554 5.92369 1 11.9994 1C18.0751 1 22.9997 5.92646 22.9997 12.0003C22.9997 16.0932 20.764 19.6637 17.4483 21.5578"
                                stroke="#4071CB" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <p class="contact m-0">Ваши контактные данные:</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form id="tradeInForm" method="POST" action="{{ route('trade-in.store') }}">
                    @csrf

                    <div class="row g-3 mt-4">
                        <div class="col-md-6 mt-0">
                            <div class="floating-label-group">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                @auth
                                    <input type="text" id="name" name="name"
                                        class="form-control" placeholder=" "
                                        value="{{ Auth::user()->name }}" readonly>
                                    <label for="name">Ваше имя</label>
                                    <input type="hidden" name="name" value="{{ Auth::user()->name }}">
                                @else
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') error @enderror" placeholder=" "
                                        value="{{ old('name') }}" maxlength="50" required>
                                    <label for="name">Ваше имя</label>
                                @endauth
                            </div>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-0">
                            <div class="floating-label-group">
                                <span class="input-icon"><i class="fas fa-phone"></i></span>
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
                                    <input type="tel" id="phone" name="phone"
                                        class="form-control" placeholder=" "
                                        value="{{ $formattedPhone }}" readonly>
                                    <label for="phone">Номер телефона</label>
                                    <input type="hidden" name="phone" value="{{ $formattedPhone }}">
                                @else
                                    <input type="tel" id="phone" name="phone"
                                        class="form-control @error('phone') error @enderror" placeholder=" "
                                        value="{{ old('phone') }}" required>
                                    <label for="phone">Номер телефона</label>
                                @endauth
                            </div>
                            @error('phone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="custom-select @error('dealer_center') error @enderror" id="dc-select">
                                <div class="select-header">
                                    <span class="selected-text">{{ old('dealer_center') ?: 'Выбрать ДЦ' }}</span>
                                    <i class="fas fa-chevron-down chevron"></i>
                                </div>
                                <ul class="select-dropdown">
                                    <li data-value="г. Казань, ул. Ямашева, д. 76"
                                        {{ old('dealer_center') == 'г. Казань, ул. Ямашева, д. 76' ? 'class=selected' : '' }}>
                                        г. Казань, ул. Ямашева, д. 76
                                    </li>
                                    <li data-value="г. Казань, ул. Чистопольская, д. 9а"
                                        {{ old('dealer_center') == 'г. Казань, ул. Чистопольская, д. 9а' ? 'class=selected' : '' }}>
                                        г. Казань, ул. Чистопольская, д. 9а
                                    </li>
                                </ul>
                                <input type="hidden" id="dc" name="dealer_center"
                                    value="{{ old('dealer_center') }}">
                            </div>
                            @error('dealer_center')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100 h-100">Оценить бесплатно</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-5">
                <div class="car-image">
                    <img src="{{ asset('assets/images/trade-in/image.png') }}" alt="Автомобиль" class="img-fluid" />
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl my-100">
        <h3 class="mb-4">Необходимые документы</h3>

        <div class="row g-4 doc-list" id="docList">
            <div class="col-6 col-md-6 col-lg-4 d-flex">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">01</div>
                    <div class="doc-title">Паспорт транспортного средства (ПТС)</div>
                    <div class="doc-desc">оригинал или дубликат</div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 d-flex">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">02</div>
                    <div class="doc-title">Свидетельство о регистрации автомобиля</div>
                    <div class="doc-desc">если машина не была снята с учета</div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 d-flex doc-hidden">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">03</div>
                    <div class="doc-title">Личный паспорт</div>
                    <div class="doc-desc">собственника автомобиля</div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 d-flex doc-hidden">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">04</div>
                    <div class="doc-title">Генеральная доверенность</div>
                    <div class="doc-desc">(если вы не являетесь собственником)</div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 d-flex doc-hidden">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">05</div>
                    <div class="doc-title">Все комплекты ключей</div>
                    <div class="doc-desc">оригинал и дубликат</div>
                </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 d-flex doc-hidden">
                <div class="doc-card w-100 h-100">
                    <div class="doc-number">06</div>
                    <div class="doc-title">Документы о сервисном обслуживании</div>
                    <div class="doc-desc">(желательно)</div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 d-lg-none">
            <button class="btn btn-primary doc-toggle" id="docToggle">Посмотреть ещё</button>
        </div>
    </div>

    <!-- MODAL: Заявка отправлена -->
    <div id="successModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border-radius:16px; border: none; box-shadow: 0 12px 32px rgba(0,0,0,0.12);">
                <div class="modal-body p-4 text-center">
                    <div
                        style="width:64px; height:64px; background:rgba(47,107,240,0.12); border-radius:50%; margin:0 auto 20px; display:flex; align-items:center; justify-content:center;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 11.08V12a10 10 0 11-5.93-9.14" stroke="#2f6bf0" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M22 4L12 14.01l-3-3" stroke="#2f6bf0" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h5 class="mb-3" style="color:#1f2937; font-weight:700;">Заявка отправлена!</h5>
                    <p class="mb-4" style="color:#4b5563; font-size:.95rem;">Мы свяжемся с вами в ближайшее время. <br>А
                        пока вы можете начать диалог в чате.</p>

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                var successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            @endif

            // Инициализация кастомных селектов
            const selects = document.querySelectorAll('.custom-select');
            selects.forEach(select => {
                const header = select.querySelector('.select-header');
                const dropdown = select.querySelector('.select-dropdown');
                const selectedText = select.querySelector('.selected-text');
                const hiddenInput = select.querySelector('input[type="hidden"]');
                const chevron = select.querySelector('.chevron');
                const items = dropdown.querySelectorAll('li');

                header.addEventListener('click', function(e) {
                    e.stopPropagation();
                    header.classList.toggle('active');
                    if (chevron) chevron.style.transform = header.classList.contains('active') ?
                        'rotate(180deg)' : 'rotate(0)';
                });

                items.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.getAttribute('data-value').trim();
                        const text = this.textContent;
                        if (selectedText) selectedText.textContent = text;
                        if (hiddenInput) hiddenInput.value = value;
                        items.forEach(el => el.classList.remove('selected'));
                        this.classList.add('selected');
                        header.classList.remove('active');
                        if (chevron) chevron.style.transform = 'rotate(0)';
                        select.classList.remove('error');
                    });
                });

                document.addEventListener('click', function() {
                    if (header.classList.contains('active')) {
                        header.classList.remove('active');
                        if (chevron) chevron.style.transform = 'rotate(0)';
                    }
                });
            });

            // Функция форматирования телефона (для неавторизованных)
            function formatPhoneNumber(value) {
                let digits = value.replace(/\D/g, '');
                if (digits.startsWith('8')) digits = '7' + digits.substring(1);
                if (digits.length > 0 && !digits.startsWith('7')) digits = '7' + digits;
                digits = digits.substring(0, 11);
                
                let formatted = '';
                if (digits.length > 0) {
                    formatted = '+7';
                    if (digits.length > 1) {
                        formatted += ' (' + digits.substring(1, 4);
                        if (digits.length > 4) {
                            formatted += ') ' + digits.substring(4, 7);
                            if (digits.length > 7) {
                                formatted += '-' + digits.substring(7, 9);
                                if (digits.length > 9) {
                                    formatted += '-' + digits.substring(9, 11);
                                }
                            }
                        } else if (digits.length > 1) {
                            formatted += ')';
                        }
                    }
                }
                return formatted;
            }

            // Маска для телефона (только для неавторизованных)
            @guest
            const phoneInput = document.getElementById("phone");
            if (phoneInput) {
                phoneInput.addEventListener("input", function(e) {
                    const formatted = formatPhoneNumber(this.value);
                    this.value = formatted;
                });

                phoneInput.addEventListener('focus', function() {
                    if (!this.value.trim()) {
                        this.value = '+7 (';
                        this.setSelectionRange(4, 4);
                    }
                });

                phoneInput.addEventListener('blur', function() {
                    const phoneRegex = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
                    if (this.value && !phoneRegex.test(this.value)) {
                        this.classList.add('error');
                        const parent = this.closest('.floating-label-group');
                        if (parent && !parent.querySelector('.error-message')) {
                            const errorElement = document.createElement('span');
                            errorElement.className = 'error-message';
                            errorElement.textContent = 'Введите номер в формате: +7 (XXX) XXX-XX-XX';
                            parent.appendChild(errorElement);
                        }
                    } else {
                        this.classList.remove('error');
                        const parent = this.closest('.floating-label-group');
                        const existingError = parent?.querySelector('.error-message');
                        if (existingError) existingError.remove();
                    }
                });
            }
            @endguest

            // Валидация имени в реальном времени (только для неавторизованных)
            @guest
            const nameInput = document.getElementById("name");
            if (nameInput && !nameInput.readOnly) {
                nameInput.addEventListener('input', function() {
                    if (this.value.length > 50) {
                        this.value = this.value.substring(0, 50);
                    }

                    const nameRegex = /^[а-яА-ЯёЁa-zA-Z\s\-]*$/;
                    if (this.value && !nameRegex.test(this.value)) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });

                nameInput.addEventListener('blur', function() {
                    if (this.value && this.value.length < 2) {
                        this.classList.add('error');
                    }
                });
            }
            @endguest

            // Обработка формы Trade-in
            const tradeInForm = document.getElementById('tradeInForm');
            const submitBtn = tradeInForm ? tradeInForm.querySelector('button[type="submit"]') : null;

            if (tradeInForm && submitBtn) {
                tradeInForm.addEventListener('submit', function(e) {
                    let isValid = true;

                    document.querySelectorAll('.error-message').forEach(el => el.remove());
                    document.querySelectorAll('.form-control.error').forEach(el => el.classList.remove('error'));
                    document.querySelectorAll('.custom-select.error').forEach(el => el.classList.remove('error'));

                    const formData = new FormData(tradeInForm);
                    const name = formData.get('name');
                    const phone = formData.get('phone');
                    const dealer_center = formData.get('dealer_center');

                    // Валидация имени (только если поле не readonly)
                    const nameField = document.getElementById('name');
                    if (nameField && !nameField.readOnly) {
                        if (!name) {
                            showFieldError('name', 'Поле "Ваше имя" обязательно для заполнения');
                            isValid = false;
                        } else if (name.length < 2) {
                            showFieldError('name', 'Имя должно содержать минимум 2 символа');
                            isValid = false;
                        } else if (name.length > 50) {
                            showFieldError('name', 'Имя не должно превышать 50 символов');
                            isValid = false;
                        } else if (!/^[а-яА-ЯёЁa-zA-Z\s\-]+$/.test(name)) {
                            showFieldError('name', 'Имя должно содержать только буквы, пробелы и дефисы');
                            isValid = false;
                        }
                    }

                    // Валидация телефона (только если поле не readonly)
                    const phoneField = document.getElementById('phone');
                    if (phoneField && !phoneField.readOnly) {
                        if (!phone) {
                            showFieldError('phone', 'Поле "Номер телефона" обязательно для заполнения');
                            isValid = false;
                        } else {
                            const phoneRegex = /^\+7 \(\d{3}\) \d{3}-\d{2}-\d{2}$/;
                            if (!phoneRegex.test(phone)) {
                                showFieldError('phone', 'Введите корректный номер телефона в формате: +7 (XXX) XXX-XX-XX');
                                isValid = false;
                            }
                        }
                    }

                    if (!dealer_center) {
                        showFieldError('dc-select', 'Пожалуйста, выберите дилерский центр');
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        return;
                    }
                });
            }

            function showFieldError(fieldId, message) {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.classList.add('error');
                    const errorElement = document.createElement('span');
                    errorElement.className = 'error-message';
                    errorElement.textContent = message;
                    const parent = field.closest('.col-md-6') || field.closest('.col-md-12') || field.parentElement;
                    if (parent) {
                        parent.appendChild(errorElement);
                    }
                } else {
                    // Для кастомного селекта
                    const selectContainer = document.getElementById('dc-select');
                    if (selectContainer) {
                        selectContainer.classList.add('error');
                        const errorElement = document.createElement('span');
                        errorElement.className = 'error-message';
                        errorElement.textContent = message;
                        selectContainer.parentElement.appendChild(errorElement);
                    }
                }
            }

            // Обработчики для модального окна
            document.getElementById('closeModalBtn')?.addEventListener('click', function() {
                const modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
                if (modal) modal.hide();
            });

            const toggleBtn = document.getElementById('docToggle');
            const docList = document.getElementById('docList');

            if (toggleBtn && docList) {
                toggleBtn.addEventListener('click', () => {
                    docList.classList.add('expanded');
                    toggleBtn.style.display = 'none';
                    docList.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                });
            }

            const steps = document.getElementById('tradeSteps');
            if (steps) {
                steps.scrollLeft = 0;
                setTimeout(() => {
                    steps.scrollLeft = 0;
                }, 50);
            }

            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                offset: 100
            });
        });
    </script>
@endpush