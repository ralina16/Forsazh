<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Админ-панель - Форсаж')</title>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.svg') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">

    @stack('styles')
</head>

<body class="mb-5 admin-body">

    @include('admin.partials.header')

    <main class="container-xxl">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('scripts')
</body>

</html>
