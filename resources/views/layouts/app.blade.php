<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if (!empty($robots_noindex ?? false))
        <meta name="robots" content="noindex,nofollow">
    @endif
    <meta name="description" content="{{ $page_description ?? config('puppiary.name') . ' - Puppy toys, teething & starter kit.' }}">
    @if (!empty($page_keywords ?? null))
        <meta name="keywords" content="{{ $page_keywords }}">
    @endif
    <meta name="theme-color" content="{{ config('puppiary.theme_color') }}">
    <title>{{ $page_title ?? config('puppiary.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://js.paystack.co">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon-96x96.png') }}" sizes="96x96">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="canonical" href="{{ config('puppiary.url') }}{{ $page_canonical ?? '/' }}">
    @if (empty($robots_noindex ?? false))
        <link rel="alternate" hreflang="en" href="{{ config('puppiary.url') }}{{ $page_canonical ?? '/' }}">
        <link rel="alternate" hreflang="x-default" href="{{ config('puppiary.url') }}{{ $page_canonical ?? '/' }}">
    @endif

    <meta property="og:site_name" content="{{ config('puppiary.name') }}">
    <meta property="og:locale" content="en_US">
    <meta property="og:title" content="{{ $page_title ?? config('puppiary.name') }}">
    <meta property="og:description" content="{{ $page_description ?? config('puppiary.name') . ' - Puppy toys, teething & starter kit.' }}">
    <meta property="og:type" content="{{ $page_og_type ?? 'website' }}">
    <meta property="og:url" content="{{ config('puppiary.url') }}{{ $page_canonical ?? '/' }}">
    @if (!empty($page_og_image ?? null))
        <meta property="og:image" content="{{ str_starts_with($page_og_image ?? '', 'http') ? $page_og_image : config('puppiary.url') . $page_og_image }}">
    @elseif(config('puppiary.default_og_image'))
        <meta property="og:image" content="{{ config('puppiary.url') }}{{ config('puppiary.default_og_image') }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    @if(config('puppiary.twitter_handle'))
        <meta name="twitter:site" content="{{ config('puppiary.twitter_handle') }}">
    @endif

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
    @stack('head')
