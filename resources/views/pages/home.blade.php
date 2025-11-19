<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="GrowDev - Professional CV & Project Management System">
    <title>GrowDev - Professional Management System</title>
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
</head>
<body>
    @include('layouts.partials.header')

    <main class="main-content">
        <div class="container">
            <!-- Hero Section -->
            <section class="hero-section" style="padding: var(--spacing-4xl) var(--spacing-lg);">
                <div style="text-align: center; margin-bottom: var(--spacing-3xl);">
                    <h1 style="font-size: 3.5rem; font-weight: bold; color: var(--color-primary); margin-bottom: var(--spacing-lg);">Welcome to GrowDev</h1>
                    <p style="font-size: 1.125rem; color: var(--color-text-secondary); margin-bottom: var(--spacing-2xl);">Professional Development & Project Management Platform</p>
                    <div style="display: flex; gap: var(--spacing-lg); justify-content: center; flex-wrap: wrap;">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">Go to Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-secondary">Sign Up</a>
                        @endauth
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section style="margin-bottom: var(--spacing-4xl);">
                <h2 style="font-size: 2.25rem; font-weight: bold; text-align: center; margin-bottom: var(--spacing-3xl); color: var(--color-text-primary);">Key Features</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: var(--spacing-2xl);">
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">📋 Document Management</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Create and manage SRS, SDD documents with ease. Collaborate with your team on technical specifications.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">📊 Project Tracking</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Track your projects from start to finish with detailed progress monitoring and milestone tracking.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">👥 Team Collaboration</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Work together seamlessly with real-time updates and collaborative features for your entire team.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">🎓 Professional Development</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Manage certifications, skills, and work experience to build comprehensive professional profiles.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">🔒 Secure & Reliable</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Enterprise-grade security with encrypted data storage and regular backups for peace of mind.</p>
                    </div>
                    <div class="card">
                        <h3 style="font-size: 1.5rem; font-weight: bold; margin-bottom: var(--spacing-md); color: var(--color-primary);">📱 Responsive Design</h3>
                        <p style="color: var(--color-text-secondary); line-height: 1.6;">Access your projects anywhere, anytime with our fully responsive design that works on all devices.</p>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            @guest
            <section style="background-color: var(--color-primary); border-radius: var(--radius-lg); padding: var(--spacing-3xl); text-align: center; color: white; margin-bottom: var(--spacing-4xl);">
                <h2 style="font-size: 2rem; font-weight: bold; margin-bottom: var(--spacing-md);">Ready to Get Started?</h2>
                <p style="font-size: 1.125rem; margin-bottom: var(--spacing-2xl); opacity: 0.9;">Join thousands of professionals using GrowDev to manage their projects and careers.</p>
                <a href="{{ route('register') }}" class="btn btn-white">Create Free Account</a>
            </section>
            @endguest
        </div>
    </main>

    @include('layouts.partials.footer')

    <script src="{{ asset('js/base.js') }}"></script>
</body>
</html>
