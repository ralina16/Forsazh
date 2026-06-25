<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ФОРСАЖ - Автосалон')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="{{ asset('assets/js/mask.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.4/dist/confetti.browser.min.js"></script>

    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <meta name="user-id" content="{{ Auth::id() ?? 'guest' }}">



    @stack('styles')
</head>

<body class="@yield('body-class', '')" id="@yield('body-id', 'main')">

    @if (request()->routeIs('home'))
        @include('partials.header-dark')
    @else
        @include('partials.header-light')
    @endif

    <main>
        @yield('content')
    </main>


    @include('partials.modal-login')
    @include('partials.modal-claim')
    @if (isset($reviews) || request()->routeIs('home') || request()->routeIs('catalog.*'))
        @include('partials.modal-review', ['reviews' => $reviews ?? collect()])
    @endif



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 1000,
                easing: 'ease-out-cubic',
                once: true,
                offset: 120
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