</head>
<body class="{{ $body_class ?? '' }} font-sans text-gray-900 bg-white min-h-screen flex flex-col pt-20" x-data="{ drawerOpen: false, cartOpen: false, searchOpen: false, shopOpen: false, supportOpen: false, accountOpen: false }" @puppiary-cart-open.window="cartOpen = true" @if(session('open_cart')) data-open-cart="1" @endif>
    @if(config('puppiary.gtm_id'))
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ config('puppiary.gtm_id') }}" height="0" width="0" class="hidden invisible"></iframe></noscript>
    @endif

    <header class="navbar fixed top-0 left-0 right-0 z-[1000] bg-white/80 backdrop-blur-sm transition-all">
        <div class="max-w-[1200px] mx-auto px-6 lg:px-8 py-3 flex justify-between items-center gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-xl lg:text-2xl font-display font-bold text-primary no-underline shrink-0">
                <img src="{{ asset('logo.webp') }}" alt="" class="w-10 h-10 lg:w-12 lg:h-12 object-contain" width="50" height="50">
                <span class="hidden sm:inline">{{ config('puppiary.name') }}</span>
            </a>

            <nav class="hidden md:flex items-center gap-6 lg:gap-8" aria-label="Primary">
                <div class="relative" @click.away="shopOpen = false">
                    <button @click="shopOpen = !shopOpen" type="button" class="py-2 px-1 -mx-1 rounded-full text-sm font-medium inline-flex items-center gap-1 border-b-2 transition-colors {{ in_array($current_nav ?? '', ['shop']) ? 'border-primary text-primary' : 'border-transparent text-gray-900 hover:text-primary' }}" aria-expanded="false" :aria-expanded="shopOpen" aria-haspopup="true" @if(in_array($current_nav ?? '', ['shop'])) aria-current="page" @endif>
                        Shop
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="shopOpen" x-cloak x-transition class="absolute top-full left-0 mt-1 py-2 w-44 bg-white rounded-lg border border-gray-100">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary" @click="shopOpen = false">All Products</a>
                        <a href="{{ route('products.category', ['category' => 'food']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary" @click="shopOpen = false">Food</a>
                        <a href="{{ route('products.category', ['category' => 'treats']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary" @click="shopOpen = false">Treats</a>
                        <a href="{{ route('products.category', ['category' => 'essentials']) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-primary" @click="shopOpen = false">Essentials</a>
                    </div>
                </div>
                <a href="{{ route('starter-kit') }}" class="py-2 text-sm font-medium border-b-2 transition-colors no-underline {{ ($current_nav ?? '') === 'starter-kit' ? 'border-primary text-primary' : 'border-transparent text-gray-900 hover:text-primary' }}" @if(($current_nav ?? '') === 'starter-kit') aria-current="page" @endif>Starter Kit</a>
                <a href="{{ route('puppy-guide') }}" class="py-2 text-sm font-medium border-b-2 transition-colors no-underline {{ ($current_nav ?? '') === 'puppy-guide' ? 'border-primary text-primary' : 'border-transparent text-gray-900 hover:text-primary' }}" @if(($current_nav ?? '') === 'puppy-guide') aria-current="page" @endif>Puppy Guide</a>
            </nav>

            <div class="flex items-center gap-2">
                <div class="relative hidden sm:block" @click.away="supportOpen = false">
                    <button
                        type="button"
                        @click="supportOpen = !supportOpen"
                        class="px-3 py-2 rounded-full text-sm font-medium text-gray-700 hover:text-primary hover:bg-gray-50"
                        aria-label="Support"
                        title="Support"
                        :aria-expanded="supportOpen"
                        aria-haspopup="true"
                    >
                        Support
                    </button>
                    <div x-show="supportOpen" x-cloak x-transition class="absolute right-0 mt-2 w-72 bg-white border border-gray-200 rounded-2xl py-3 z-50">
                        <a href="{{ auth()->check() ? route('customer.orders') : route('sign-in') }}" class="block px-5 py-3 text-gray-900 hover:bg-gray-50 no-underline" @click="supportOpen = false">Orders Status</a>
                        <a href="{{ route('return-policy') }}" class="block px-5 py-3 text-gray-900 hover:bg-gray-50 no-underline" @click="supportOpen = false">Exchange/Returns</a>
                        <a href="{{ route('faq') }}" class="block px-5 py-3 text-gray-900 hover:bg-gray-50 no-underline" @click="supportOpen = false">FAQ</a>
                        <a href="{{ route('contact') }}" class="block px-5 py-3 text-gray-900 hover:bg-gray-50 no-underline" @click="supportOpen = false">Help</a>
                        <a href="{{ route('contact') }}" class="block px-5 py-3 text-gray-900 hover:bg-gray-50 no-underline" @click="supportOpen = false">Contact Us</a>
                    </div>
                </div>
                <a href="{{ route('products.index') }}" class="p-2 rounded-full text-gray-600 hover:text-primary hover:bg-gray-50" aria-label="Search products" title="Search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </a>
                @auth
                    <a href="{{ route('customer.dashboard') }}" class="p-2 rounded-full text-gray-600 hover:text-primary hover:bg-gray-50" aria-label="My account" title="My account">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @else
                    <a href="{{ route('sign-in') }}" class="p-2 rounded-full text-gray-600 hover:text-primary hover:bg-gray-50" aria-label="Sign in" title="Sign in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth
                <a href="{{ route('cart') }}" class="js-cart-trigger p-2 rounded-full text-gray-600 hover:text-primary hover:bg-gray-50 relative" aria-label="Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="cart-counter absolute -top-0.5 -right-0.5 bg-primary text-white text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">0</span>
                </a>
                <button @click="drawerOpen = true" class="md:hidden p-2 rounded-full" aria-label="Open menu" :aria-expanded="drawerOpen">
                    <svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M3 6h18v2H3V6zm0 5h18v2H3v-2zm0 5h18v2H3v-2z"/></svg>
                </button>
            </div>
        </div>
    </header>

    <div x-show="drawerOpen" x-cloak @click="drawerOpen = false" class="fixed inset-0 bg-black/30 z-[1001] md:hidden" aria-hidden="true"></div>
    <aside x-show="drawerOpen" x-transition class="fixed top-0 right-0 w-72 h-full bg-white border-l border-gray-200 z-[1002] p-6 md:hidden" aria-label="Mobile navigation">
        <button @click="drawerOpen = false" class="absolute top-4 right-4 p-2 rounded-full" aria-label="Close menu">
            <svg viewBox="0 0 24 24" width="24" height="24"><path fill="currentColor" d="M18.3 5.71 12 12l6.3 6.29-1.41 1.41L10.59 13.4 4.29 19.7 2.88 18.3 9.17 12 2.88 5.71 4.29 4.3l6.3 6.3 6.29-6.3z"/></svg>
        </button>
        <nav class="flex flex-col gap-2 mt-12">
            <a href="{{ route('products.index') }}" class="py-3 text-lg font-medium border-b border-gray-100 no-underline {{ in_array($current_nav ?? '', ['shop']) ? 'text-primary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-900' }}" @click="drawerOpen = false">Shop</a>
            <a href="{{ route('starter-kit') }}" class="py-3 text-lg font-medium border-b border-gray-100 no-underline {{ ($current_nav ?? '') === 'starter-kit' ? 'text-primary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-900' }}" @click="drawerOpen = false">Starter Kit</a>
            <a href="{{ route('puppy-guide') }}" class="py-3 text-lg font-medium border-b border-gray-100 no-underline {{ ($current_nav ?? '') === 'puppy-guide' ? 'text-primary underline decoration-primary decoration-2 underline-offset-8' : 'text-gray-900' }}" @click="drawerOpen = false">Puppy Guide</a>
            <a href="{{ route('faq') }}" class="py-3 text-lg font-medium border-b border-gray-100" @click="drawerOpen = false">FAQ</a>
            @auth
                <a href="{{ route('customer.dashboard') }}" class="py-3 text-lg font-medium border-b border-gray-100" @click="drawerOpen = false">My account</a>
                <form method="post" action="{{ route('logout') }}" class="py-3 border-b border-gray-100">
                    @csrf
                    <button type="submit" class="text-lg font-medium text-red-700">Log out</button>
                </form>
            @else
                <a href="{{ route('sign-in') }}" class="py-3 text-lg font-medium border-b border-gray-100" @click="drawerOpen = false">Sign in</a>
            @endauth
            <a href="{{ route('contact') }}" class="py-3 text-lg font-medium" @click="drawerOpen = false">Contact</a>
            <a href="{{ route('cart') }}" class="js-cart-trigger py-3 text-lg font-medium border-t border-gray-200 mt-2 no-underline text-gray-900" @click="drawerOpen = false">Cart</a>
        </nav>
        <a href="{{ route('starter-kit') }}" class="mt-8 block w-full py-3 rounded-full font-semibold bg-primary text-white text-center no-underline" @click="drawerOpen = false">Get Starter Kit</a>
    </aside>

    <div
        x-show="cartOpen"
        x-cloak
        class="fixed inset-0 z-[1100]"
        @keydown.escape.window="cartOpen = false"
        :aria-hidden="!cartOpen"
        role="dialog"
        aria-label="Shopping cart"
    >
        <div
            x-show="cartOpen"
            x-transition.opacity
            class="absolute inset-0 bg-black/40"
            @click="cartOpen = false"
        ></div>
        <aside
            x-show="cartOpen"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-full max-w-md bg-white border-l border-gray-200 flex flex-col"
        >
            <header class="shrink-0 px-5 py-4 border-b border-gray-200 flex items-center justify-between bg-white">
                <h2 class="text-lg font-semibold text-gray-900">Your Cart</h2>
                <button type="button" class="p-2 rounded-full hover:bg-gray-100 text-gray-700" @click="cartOpen = false" aria-label="Close cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </header>
            <div id="cart-drawer-scroll" class="flex-1 min-h-0 overflow-y-auto overscroll-contain p-5">
                <p class="text-sm text-gray-500">Loading cart…</p>
            </div>
            <footer id="cart-drawer-footer" class="hidden shrink-0 border-t border-gray-200 bg-white px-5 pt-4 pb-[max(1rem,env(safe-area-inset-bottom))]">
            </footer>
        </aside>
    </div>

    <main class="flex-1">
        @hasSection('content')
            @yield('content')
        @else
            {{ $slot ?? '' }}
        @endif
    </main>

    @include('partials.site-footer')

    <a href="tel:+2347016426458" class="fixed bottom-6 right-6 w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center z-50" aria-label="Call us">
        <svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
    </a>
    <a href="https://wa.me/2347016426458" target="_blank" rel="noopener" class="fixed bottom-6 right-24 w-14 h-14 rounded-full bg-green-500 text-white flex items-center justify-center z-50" aria-label="WhatsApp">
        <svg viewBox="0 0 24 24" width="28" height="28"><path fill="currentColor" d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    </a>

    <script>
        window.CURRENCY = @json(\App\Helpers\CurrencyHelper::currency());
        window.CURRENCY_SYMBOL = @json(\App\Helpers\CurrencyHelper::symbol());
        window.DELIVERY_FEE = @json(\App\Helpers\CurrencyHelper::deliveryFee());
        window.products = window.products || @json(\App\Data\ProductsData::all());
    </script>
    @stack('scripts')
    @livewireScripts
</body>
</html>
