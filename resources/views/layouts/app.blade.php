<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') - Laundry App</title>

    <link href="{{ asset('assets/fonts/inter/inter.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/icons/phosphor/styles.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/ltr/all.min.css') }}" rel="stylesheet">
    @stack('head')
</head>
<body>

    {{-- Navbar --}}
    @include('layouts.partials.navbar')

    {{-- Breadcrumb --}}
    @include('layouts.partials.breadcrumb')

    {{-- Page Content --}}
    <div class="page-content pt-5">

        {{-- Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Main Content --}}
        <div class="content-wrapper">
            <div class="content">
                @yield('content')
            </div>
        </div>

    </div>

    @include('layouts.partials.footer')

    {{-- Notifications Offcanvas --}}
    @include('layouts.partials.notifications')

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>