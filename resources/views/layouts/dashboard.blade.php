<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ config('puppiary.theme_color') }}">
    <title>{{ $dashboardPageTitle ?? 'My account' }} - {{ config('puppiary.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @livewireStyles
</head>
<body class="font-sans text-gray-900 bg-gray-50 min-h-screen" x-data="{ mobileNav: false }">
    <div class="flex min-h-screen">
        {{-- Sidebar desktop --}}
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-white border-r border-gray-200 sticky top-0 h-screen">
            <div class="p-6 border-b border-gray-100">
                <a href="{{ route('home') }}" class="flex items-center gap-2 font-display font-bold text-primary text-lg no-underline">
                    <img src="{{ asset('logo.webp') }}" alt="" class="w-9 h-9 object-contain" width="36" height="36">
                    {{ config('puppiary.name') }}
                </a>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto" aria-label="Account">
                <x-dashboard.nav-link href="{{ route('customer.dashboard') }}" :active="request()->routeIs('customer.dashboard')">Overview</x-dashboard.nav-link>
                <x-dashboard.nav-link href="{{ route('customer.account') }}" :active="request()->routeIs('customer.account')">Profile & settings</x-dashboard.nav-link>
                <x-dashboard.nav-link href="{{ route('customer.orders') }}" :active="request()->routeIs('customer.orders*')">My orders</x-dashboard.nav-link>
                <x-dashboard.nav-link href="{{ route('customer.puppies') }}" :active="request()->routeIs('customer.puppies')">My puppies</x-dashboard.nav-link>
                <x-dashboard.nav-link href="{{ route('customer.reviews') }}" :active="request()->routeIs('customer.reviews')">My reviews</x-dashboard.nav-link>
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Learning hub</p>
                    <a href="{{ route('puppy-guide') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">First-time puppy guide</a>
                    <a href="{{ route('guide.show', ['slug' => 'understanding-puppy-nutrition-a-complete-guide']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Feeding guide</a>
                    <a href="{{ route('guide.show', ['slug' => 'crate-training-made-easy']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Training tips</a>
                    <a href="{{ route('guide.show', ['slug' => 'puppy-potty-training-101']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Potty training 101</a>
                    <a href="{{ route('puppy-guide') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">All articles</a>
                </div>
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">Support</p>
                    <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">Contact us</a>
                    <a href="{{ route('faq') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700 hover:bg-gray-50 hover:text-primary">FAQ</a>
                </div>
            </nav>
            <div class="p-4 border-t border-gray-200 mt-auto">
                <form method="post" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2.5 rounded-full text-sm font-semibold text-red-700 bg-red-50 hover:bg-red-100 transition">
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile top bar --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="lg:hidden sticky top-0 z-40 flex items-center justify-between gap-3 px-4 py-3 bg-white border-b border-gray-200">
                <button type="button" @click="mobileNav = true" class="p-2 rounded-full text-gray-700 hover:bg-gray-100" aria-label="Open menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <span class="font-display font-semibold text-primary truncate">{{ $dashboardPageTitle ?? 'Account' }}</span>
                <a href="{{ route('home') }}" class="text-sm text-primary font-medium">Shop</a>
            </header>

            <div x-show="mobileNav" x-cloak class="lg:hidden fixed inset-0 z-50" x-transition.opacity>
                <div class="absolute inset-0 bg-black/40" @click="mobileNav = false"></div>
                <aside class="absolute left-0 top-0 bottom-0 w-72 max-w-[85vw] bg-white border-r border-gray-200 flex flex-col" @click.stop>
                    <div class="p-4 flex justify-between items-center border-b">
                        <span class="font-display font-bold text-primary">Menu</span>
                        <button type="button" @click="mobileNav = false" class="p-2 rounded-full hover:bg-gray-100 text-gray-700" aria-label="Close">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <nav class="flex-1 overflow-y-auto p-4 space-y-1">
                        <a href="{{ route('customer.dashboard') }}" class="block px-3 py-2 rounded-full text-sm {{ request()->routeIs('customer.dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' }}" @click="mobileNav = false">Overview</a>
                        <a href="{{ route('customer.account') }}" class="block px-3 py-2 rounded-full text-sm {{ request()->routeIs('customer.account') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' }}" @click="mobileNav = false">Profile & settings</a>
                        <a href="{{ route('customer.orders') }}" class="block px-3 py-2 rounded-full text-sm {{ request()->routeIs('customer.orders*') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' }}" @click="mobileNav = false">My orders</a>
                        <a href="{{ route('customer.puppies') }}" class="block px-3 py-2 rounded-full text-sm {{ request()->routeIs('customer.puppies') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' }}" @click="mobileNav = false">My puppies</a>
                        <a href="{{ route('customer.reviews') }}" class="block px-3 py-2 rounded-full text-sm {{ request()->routeIs('customer.reviews') ? 'bg-primary/10 text-primary font-semibold' : 'text-gray-700' }}" @click="mobileNav = false">My reviews</a>
                        <a href="{{ route('puppy-guide') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">First-time guide</a>
                        <a href="{{ route('guide.show', ['slug' => 'understanding-puppy-nutrition-a-complete-guide']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">Feeding guide</a>
                        <a href="{{ route('guide.show', ['slug' => 'crate-training-made-easy']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">Training tips</a>
                        <a href="{{ route('guide.show', ['slug' => 'puppy-potty-training-101']) }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">Potty training 101</a>
                        <a href="{{ route('puppy-guide') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">All articles</a>
                        <a href="{{ route('contact') }}" class="block px-3 py-2 rounded-full text-sm text-gray-700" @click="mobileNav = false">Contact</a>
                    </nav>
                    <form method="post" action="{{ route('logout') }}" class="p-4 border-t">
                        @csrf
                        <button type="submit" class="w-full py-3 rounded-full text-sm font-semibold text-red-700 bg-red-50">Log out</button>
                    </form>
                </aside>
            </div>

            <main class="flex-1 p-4 sm:p-6 lg:p-10 max-w-4xl w-full mx-auto">
                {{ $slot }}
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
