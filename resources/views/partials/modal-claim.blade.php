<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
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
                                stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>
                    <div class="svg-vector-1">
                        <svg width="648" height="451" viewBox="0 0 648 451" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M892.5 1.4448C892.5 1.4448 616.816 -10.5824 556.5 90.4448C496.184 191.472 615.99 217.473 556.5 345.445C497.01 473.417 179.633 359.331 103.5 461.945C27.3667 564.559 112.745 599.207 50 681.945C-12.7447 764.683 -274 735.445 -274 735.445"
                                stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </div>

                    <h2 class="modal-title">ОСТАВИТЬ ЗАЯВКУ</h2>
                    <div class="divider"></div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="forms">
                        <form method="POST" action="{{ route('requests.store') }}" class="active" novalidate>
                            @csrf

                            {{-- Типы заявок (чекбоксы) --}}
                            <div class="mb-4 mt-4">
                                <p class="fields-label">Тип заявки <span class="text-danger">*</span></p>
                                <div class="request-tags {{ $errors->has('request_type') ? 'error' : '' }}">
                                    <label class="tag">
                                        <input type="checkbox" name="request_type[]" value="test-drive"
                                            {{ in_array('test-drive', old('request_type', [])) ? 'checked' : '' }}>
                                        <span>Тест-драйв</span>
                                    </label>
                                    <label class="tag">
                                        <input type="checkbox" name="request_type[]" value="consultation"
                                            {{ in_array('consultation', old('request_type', [])) ? 'checked' : '' }}>
                                        <span>Консультация</span>
                                    </label>
                                    <label class="tag">
                                        <input type="checkbox" name="request_type[]" value="car-selection"
                                            {{ in_array('car-selection', old('request_type', [])) ? 'checked' : '' }}>
                                        <span>Подбор авто</span>
                                    </label>
                                    <label class="tag">
                                        <input type="checkbox" name="request_type[]" value="credit"
                                            {{ in_array('credit', old('request_type', [])) ? 'checked' : '' }}>
                                        <span>Запись на сервис</span>
                                    </label>
                                </div>
                                @error('request_type')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Имя --}}
                            <div class="mb-4">
                                <div class="field {{ $errors->has('name') ? 'error' : '' }}" data-name="name">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <input type="text" id="request-name" name="name" value="{{ old('name') }}"
                                        placeholder=" " required minlength="2">
                                    <label for="request-name">Имя *</label>
                                    <span class="status"></span>
                                </div>
                                @error('name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Телефон --}}
                            <div class="mb-4">
                                <div class="field {{ $errors->has('phone') ? 'error' : '' }}" data-name="phone">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.3-.1-.7 0-1 .2l-2.2 2.2c-2.8-1.4-5.1-3.8-6.6-6.6l2.2-2.2c.3-.3.4-.7.2-1-.3-1.1-.5-2.3-.5-3.5 0-.6-.4-1-1-1H4c-.6 0-1 .4-1 1 0 9.4 7.6 17 17 17 .6 0 1-.4 1-1v-3.5c0-.6-.4-1-1-1z" />
                                        </svg>
                                    </div>
                                    <input type="tel" id="request-phone" name="phone" value="{{ old('phone') }}"
                                        placeholder=" " required>
                                    <label for="request-phone">Телефон *</label>
                                    <span class="status"></span>
                                </div>
                                @error('phone')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email (необязательно) --}}
                            <div class="mb-4">
                                <div class="field {{ $errors->has('email') ? 'error' : '' }}" data-name="email">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path
                                                d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                    </div>
                                    <input type="email" id="request-email" name="email"
                                        value="{{ old('email') }}" placeholder=" ">
                                    <label for="request-email">Email</label>
                                </div>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Согласие на обработку --}}
                            <div class="consent {{ $errors->has('agree') ? 'error' : '' }}">
                                <input type="checkbox" id="request-agree" name="agree" value="1"
                                    {{ old('agree') ? 'checked' : '' }} required>
                                <label for="request-agree">
                                    Я согласен с
                                    <a href="{{ asset('assets/documents/politics.docx') }}" target="_blank"
                                        rel="noopener">
                                        политикой конфиденциальности
                                    </a> *
                                </label>
                            </div>
                            @error('agree')
                                <div class="field-error">{{ $message }}</div>
                            @enderror

                            <button type="submit" class="submit mt-2">ОТПРАВИТЬ ЗАЯВКУ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .svg-vector,
    .svg-vector-1 {
        pointer-events: none;
        position: absolute;
    }

    .modal-inner-box .content {
        position: relative;
        z-index: 2 !important;
    }

    .modal-backdrop.show {
        opacity: 0.5 !important;
    }

    .modal.show {
        display: block !important;
        overflow-x: hidden;
        overflow-y: auto;
    }


    .reviews-track {
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
    }

    .reviews-track:focus {
        outline: none;
    }

    .rating-stars {
        display: flex;
        gap: 8px;
    }

    .star {
        font-size: 28px;
        color: #e0e0e0;
        cursor: pointer;
        transition: all 0.2s ease;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .star.active {
        color: #4071CB;
        text-shadow: 0 2px 4px rgba(64, 113, 203, 0.3);
    }

    .star:hover {
        transform: scale(1.1);
    }

    .char-count {
        text-align: right;
        font-size: 12px;
        color: #666;
        margin-top: 4px;
    }

    .field.error {
        border-color: #dc3545 !important;
    }

    .field.error .status::after {
        content: '✗';
        color: #dc3545;
    }

    .consent.error {
        border: none;
        border-radius: 0;
        padding: 0;
        background-color: transparent;
    }

    .field-error {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: block;
        font-weight: 500;
    }

    .review-card {
        flex: 0 0 calc(50% - 12px);
        min-width: 0;
        border: 1px solid #dedede;
        border-radius: 12px;
        padding: 1.5rem;
        min-height: 220px;
        background: transparent url("assets/images/reviews/vector.svg") no-repeat right 0 bottom 0;
        background-size: 150px !important;
    }

    @media (max-width: 768px) {
        .review-card {
            flex: 0 0 100%;
            min-width: 100%;
            margin-bottom: 16px;
        }

        .reviews-container {
            gap: 16px !important;
        }
    }

    .review-date {
        margin-top: 10px;
        font-size: 12px;
        color: #6c757d;
    }

    .review-stars {
        color: #4071CB;
        font-size: 18px;
        letter-spacing: 2px;
    }

    .review-text {
        color: #6c757d;
        line-height: 1.5;
        margin-bottom: 1rem;
        flex-grow: 1;
        word-wrap: break-word;
        overflow-wrap: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 6;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .read-more-btn {
        background: none;
        border: none;
        color: #4071CB;
        cursor: pointer;
        font-size: 14px;
        padding: 0;
        text-decoration: underline;
        transition: color 0.2s ease;
    }

    .read-more-btn:hover {
        color: #2c5bb4;
    }

    .full-review-header {
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
    }

    .full-review-text {
        line-height: 1.6;
        color: #333;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    @media (max-width: 768px) {
        .rating-stars {
            gap: 4px;
        }

        .star {
            font-size: 24px;
            width: 24px;
            height: 24px;
        }

        .review-text {
            font-size: 14px;
        }
    }

    .field-error {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        animation: slideDown 0.5s ease-in-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .submit {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1.6px solid #4071CB;
        background: rgba(255, 255, 255, 0.327);
        color: var(--blue, #4071CB);
        font-weight: 600;
        font-size: clamp(14px, 2.5vw, 16px);
        cursor: pointer;
        transition: all .14s;
    }

    .submit:hover {
        background: #4071CB !important;
        color: #F1F0EB;
    }

    .mt-2 {
        margin-top: 0.5rem;
    }

    .mb-4 {
        margin-bottom: 1rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateFieldStatus(fieldEl, isValid) {
            if (!fieldEl) return;

            const statusSpan = fieldEl.querySelector('.status');
            if (!statusSpan) return;

            statusSpan.innerHTML = '';

            if (!isValid) {
                fieldEl.classList.add('error');
                fieldEl.classList.remove('ok');
            } else {
                fieldEl.classList.remove('error');
                const input = fieldEl.querySelector('input');
                if (input && input.value.trim()) {
                    fieldEl.classList.add('ok');
                } else {
                    fieldEl.classList.remove('ok');
                }
            }
        }

        // Маска для телефона в модалке заявки
        function initRequestPhoneMask() {
            const phoneInput = document.querySelector('#requestModal #request-phone');
            if (!phoneInput) return;

            if (phoneInput._maskHandler) {
                phoneInput.removeEventListener('input', phoneInput._maskHandler);
            }

            function formatPhoneNumber(value) {
                let digits = value.replace(/\D/g, '');

                if (digits.startsWith('8')) {
                    digits = '7' + digits.substring(1);
                }

                if (digits.length > 0 && !digits.startsWith('7')) {
                    digits = '7' + digits;
                }

                if (digits.length >= 2 && digits[1] !== '9') {
                    digits = digits[0] + '9' + digits.substring(2);
                }

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

            function maskHandler(e) {
                const cursorPos = e.target.selectionStart;
                const oldLength = phoneInput.value.length;
                const formatted = formatPhoneNumber(phoneInput.value);
                phoneInput.value = formatted;
                const newLength = formatted.length;
                const diff = newLength - oldLength;
                phoneInput.setSelectionRange(cursorPos + diff, cursorPos + diff);

                const digits = phoneInput.value.replace(/\D/g, '');
                const isValid = digits.length === 11 && digits[0] === '7' && digits[1] === '9';
                const fieldDiv = phoneInput.closest('.field');
                updateFieldStatus(fieldDiv, isValid);

                const existingError = fieldDiv?.nextElementSibling;
                if (isValid && existingError && existingError.classList.contains('field-error')) {
                    existingError.remove();
                }
            }

            function focusHandler() {
                if (!phoneInput.value.trim()) {
                    phoneInput.value = '+7 (9';
                    phoneInput.setSelectionRange(5, 5);
                }
            }

            function blurHandler() {
                const digits = phoneInput.value.replace(/\D/g, '');
                const isValid = digits.length === 11 && digits[0] === '7' && digits[1] === '9';
                const fieldDiv = phoneInput.closest('.field');

                if (!isValid && digits.length > 0) {
                    updateFieldStatus(fieldDiv, false);
                    if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Введите номер в формате: +7 (9XX) XXX-XX-XX';
                        fieldDiv.after(errDiv);
                    }
                } else if (!isValid && digits.length === 0) {
                    updateFieldStatus(fieldDiv, false);
                    if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Телефон обязателен для заполнения';
                        fieldDiv.after(errDiv);
                    }
                } else {
                    updateFieldStatus(fieldDiv, true);
                    const existingError = fieldDiv?.nextElementSibling;
                    if (existingError && existingError.classList.contains('field-error')) {
                        existingError.remove();
                    }
                }
            }

            phoneInput._maskHandler = maskHandler;
            phoneInput.addEventListener('input', maskHandler);
            phoneInput.addEventListener('focus', focusHandler);
            phoneInput.addEventListener('blur', blurHandler);

            if (phoneInput.value) {
                phoneInput.dispatchEvent(new Event('input'));
            }
        }

        // Функция для валидации имени
        function validateName() {
            const nameInput = document.querySelector('#requestModal #request-name');
            if (!nameInput) return;

            const fieldDiv = nameInput.closest('.field');
            const isValid = nameInput.value.trim().length >= 2;

            updateFieldStatus(fieldDiv, isValid);

            const existingError = fieldDiv?.nextElementSibling;
            if (!isValid && nameInput.value.trim().length > 0 && nameInput.value.trim().length < 2) {
                if (!existingError?.classList.contains('field-error')) {
                    const errDiv = document.createElement('div');
                    errDiv.className = 'field-error';
                    errDiv.textContent = 'Имя должно содержать минимум 2 символа';
                    fieldDiv?.after(errDiv);
                }
            } else if (!isValid && !nameInput.value.trim()) {
                if (!existingError?.classList.contains('field-error')) {
                    const errDiv = document.createElement('div');
                    errDiv.className = 'field-error';
                    errDiv.textContent = 'Имя обязательно для заполнения';
                    fieldDiv?.after(errDiv);
                }
            } else if (isValid && existingError?.classList.contains('field-error')) {
                existingError.remove();
            }
        }

        // Функция для валидации email
        function validateEmail() {
            const emailInput = document.querySelector('#requestModal #request-email');
            if (!emailInput) return;

            const fieldDiv = emailInput.closest('.field');
            const value = emailInput.value.trim();
            let isValid = true;

            if (value) {
                const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                isValid = emailPattern.test(value);
            }

            updateFieldStatus(fieldDiv, isValid);

            const existingError = fieldDiv?.nextElementSibling;
            if (!isValid && value) {
                if (!existingError?.classList.contains('field-error')) {
                    const errDiv = document.createElement('div');
                    errDiv.className = 'field-error';
                    errDiv.textContent = 'Введите корректный email';
                    fieldDiv?.after(errDiv);
                }
            } else if (isValid && existingError?.classList.contains('field-error')) {
                existingError.remove();
            }
        }

        // Автоматическое отображение модалки при ошибках валидации
        @if ($errors->any())
            const requestModal = document.getElementById('requestModal');
            if (requestModal) {
                const modal = new bootstrap.Modal(requestModal);
                modal.show();
            }
        @endif

        // Инициализация при загрузке
        initRequestPhoneMask();

        // Добавляем обработчики для имени
        const nameInput = document.querySelector('#requestModal #request-name');
        if (nameInput) {
            nameInput.addEventListener('input', validateName);
            nameInput.addEventListener('blur', validateName);
            if (nameInput.value) validateName();
        }

        // Добавляем обработчики для email
        const emailInput = document.querySelector('#requestModal #request-email');
        if (emailInput) {
            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('blur', validateEmail);
            if (emailInput.value) validateEmail();
        }

        const requestModal = document.getElementById('requestModal');
        if (requestModal) {
            requestModal.addEventListener('shown.bs.modal', function() {
                initRequestPhoneMask();
                const phoneInput = this.querySelector('#request-phone');
                if (phoneInput && phoneInput.value) {
                    phoneInput.dispatchEvent(new Event('input'));
                }
                if (nameInput && nameInput.value) validateName();
                if (emailInput && emailInput.value) validateEmail();
            });
        }

        // Клиентская валидация перед отправкой
        const requestForm = document.querySelector('#requestModal form');
        if (requestForm) {
            requestForm.addEventListener('submit', function(e) {
                let isValid = true;

                // Удаляем старые ошибки
                this.querySelectorAll('.field-error').forEach(el => el.remove());
                this.querySelectorAll('.field').forEach(f => f.classList.remove('error'));
                this.querySelector('.consent')?.classList.remove('error');
                this.querySelector('.request-tags')?.classList.remove('error');

                // Проверка типа заявки
                const requestTypes = this.querySelectorAll('input[name="request_type[]"]:checked');
                if (requestTypes.length === 0) {
                    isValid = false;
                    const tagsDiv = this.querySelector('.request-tags');
                    if (tagsDiv && !tagsDiv.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Выберите хотя бы один тип заявки';
                        tagsDiv.after(errDiv);
                    }
                    tagsDiv?.classList.add('error');
                }

                // Проверка имени
                if (nameInput && (!nameInput.value.trim() || nameInput.value.trim().length < 2)) {
                    isValid = false;
                    const fieldDiv = nameInput.closest('.field');
                    fieldDiv?.classList.add('error');
                    if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = nameInput.value.trim() ?
                            'Имя должно содержать минимум 2 символа' : 'Имя обязательно для заполнения';
                        fieldDiv.after(errDiv);
                    }
                }

                // Проверка телефона
                const phoneInput = document.querySelector('#request-phone');
                if (phoneInput) {
                    const digits = phoneInput.value.replace(/\D/g, '');
                    const isPhoneValid = digits.length === 11 && digits[0] === '7' && digits[1] === '9';
                    if (!isPhoneValid) {
                        isValid = false;
                        const fieldDiv = phoneInput.closest('.field');
                        fieldDiv?.classList.add('error');
                        if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains(
                            'field-error')) {
                            const errDiv = document.createElement('div');
                            errDiv.className = 'field-error';
                            errDiv.textContent = digits.length === 0 ?
                                'Телефон обязателен для заполнения' :
                                'Введите номер в формате: +7 (9XX) XXX-XX-XX';
                            fieldDiv.after(errDiv);
                        }
                    }
                }

                // Проверка email
                if (emailInput && emailInput.value.trim()) {
                    const emailPattern = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                    if (!emailPattern.test(emailInput.value.trim())) {
                        isValid = false;
                        const fieldDiv = emailInput.closest('.field');
                        fieldDiv?.classList.add('error');
                        if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains(
                            'field-error')) {
                            const errDiv = document.createElement('div');
                            errDiv.className = 'field-error';
                            errDiv.textContent = 'Введите корректный email';
                            fieldDiv.after(errDiv);
                        }
                    }
                }

                // Проверка согласия
                const agreeCheckbox = document.querySelector('#request-agree');
                if (agreeCheckbox && !agreeCheckbox.checked) {
                    isValid = false;
                    const consentDiv = agreeCheckbox.closest('.consent');
                    consentDiv?.classList.add('error');
                    if (consentDiv && !consentDiv.nextElementSibling?.classList.contains(
                        'field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Необходимо согласие с политикой конфиденциальности';
                        consentDiv.after(errDiv);
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                }
            });
        }
    });
</script>
