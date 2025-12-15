<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full font-sans antialiased text-gray-900">
        
        <div class="flex h-screen overflow-hidden bg-gray-100" x-data="{ sidebarOpen: false }">
            
            <!-- Sidebar -->
            @include('components.modern.sidebar')

            <!-- Main Content Wrapper -->
            <div class="flex flex-col flex-1 w-0">
                
                <!-- Topbar -->
                @include('components.modern.topbar')

                <!-- Main Content -->
                <main class="flex-1 relative overflow-y-auto focus:outline-none">
                    <div class="py-6">
                        
                        <!-- Page Header -->
                        @isset($header)
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 mb-6">
                            {{ $header }}
                        </div>
                        @endisset

                        <!-- Alerts -->
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            @if (session('success'))
                                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('success') }}</span>
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                    <span class="block sm:inline">{{ session('error') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                            {{ $slot }}
                        </div>
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
