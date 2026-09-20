<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'App') - Laundry App</title>

    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>

    @include('layouts.partials.auth-navbar')

    <div class="page-content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    @include('layouts.partials.footer')

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>