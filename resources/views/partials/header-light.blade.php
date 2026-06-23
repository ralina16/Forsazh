
@include('partials.modal-claim')

<style>
  .btn-profile-light {
    background: transparent;
    border: 1.6px solid #4071CB;
    color: #4071CB;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  form{
    padding-top: 0;
  }
  .btn-profile-light:hover {
    background: #4071CB;
    color: white;
  }
  .field-error {
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
    display: block;
  }
  .text-success { color: #28a745; }
</style>

<!-- HEADER -->
<div class="site-header-light">
  <div class="topbar-light container-xxl">
    <div>г. Казань, ул. Чистопольская, д. 9а</div>
    <div class="phone-light">Круглосуточная линия:
      <a href="tel:89874161010" class="phone-span-light">8 (987) 416-10-10</a>
    </div>
  </div>
  <div class="line-light"></div>

  <div class="main-nav-light container-xxl d-flex align-items-center">
    <div class="d-flex align-items-center gap-3">
      <button class="burger-btn-light d-md-none" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasMenu">
        <img src="{{ asset('assets/images/header/burger-menu-2.svg') }}" alt="">
      </button>

      <div class="logo d-none d-md-block">
        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo/logo-2.svg') }}" alt=""></a>
      </div>

      <nav class="header-nav-light d-none d-md-flex">
        <a class="nav-link-light" href="{{ route('home') }}">ГЛАВНАЯ</a>
        <a class="nav-link-light" href="{{ route('catalog.index') }}">КАТАЛОГ</a>
        <a class="nav-link-light" href="{{ route('about') }}">О НАС</a>
        <a class="nav-link-light" href="{{ route('auction.index') }}">АУКЦИОНЫ</a>   
      </nav>
    </div>

    <div class="btns-header-light">
      @auth
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.index') }}" class="btn-cta-desktop-light">АДМИН-ПАНЕЛЬ</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn-cta-desktop-light">ВЫЙТИ</button>
          </form>
        @else
          <a href="{{ route('account') }}" class="btn-cta-desktop-light">ЛИЧНЫЙ КАБИНЕТ</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn-cta-desktop-light">ВЫЙТИ</button>
          </form>
        @endif
      @else
        <button class="btn-cta-desktop-light" data-bs-toggle="modal" data-bs-target="#requestModal">
          ОСТАВИТЬ ЗАЯВКУ
        </button>
      @endauth

      <a href="https://t.me/wicsay" class="btn-link-desktop-light" target="_blank">
        <img src="{{ asset('assets/images/header/telegram-2.svg') }}" alt="Telegram">
      </a>
      <a href="https://wa.me/79600311715" class="btn-link-desktop-light" target="_blank">
        <img src="{{ asset('assets/images/header/whatsapp-2.svg') }}" alt="WhatsApp">
      </a>
    </div>
  </div>
  <div class="line-light"></div>
</div>

<div class="offcanvas offcanvas-start offcanvas-light" tabindex="-1" id="offcanvasMenu"
  aria-labelledby="offcanvasMenuLabel">
  <div class="offcanvas-header off-line d-flex align-items-center justify-content-between">
    <div style="display:flex;gap:12px;align-items:center">
      <div>
        <img src="{{ asset('assets/images/logo/logo-2.svg') }}" alt="" class="offcanvas-image">
        <div style="font-size:12px;color:rgba(0,0,0,0.6)">Казань</div>
      </div>
    </div>
    <button type="button" class="btn-close text-reset me-2" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column">
    <nav class="menu-list-light">
      <a href="{{ route('home') }}">— Главная</a>
      <a href="{{ route('catalog.index') }}">— Каталог</a>
      <a href="{{ route('about') }}">— О нас</a>
      <a href="{{ route('auction.index') }}">— Аукционы</a>  
      <a href="#" id="toggle-contacts">— Контакты</a>

      @auth
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.index') }}" class="w-100 mb-2 mt-2">— Админ-панель</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <a href="#" onclick="this.closest('form').submit(); return false;" style="color: #dc3545;">— Выйти</a>
          </form>
        @else
          <a href="{{ route('account') }}">— Личный кабинет</a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <a href="#" onclick="this.closest('form').submit(); return false;" style="color: #dc3545;">— Выйти</a>
          </form>
        @endif
      @endauth
    </nav>

    <div class="contact-card-light d-none" id="contacts-section">
      <p class="d-flex flex-column gap-2">— Адрес: <br> <span class="header-info-light">г. Казань, ул. Чистопольская,
          д. 9а</span></p>
      <p class="mb-0 d-flex flex-column gap-2">— Круглосуточная линия: <br><a class="header-info-light">8 (987)
          416-10-10</a></p>
      <div class="socials" aria-hidden="true"></div>
    </div>

    <div class="mt-auto offcanvas-cta-light">
      @auth
        @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.index') }}" class="btn-profile-light w-100 mb-2 text-center d-block">АДМИН-ПАНЕЛЬ</a>
        @else
          <a href="{{ route('account') }}" class="btn-profile-light w-100 mb-2 text-center d-block">ЛИЧНЫЙ КАБИНЕТ</a>
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
      contactsSection.classList.add('d-none');
      toggleContacts.addEventListener('click', function(e) {
        e.preventDefault();
        const isHidden = contactsSection.classList.contains('d-none');
        if (isHidden) {
          contactsSection.classList.remove('d-none');
          toggleContacts.textContent = '— Скрыть контакты';
          setTimeout(() => {
            contactsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
          }, 100);
        } else {
          contactsSection.classList.add('d-none');
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
          if (targetTab) targetTab.style.display = 'block';
        });
      });
    }
  });
</script>