
<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-top">
        <div class="modal-content">
            <div class="svg-vector">
                <svg width="421" height="578" viewBox="0 0 421 578" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M-133.22 1.40758C-133.22 1.40758 124.267 100.639 139.145 217.357C154.023 334.075 33.8168 309.992 37.1622 451.076C40.5076 592.16 361.784 651.036 405.765 739.023C449.745 827.01 371.441 913.95 371.441 913.95" stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>
            <div class="svg-vector-1">
                <svg width="648" height="451" viewBox="0 0 648 451" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M892.5 1.4448C892.5 1.4448 616.816 -10.5824 556.5 90.4448C496.184 191.472 615.99 217.473 556.5 345.445C497.01 473.417 179.633 359.331 103.5 461.945C27.3667 564.559 112.745 599.207 50 681.945C-12.7447 764.683 -274 735.445 -274 735.445" stroke="white" stroke-opacity="0.8" stroke-width="2" stroke-linecap="round" />
                </svg>
            </div>

            <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Закрыть">
                <svg viewBox="0 0 24 24">
                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                </svg>
            </button>

            <div class="modal-inner-box">
                <div class="content">
                    {{-- Табы переключения --}}
                    <div class="tabs-wraps">
                        <div class="tabs-bg">
                            <input type="radio" id="tab-reg" name="auth_tab" value="reg" {{ old('auth_tab', 'reg') === 'reg' ? 'checked' : '' }}>
                            <label class="bg-tab {{ old('auth_tab', 'reg') === 'reg' ? 'active' : '' }}" for="tab-reg">РЕГИСТРАЦИЯ</label>
                            <input type="radio" id="tab-auth" name="auth_tab" value="auth" {{ old('auth_tab') === 'auth' ? 'checked' : '' }}>
                            <label class="bg-tab {{ old('auth_tab') === 'auth' ? 'active' : '' }}" for="tab-auth">АВТОРИЗАЦИЯ</label>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="forms">
                        {{-- Форма регистрации --}}
                        <form class="reg {{ old('auth_tab', 'reg') === 'reg' ? 'active' : '' }}" novalidate method="POST" action="{{ route('register') }}">
                            @csrf
                            <input type="hidden" name="auth_tab" value="reg">

                            {{-- Имя --}}
                            <div class="mb-4 mt-4">
                                <div class="field" data-name="name">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="25" height="25" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <input id="reg-name" name="name" type="text" placeholder=" " required minlength="4" maxlength="100" autocomplete="name" value="{{ old('name') }}" />
                                    <label for="reg-name">Ваше имя</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-4">
                                <div class="field" data-name="email">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                    </div>
                                    <input id="reg-email" name="email" type="email" placeholder=" " required autocomplete="email" value="{{ old('email') }}" />
                                    <label for="reg-email">Email</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Код подтверждения email --}}
                            <div class="mb-4" id="email-verification-block" style="display: none;">
                                <div class="field" data-name="email_code">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                                        </svg>
                                    </div>
                                    <input id="reg-email-code" name="email_code" type="text" placeholder=" " required maxlength="6" autocomplete="off" inputmode="numeric" value="{{ old('email_code') }}" />
                                    <label for="reg-email-code">Код из письма</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                <div class="verification-timer">
                                    <span id="timer-text"></span>
                                    <button type="button" id="resend-code" style="display: none;">Отправить код повторно</button>
                                </div>
                                @error('email_code')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Телефон --}}
                            <div class="mb-4">
                                <div class="field" data-name="phone">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" />
                                        </svg>
                                    </div>
                                    <input id="reg-phone" name="phone" type="tel" placeholder=" " required minlength="10" maxlength="20" autocomplete="tel" value="{{ old('phone') }}" />
                                    <label for="reg-phone">Номер телефона <span class="required">*</span></label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('phone')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Пароль --}}
                            <div class="mb-4">
                                <div class="field" data-name="password">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                        </svg>
                                    </div>
                                    <input id="reg-password" name="password" type="password" placeholder=" " required minlength="6" autocomplete="new-password" />
                                    <label for="reg-password">Пароль</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Подтверждение пароля --}}
                            <div class="mb-4">
                                <div class="field" data-name="confirm">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                            <path d="M10 15l-3-3 1.41-1.41L10 12.17l3.59-3.59L15 10l-5 5z" fill="currentColor" />
                                        </svg>
                                    </div>
                                    <input id="reg-confirm" name="password_confirmation" type="password" placeholder=" " required minlength="6" autocomplete="new-password" />
                                    <label for="reg-confirm">Повторите пароль</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('password_confirmation')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Согласие --}}
                            <div class="consent {{ $errors->has('agree') ? 'error' : '' }}">
                                <input type="checkbox" id="request-agree" name="agree" value="1" {{ old('agree') ? 'checked' : '' }} required>
                                <label for="request-agree">
                                    Я согласен с
                                    <a href="{{ asset('assets/documents/politics.docx') }}" target="_blank" rel="noopener">
                                        политикой конфиденциальности
                                    </a> *
                                </label>
                            </div>
                            @error('agree')
                                <div class="field-error">{{ $message }}</div>
                            @enderror

                            <button class="submit mt-2" type="submit" id="reg-submit-btn">ЗАРЕГИСТРИРОВАТЬСЯ</button>
                        </form>

                        {{-- Форма авторизации --}}
                        <form class="auth {{ old('auth_tab') === 'auth' ? 'active' : '' }}" novalidate method="POST" action="{{ route('login') }}">
                            @csrf
                            <input type="hidden" name="auth_tab" value="auth">

                            {{-- Email --}}
                            <div class="mb-4 mt-4">
                                <div class="field" data-name="email-auth">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                                        </svg>
                                    </div>
                                    <input id="auth-email" name="email" type="email" placeholder=" " required autocomplete="email" value="{{ old('email') }}" />
                                    <label for="auth-email">Email</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('email')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Пароль --}}
                            <div class="mb-4">
                                <div class="field" data-name="pass-auth">
                                    <div class="left-icon" aria-hidden="true">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                        </svg>
                                    </div>
                                    <input id="auth-password" name="password" type="password" placeholder=" " required autocomplete="current-password" />
                                    <label for="auth-password">Пароль</label>
                                    <span class="status" aria-hidden="true"></span>
                                </div>
                                @error('password')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                                @error('general')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="auth-helpers">
                                <button type="submit" class="submit">ВОЙТИ</button>
                                <div class="switch-prompt">
                                    Нет аккаунта? <a href="#" class="switch-link" onclick="document.getElementById('tab-reg').click(); return false;">Зарегистрируйтесь</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .submit {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: 1.6px solid #4071CB;
        background: rgba(255, 255, 255, 0.327);
        color: var(--blue);
        font-weight: 600;
        font-size: clamp(14px, 2.5vw, 16px);
        cursor: pointer;
        transition: all .14s;
    }

    .tabs-wraps{
        width: 100% !important;
    }

    .submit:hover {
        background: #4071CB !important;
        color: #F1F0EB;
    }

    .forms form {
        display: none;
    }

    .forms form.active {
        display: block;
    }

    .field.error {
        border-color: #dc3545 !important;
    }

    .field.error .status::after {
        content: '✗';
        color: #dc3545;
    }

    .consent.error {
        border: 1px solid #dc3545;
        border-radius: 4px;
        padding: 5px;
    }

    .field-error {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: block;
    }

    .fade-out {
        opacity: 0;
        transition: opacity 0.5s ease-out;
    }

    .modal-backdrop {
        pointer-events: none;
    }

    .modal.fade .modal-dialog {
        transform: none;
    }

    .verification-timer {
        font-size: 12px;
        color: #666;
        margin-top: 5px;
    }

    .verification-timer button {
        background: none;
        border: none;
        color: #4071CB;
        cursor: pointer;
        text-decoration: underline;
        font-size: 12px;
        padding: 0;
    }

    @media (max-width: 768px) {
        .tabs-wraps { margin: 0 0 20px; }
        .tabs-bg { padding: 3px; }
        .bg-tab { font-size: 13px; padding: 12px 6px; border-radius: 8px; letter-spacing: 0.5px; }
        .tab:not(.active) { font-size: 13px; }
    }

    @media (max-width: 480px) {
        .tabs-bg { padding: 2px; }
        .bg-tab { font-size: 12px; padding: 10px 4px; border-radius: 7px; letter-spacing: 0.3px; font-weight: 700; }
        .tab:not(.active) { font-size: 12px; }
    }

    @media (max-width: 380px) {
        .bg-tab { font-size: 11px; padding: 9px 3px; }
        .bg-tab:not(.active) { font-size: 11px; }
    }

    @media (max-height: 500px) and (max-width: 768px) {
        .tabs-wraps { margin: 0 0 15px; }
        .bg-tab { padding: 8px 4px; font-size: 11px; }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const authModal = document.getElementById('authModal');
        const tabReg = document.getElementById('tab-reg');
        const tabAuth = document.getElementById('tab-auth');
        const regForm = document.querySelector('form.reg');
        const authForm = document.querySelector('form.auth');

        function updateCsrfToken() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                document.querySelectorAll('#authModal form input[name="_token"]').forEach(input => {
                    input.value = csrfToken;
                });
            }
        }

        authModal?.addEventListener('show.bs.modal', function() {
            updateCsrfToken();
        });

        document.querySelectorAll('#authModal form').forEach(form => {
            form.addEventListener('submit', function() {
                updateCsrfToken();
            });
        });

        // Переключение форм
        function switchForms() {
            regForm?.classList.toggle('active', tabReg?.checked);
            authForm?.classList.toggle('active', tabAuth?.checked);
            document.querySelectorAll('.tabs-bg label').forEach(tab => tab.classList.remove('active'));
            if (tabReg?.checked) {
                document.querySelector('label[for="tab-reg"]')?.classList.add('active');
            } else {
                document.querySelector('label[for="tab-auth"]')?.classList.add('active');
            }
        }

        tabReg?.addEventListener('change', switchForms);
        tabAuth?.addEventListener('change', switchForms);
        switchForms();

        // Показ модалки при ошибках
        @if ($errors->any() || session('auth_error'))
            const modal = new bootstrap.Modal(authModal);
            modal.show();
            const activeTab = "{{ old('auth_tab', 'reg') }}";
            if (activeTab === 'auth') {
                document.getElementById('tab-auth').checked = true;
            } else {
                document.getElementById('tab-reg').checked = true;
            }
            switchForms();
            updateCsrfToken();
        @endif

        // Блокировка закрытия при ошибках
        authModal?.addEventListener('hide.bs.modal', function(event) {
            const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
            if (hasErrors) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        });

        const svgOk = '<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M9 16.2 4.8 12 3.4 13.4l5.6 5.6L20.6 8.4 19.2 7z"/></svg>';
        const svgErr = '<svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M18.3 5.7 12 12l6.3 6.3-1.4 1.4L10.6 13.4 4.3 19.7 2.9 18.3 9.3 11.9 2.9 5.5 4.3 4.1 10.6 10.4 17 4z"/></svg>';

        function validateField(fieldEl) {
            if (!fieldEl) return { valid: true };
            const input = fieldEl.querySelector('input');
            if (!input) return { valid: true };

            let valid = input.checkValidity() && input.value.trim() !== '';
            const value = input.value.trim();

            if (input.id === 'reg-name') valid = value.length >= 4;
            if (input.id === 'reg-password') valid = value.length >= 6;
            if (input.id === 'reg-confirm') valid = value === document.getElementById('reg-password')?.value;
            if (input.id === 'reg-email-code') valid = /^\d{6}$/.test(value);
            if (input.type === 'checkbox') valid = input.checked;

            fieldEl.classList.remove('ok', 'err');
            if (value || input.type === 'checkbox') {
                fieldEl.classList.add(valid ? 'ok' : 'err');
            }

            const status = fieldEl.querySelector('.status');
            if (status) {
                status.innerHTML = '';
                if (value || input.type === 'checkbox') {
                    status.innerHTML = valid ? svgOk : svgErr;
                }
            }
            return { valid };
        }

        document.querySelectorAll('.field input').forEach(input => {
            input.addEventListener('input', () => validateField(input.closest('.field')));
            input.addEventListener('blur', () => validateField(input.closest('.field')));
        });

        // ========== МАСКА ТЕЛЕФОНА ==========
        const regPhoneInput = document.getElementById('reg-phone');
        if (regPhoneInput) {
            function formatPhoneNumber(value) {
                let digits = value.replace(/\D/g, '');
                if (digits.startsWith('8')) digits = '7' + digits.substring(1);
                if (digits.length > 0 && !digits.startsWith('7')) digits = '7' + digits;
                if (digits.length >= 2 && digits[1] !== '9') digits = digits[0] + '9' + digits.substring(2);
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
                                if (digits.length > 9) formatted += '-' + digits.substring(9, 11);
                            }
                        } else if (digits.length > 1) {
                            formatted += ')';
                        }
                    }
                }
                return formatted;
            }

            regPhoneInput.addEventListener('input', function(e) {
                const cursorPos = e.target.selectionStart;
                const oldLength = this.value.length;
                const formatted = formatPhoneNumber(this.value);
                this.value = formatted;
                const newLength = formatted.length;
                const diff = newLength - oldLength;
                this.setSelectionRange(cursorPos + diff, cursorPos + diff);
                this.classList.remove('error');
                const fieldDiv = this.closest('.field');
                const existingError = fieldDiv?.nextElementSibling;
                if (existingError && existingError.classList.contains('field-error')) existingError.remove();
            });

            regPhoneInput.addEventListener('focus', function() {
                if (!this.value.trim()) {
                    this.value = '+7 (9';
                    this.setSelectionRange(5, 5);
                }
            });

            regPhoneInput.addEventListener('blur', function() {
                const digits = this.value.replace(/\D/g, '');
                const isValid = digits.length === 11 && digits[0] === '7' && digits[1] === '9';
                if (!isValid && digits.length > 0) {
                    this.classList.add('error');
                    const fieldDiv = this.closest('.field');
                    if (fieldDiv && !fieldDiv.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Введите номер в формате: +7 (9XX) XXX-XX-XX';
                        fieldDiv.after(errDiv);
                    }
                } else {
                    this.classList.remove('error');
                    const fieldDiv = this.closest('.field');
                    const existingError = fieldDiv?.nextElementSibling;
                    if (existingError && existingError.classList.contains('field-error')) existingError.remove();
                }
            });
        }

               // ========== ПОДТВЕРЖДЕНИЕ EMAIL (двухэтапная регистрация) ==========
        const emailCodeBlock = document.getElementById('email-verification-block');
        const emailCodeInput = document.getElementById('reg-email-code');
        const resendBtn = document.getElementById('resend-code');
        const timerText = document.getElementById('timer-text');
        const regSubmitBtn = document.getElementById('reg-submit-btn');
        let codeSent = false;
        let verificationDisabled = false;
        let timerInterval;

        function startTimer(duration = 60) {
            let remaining = duration;
            resendBtn.style.display = 'none';
            timerText.textContent = `Повторная отправка через ${remaining} сек`;

            clearInterval(timerInterval);
            timerInterval = setInterval(() => {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(timerInterval);
                    timerText.textContent = '';
                    resendBtn.style.display = 'inline';
                } else {
                    timerText.textContent = `Повторная отправка через ${remaining} сек`;
                }
            }, 1000);
        }

        async function sendVerificationCode() {
            const emailInput = document.getElementById('reg-email');
            const email = emailInput.value.trim();

            if (!email || !emailInput.checkValidity()) {
                const fieldDiv = emailInput.closest('.field');
                fieldDiv?.classList.add('err');
                let errDiv = fieldDiv?.nextElementSibling;
                if (!errDiv?.classList.contains('field-error')) {
                    errDiv = document.createElement('div');
                    errDiv.className = 'field-error';
                    errDiv.textContent = 'Введите корректный email';
                    fieldDiv?.after(errDiv);
                }
                emailInput.focus();
                return { disabled: false, sent: false };
            }

            regSubmitBtn.disabled = true;
            regSubmitBtn.textContent = 'Отправка кода...';

            try {
                const response = await fetch('{{ route("verification.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Если проверка отключена администратором
                    if (data.disabled) {
                        verificationDisabled = true;
                        codeSent = true;
                        emailCodeBlock.style.display = 'none';
                        regSubmitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                        return { disabled: true, sent: true };
                    }

                    // Обычный режим — показываем поле кода
                    verificationDisabled = false;
                    emailCodeBlock.style.display = 'block';
                    emailCodeInput.focus();
                    codeSent = true;
                    regSubmitBtn.textContent = 'ПОДТВЕРДИТЬ И ЗАРЕГИСТРИРОВАТЬСЯ';
                    startTimer(60);
                    return { disabled: false, sent: true };
                } else {
                    alert(data.message || 'Ошибка отправки кода');
                    codeSent = false;
                    regSubmitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                    return { disabled: false, sent: false };
                }
            } catch (e) {
                alert('Ошибка сети. Попробуйте позже.');
                codeSent = false;
                regSubmitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                return { disabled: false, sent: false };
            } finally {
                regSubmitBtn.disabled = false;
            }
        }

        resendBtn?.addEventListener('click', sendVerificationCode);

        // Сброс состояния при изменении email
        document.getElementById('reg-email')?.addEventListener('input', function() {
            if (codeSent) {
                codeSent = false;
                verificationDisabled = false;
                emailCodeBlock.style.display = 'none';
                emailCodeInput.value = '';
                regSubmitBtn.textContent = 'ЗАРЕГИСТРИРОВАТЬСЯ';
                clearInterval(timerInterval);
                timerText.textContent = '';
                resendBtn.style.display = 'none';
            }
        });

        if (emailCodeInput) {
            emailCodeInput.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '').substring(0, 6);
                validateField(this.closest('.field'));
            });
            emailCodeInput.addEventListener('blur', function() {
                validateField(this.closest('.field'));
            });
        }

        // ========== ОБРАБОТКА SUBMIT ФОРМ ==========
        document.querySelectorAll('.forms form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                form.querySelectorAll('.field-error').forEach(el => el.remove());
                form.querySelectorAll('.field').forEach(f => f.classList.remove('err', 'ok'));
                form.querySelector('.consent')?.classList.remove('error');

                let isValid = true;

                form.querySelectorAll('.field').forEach(fieldEl => {
                    if (fieldEl.closest('#email-verification-block') && emailCodeBlock.style.display === 'none') {
                        return;
                    }

                    const res = validateField(fieldEl);
                    if (!res.valid) {
                        isValid = false;
                        let msg = '';
                        const input = fieldEl.querySelector('input');
                        if (input?.id === 'reg-name') msg = 'Имя минимум 4 символа';
                        else if (input?.id === 'reg-password') msg = 'Пароль минимум 6 символов';
                        else if (input?.id === 'reg-confirm') msg = 'Пароли не совпадают';
                        else if (input?.id === 'reg-email-code') msg = 'Введите 6-значный код из письма';
                        else if (input?.type === 'checkbox') msg = 'Необходимо согласие';
                        else if (input?.required && !input.value.trim()) msg = 'Заполните поле';

                        if (msg && !fieldEl.nextElementSibling?.classList.contains('field-error')) {
                            const errDiv = document.createElement('div');
                            errDiv.className = 'field-error';
                            errDiv.textContent = msg;
                            fieldEl.after(errDiv);
                        }
                    }
                });

                const agree = form.querySelector('input[name="agree"]');
                if (agree && !agree.checked) {
                    isValid = false;
                    const consentField = agree.closest('.consent');
                    consentField?.classList.add('error');
                    if (!consentField?.nextElementSibling?.classList.contains('field-error')) {
                        const errDiv = document.createElement('div');
                        errDiv.className = 'field-error';
                        errDiv.textContent = 'Необходимо согласие с политикой конфиденциальности';
                        consentField?.after(errDiv);
                    }
                }

                if (!isValid) {
                    authModal?.setAttribute('data-bs-backdrop', 'static');
                    authModal?.setAttribute('data-bs-keyboard', 'false');
                    return;
                }

                // === РЕГИСТРАЦИЯ: двухэтапная логика ===
                if (form.classList.contains('reg')) {
                    if (!codeSent) {
                        const result = await sendVerificationCode();
                        
                        if (result.disabled) {
                        } else if (!result.sent) {
                            return;
                        } else {
                            return;
                        }
                    }

                    // Если код требуется, но не введен
                    if (emailCodeBlock.style.display !== 'none' && !emailCodeInput.value.trim()) {
                        emailCodeBlock.style.display = 'block';
                        emailCodeInput.focus();
                        const fieldEl = emailCodeInput.closest('.field');
                        fieldEl?.classList.add('err');
                        if (!fieldEl?.nextElementSibling?.classList.contains('field-error')) {
                            const errDiv = document.createElement('div');
                            errDiv.className = 'field-error';
                            errDiv.textContent = 'Введите код подтверждения из письма';
                            fieldEl?.after(errDiv);
                        }
                        return;
                    }
                }

                // === AJAX отправка формы ===
                const formData = new FormData(this);
                const action = this.getAttribute('action');

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        authModal?.removeAttribute('data-bs-backdrop');
                        authModal?.removeAttribute('data-bs-keyboard');
                        const modalInstance = bootstrap.Modal.getInstance(authModal);
                        modalInstance?.hide();

                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        authModal?.setAttribute('data-bs-backdrop', 'static');
                        authModal?.setAttribute('data-bs-keyboard', 'false');

                        if (data.errors) {
                            Object.entries(data.errors).forEach(([field, messages]) => {
                                const msg = Array.isArray(messages) ? messages[0] : messages;

                                if (field === 'general') {
                                    const passField = form.querySelector('.field[data-name="pass-auth"]');
                                    if (passField && !passField.nextElementSibling?.classList.contains('field-error')) {
                                        const errDiv = document.createElement('div');
                                        errDiv.className = 'field-error';
                                        errDiv.textContent = msg;
                                        passField.after(errDiv);
                                    }
                                } else {
                                    const input = form.querySelector(`[name="${field}"]`);
                                    if (input) {
                                        const fieldEl = input.closest('.field') || input.closest('.consent');
                                        if (fieldEl) {
                                            fieldEl.classList.add('err');
                                            if (!fieldEl.nextElementSibling?.classList.contains('field-error')) {
                                                const errDiv = document.createElement('div');
                                                errDiv.className = 'field-error';
                                                errDiv.textContent = msg;
                                                fieldEl.after(errDiv);
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }
                } catch (err) {
                    console.error('Ошибка отправки формы:', err);
                }
            });
        });

        const passwordInput = document.getElementById('reg-password');
        const confirmInput = document.getElementById('reg-confirm');
        if (passwordInput && confirmInput) {
            passwordInput.addEventListener('input', () => {
                if (confirmInput.value) validateField(confirmInput.closest('.field'));
            });
        }
    });
</script>

@endpush