<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <meta name="theme-color" content="{{ config('puppiary.theme_color') }}">
    <title>{{ ($title ?? 'Something went wrong') . ' | ' . config('puppiary.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 font-sans text-gray-900 min-h-screen flex flex-col">
    <header class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-primary no-underline">{{ config('puppiary.name') }}</a>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-primary transition">Shop</a>
                    <a href="{{ route('starter-kit') }}" class="text-gray-700 hover:text-primary transition">Starter Kit</a>
                    <a href="{{ route('puppy-guide') }}" class="text-gray-700 hover:text-primary transition">Puppy Guide</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('sign-in') }}" class="text-gray-700 hover:text-primary transition">Sign in</a>
                    <a href="{{ route('cart') }}" class="relative text-gray-700 hover:text-primary transition">Cart</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="text-center max-w-2xl">
            <div class="mb-8">
                <div class="text-8xl md:text-9xl font-bold text-primary">{{ $code ?? 'Error' }}</div>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">{{ $title ?? 'Something went wrong' }}</h1>
            <p class="text-lg text-gray-600 mb-8 max-w-md mx-auto">
                {{ $message ?? 'Sorry, we could not load this page right now. Please try again or use one of the links below.' }}
            </p>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 max-w-2xl mx-auto">
                <a href="{{ route('home') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-200 transition text-sm font-medium no-underline">
                    Home
                </a>
                <a href="{{ route('starter-kit') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-200 transition text-sm font-medium no-underline">
                    Starter Kit
                </a>
                <a href="{{ route('products.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-200 transition text-sm font-medium no-underline">
                    Shop
                </a>
                <a href="{{ route('puppy-guide') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full hover:bg-gray-200 transition text-sm font-medium no-underline">
                    Puppy Guide
                </a>
            </div>
        </div>
    </main>

    @include('partials.site-footer')
</body>
</html>
