<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="growdevTheme()"
      x-init="init()"
      :data-theme="theme">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full antialiased" style="font-family:var(--font-sans);background:var(--color-base);color:var(--color-text)">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0" style="background-color:var(--color-base);">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20" style="color:var(--color-accent);" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg" style="background-color:var(--color-surface);border:1px solid var(--color-border);">
                {{ $slot }}
            </div>
        </div>
    </body>

    <script>
    function growdevTheme() {
        return {
            theme: 'dark',
            init() {
                const saved = localStorage.getItem('gd-theme');
                if (saved === 'light' || saved === 'dark') {
                    this.theme = saved;
                } else {
                    this.theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (!localStorage.getItem('gd-theme')) {
                        this.theme = e.matches ? 'dark' : 'light';
                    }
                });
            },
            toggleTheme() {
                this.theme = this.theme === 'dark' ? 'light' : 'dark';
                localStorage.setItem('gd-theme', this.theme);
            }
        };
    }
    </script>
</html>
