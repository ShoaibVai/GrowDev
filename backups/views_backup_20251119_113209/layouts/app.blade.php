<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="GrowDev - Professional CV & Project Management System">

        <title>@yield('title', 'GrowDev - Professional Management System')</title>

        <!-- CSS -->
        <link rel="stylesheet" href="{{ asset('css/base.css') }}">
        @yield('css')
    </head>
    <body>
        <!-- Header (Navigation) -->
        @include('layouts.partials.header')

        <!-- Page Header -->
        @isset($header)
            <header class="page-header">
                <div class="container">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="main-content">
            <div class="container">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        @include('layouts.partials.footer')

        <!-- Scripts -->
        <script src="{{ asset('js/base.js') }}"></script>
        @yield('js')
    </body>
</html>
