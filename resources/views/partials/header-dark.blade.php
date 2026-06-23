@include('partials.modal-claim')

<style>
    .btn-profile-light {
        background: transparent;
        border: 1.6px solid #4071CB;
        color: #4071CB;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    form {
        padding-top: 0;
    }

    .btn-profile-light:hover {
        background: #4071CB;
        color: white;
    }
</style>
<!-- HEADER -->
<div class="site-header">
    <div class="topbar container-xxl">
        <div>г. Казань, ул. Чистопольская, д. 9а</div>
        <div class="phone">Круглосуточная линия:
            <a href="tel:89874161010" class="phone-span">8 (987) 416-10-10</a>
        </div>
    </div>
    <div class="line"></div>

    <div class="main-nav container-xxl d-flex align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button class="burger-btn d-md-none" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasMenu">
                <img src="{{ asset('assets/images/header/burger-menu.svg') }}" alt="">
            </button>

            <div class="logo d-none d-md-block">
                <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo/logo.svg') }}" alt=""></a>
            </div>

            <nav class="header-nav-light d-none d-md-flex">
                <a class="nav-link" href="{{ route('home') }}">ГЛАВНАЯ</a>
                <a class="nav-link" href="{{ route('catalog.index') }}">КАТАЛОГ</a>
                <a class="nav-link" href="{{ route('about') }}">О НАС</a>
                <a class="nav-link" href="{{ route('auction.index') }}">АУКЦИОНЫ</a>
            </nav>
        </div>

        <div class="btns-header">
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="btn-cta-desktop">АДМИН-ПАНЕЛЬ</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-cta-desktop">ВЫЙТИ</button>
                    </form>
                @else
                    <a href="{{ route('account') }}" class="btn-cta-desktop">ЛИЧНЫЙ КАБИНЕТ</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn-cta-desktop">ВЫЙТИ</button>
                    </form>
                @endif
            @else
                <button class="btn-cta-desktop" data-bs-toggle="modal" data-bs-target="#authModal">ВОЙТИ</button>
                <button class="btn-cta-desktop" data-bs-toggle="modal" data-bs-target="#requestModal">ОСТАВИТЬ
                    ЗАЯВКУ</button>
            @endauth

            <a href="https://t.me/wicsay" class="btn-link-desktop" target="_blank">
                <img src="{{ asset('assets/images/header/telegram.svg') }}" alt="Telegram">
            </a>
            <a href="https://wa.me/79600311715" class="btn-link-desktop" target="_blank">
                <img src="{{ asset('assets/images/header/whatsapp.svg') }}" alt="WhatsApp">
            </a>
        </div>
    </div>
</div>
<div class="line"></div>

{{-- Offcanvas меню --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header d-flex align-items-center justify-content-between">
        <div style="display:flex;gap:12px;align-items:center">
            <div>
                <img src="{{ asset('assets/images/logo/logo.svg') }}" alt="" class="offcanvas-image">
                <div style="font-size:12px;color:rgba(255,255,255,0.6)">Казань</div>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white text-reset me-2" data-bs-dismiss="offcanvas"
            aria-label="Закрыть"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">
        <nav class="menu-list">
            <a href="{{ route('home') }}">— Главная</a>
            <a href="{{ route('catalog.index') }}">— Каталог</a>
            <a href="{{ route('about') }}">— О нас</a>
            <a href="{{ route('auction.index') }}">— Аукционы</a>
            <a href="#" id="toggle-contacts">— Контакты</a>

            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="btn-cta-desktop">АДМИН-ПАНЕЛЬ</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" style="color: #dc3545; background-color:transparent; border:none">—
                            Выйти</button>
                    </form>
                @else
                    <a href="{{ route('account') }}">— Личный кабинет</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" style="color: #dc3545; background-color:transparent; border:none">—
                            Выйти</button>
                    </form>
                @endif
            @endauth
        </nav>

        <div class="contact-card d-none" id="contacts-section">
            <p class="d-flex flex-column gap-2">— Адрес: <br>
                <span class="header-info">г. Казань, ул. Чистопольская, д. 9а</span>
            </p>
            <p class="mb-0 d-flex flex-column gap-2">— Круглосуточная линия: <br>
                <a class="header-info" href="tel:89874161010">8 (987) 416-10-10</a>
            </p>
        </div>

        <div class="mt-auto offcanvas-cta w-100">
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.index') }}" class="btn-cta w-100 mb-2">АДМИН-ПАНЕЛЬ</a>
                @else
                    <div class="w-100">
                        <a href="{{ route('account') }}" class="btn-profile-light w-100 mb-2 text-center d-block">
                            ЛИЧНЫЙ КАБИНЕТ
                        </a>
                    </div>
                @endif
            @else
                <button class="btn-cta-light w-100 mb-0" data-bs-toggle="modal" data-bs-target="#requestModal">ОСТАВИТЬ
                    ЗАЯВКУ</button>
            @endauth
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleContacts = document.getElementById('toggle-contacts');
        const contactsSection = document.getElementById('contacts-section');

        if (toggleContacts && contactsSection) {
            toggleContacts.addEventListener('click', function(e) {
                e.preventDefault();

                const isHidden = contactsSection.classList.contains('d-none');
                contactsSection.classList.toggle('d-none');

                if (isHidden) {
                    toggleContacts.textContent = '— Скрыть контакты';
                    setTimeout(() => {
                        contactsSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);
                } else {
                    toggleContacts.textContent = '— Контакты';
                }
            });
        }

        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        if (tabs.length > 0 && contents.length > 0) {
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
        }
    });
</script>
