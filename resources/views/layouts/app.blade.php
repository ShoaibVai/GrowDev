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
            
            /* Smooth scroll behavior */
            html {
                scroll-behavior: smooth;
            }
            
            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            
            ::-webkit-scrollbar-track {
                background: #f1f1f1;
            }
            
            ::-webkit-scrollbar-thumb {
                background: #6366f1;
                border-radius: 4px;
            }
            
            ::-webkit-scrollbar-thumb:hover {
                background: #4f46e5;
            }
            
            /* Page transition */
            .page-content {
                animation: fadeInUp 0.5s ease-out;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            /* Card animations */
            .card {
                transform-origin: center;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Gradient text */
            .gradient-text {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            /* Button hover glow */
            .btn-glow:hover {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.5);
            }
            
            /* Loading animation */
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .animate-spin {
                animation: spin 1s linear infinite;
            }
            
            /* Pulse animation */
            @keyframes pulse {
                0%, 100% {
                    opacity: 1;
                }
                50% {
                    opacity: .5;
                }
            }
            
            .animate-pulse {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }
            
            /* Floating animation */
            @keyframes float {
                0%, 100% {
                    transform: translateY(0);
                }
                50% {
                    transform: translateY(-10px);
                }
            }
            
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            
            /* Form focus glow */
            input:focus, select:focus, textarea:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
                border-color: #6366f1;
                transition: all 0.3s ease;
            }
            
            /* Success/Error states */
            input.success {
                border-color: #10b981 !important;
                box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
            }
            
            input.error {
                border-color: #ef4444 !important;
                box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
            }
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
