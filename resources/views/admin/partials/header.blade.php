<nav class="navbar bg-body-tertiary">
    <div class="container-xxl">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" aria-label="Переключить навигацию">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="admin-header-right">
            <span class="admin-header-name">{{ Auth::user()->name }}</span>

            <a href="{{ route('admin.chat') }}" class="btn-admin-header btn-chat">
                Чат
                @if ((session('unread_messages') ?? 0) > 0)
                    <span class="admin-header-badge">{{ session('unread_messages') }}</span>
                @endif
            </a>

            <form method="POST" action="{{ route('logout') }}" class="m-0 d-inline">
                @csrf
                <button type="submit" class="btn-admin-header">Выйти</button>
            </form>
        </div>

        {{-- Offcanvas меню --}}
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
            aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header" style="align-items: start; padding-top:30px">
                <div class="brand-section">
                    <img src="{{ asset('assets/images/logo/logo-2.svg') }}" alt="Логотип" class="offcanvas-image">
                    <div class="brand-city">Казань</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
            </div>
            <div class="off-line"></div>
            <div class="offcanvas-body d-flex flex-column justify-content-between mt-4">
                <ul class="navbar-nav flex-grow-1 mb-4">
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.index') ? 'active' : '' }}"
                            href="{{ route('admin.index') }}">
                            Главная
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                            href="{{ route('admin.users.index') }}">
                            Пользователи
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.auctions.*') ? 'active' : '' }}"
                            href="{{ route('admin.auctions.index') }}">
                            Аукционы
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.settings') ? 'active' : '' }}"
                            href="{{ route('admin.car-configs.index') }}">
                            Конфигурации
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.requests') ? 'active' : '' }}"
                            href="{{ route('admin.requests.index') }}">
                            Заявки
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.cars.*') ? 'active' : '' }}"
                            href="{{ route('admin.cars.index') }}">
                            Автомобили
                        </a>
                    </li>
                    <li class="nav-item mb-3">
                        <a class="nav-link-custom {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"
                            href="{{ route('admin.reviews.index') }}">
                            Отзывы
                        </a>
                    </li>
                </ul>

                <div class="action-buttons mt-auto">
                    <a href="{{ route('home') }}" class="btn btn-outline-dark w-100 mb-3">
                        Перейти на сайт
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
    .admin-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .admin-header-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        padding-right: 10px;
        margin-right: 2px;
        border-right: 1px solid rgba(0, 0, 0, 0.08);
        white-space: nowrap;
    }

    .btn-admin-header {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 18px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        border: 1.5px solid #4071CB;
        background: transparent;
        color: #4071CB;
        transition: all 0.3s ease;
        cursor: pointer;
        line-height: 1.4;
        white-space: nowrap;
    }

    .btn-chat {
        background-color: #4071CB;
        color: #fff
    }

    .btn-admin-header:hover {
        background: #4071CB;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(64, 113, 203, 0.25);
    }

    .admin-header-badge {
        background: #dc3545;
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        line-height: 1;
        margin-left: 2px;
    }

    .off-line {
        height: 1px;
        width: 100%;
        background: linear-gradient(90deg,
                rgba(64, 113, 203, 0) 0%,
                rgba(64, 113, 203, 0.3) 50%,
                rgba(64, 113, 203, 0) 100%);
        transition: all 0.4s ease;
    }

    .brand-section {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .offcanvas-image {
        height: 40px;
        width: auto;
        object-fit: contain;
    }

    .brand-city {
        font-size: 0.85rem;
        color: rgba(0, 0, 0, 0.5);
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    @media (max-width: 576px) {
        .admin-header-name {
            display: none;
        }

        .btn-admin-header {
            padding: 7px 12px;
            font-size: 0.85rem;
        }
    }
</style>
