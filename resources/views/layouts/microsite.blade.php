<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $restaurant->meta_title ?: $restaurant->name . ' | Official Site' }}</title>
    <meta name="description" content="{{ $restaurant->meta_description ?: Str::limit($restaurant->description, 160) }}">

    {{-- OG Tags --}}
    <meta property="og:title" content="{{ $restaurant->name }}">
    <meta property="og:description" content="{{ Str::limit($restaurant->description, 160) }}">
    <meta property="og:image" content="{{ $restaurant->getHeroUrl() }}">

    {{-- Hreflang Tags --}}
    @foreach (['it', 'en', 'fr', 'de'] as $lang)
        <link rel="alternate" hreflang="{{ $lang }}"
            href="{{ route(Route::currentRouteName(), ['lang' => $lang, 'restaurant' => $restaurant->slug]) }}" />
    @endforeach

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;900&family=Playfair+Display:wght@700;900&family=Calistoga&family=Montserrat:wght@700&family=Caveat:wght@700&family=Special+Elite&display=swap"
        rel="stylesheet">

    {{-- Tailwind & Alpine --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary:
                {{ $restaurant->primary_color ?? '#4f46e5' }}
            ;
            --font-body: 'Inter', sans-serif;

            /* Map the saved key to the actual CSS font stack */
            --font-heading:
            {!! match ($restaurant->font_family) {
    'serif' => "'Playfair Display', serif",
    'display' => "'Calistoga', serif",
    'monsterrat' => "'Montserrat', sans-serif",
    'handwriting' => "'Caveat', cursive",
    'rust' => "'Special Elite', cursive",
    default => "'Inter', sans-serif",
} !!}
            ;
        }

        h1,
        h2,
        h3,
        .font-heading,
        .restaurant-name {
            font-family: var(--font-heading) !important;
        }

        body {
            font-family: var(--font-body);
        }

        .bg-brand {
            background-color: var(--primary);
        }

        .text-brand {
            color: var(--primary);
        }

        .border-brand {
            border-color: var(--primary);
        }

        /* Smooth fade for the overlay */
        .hero-gradient {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.7) 100%);
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased" x-data="{ sideMenu: false, langOpen: false }"> {{-- <--- ADDED
        x-data HERE --}} {{-- NAVBAR --}} <nav
        class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
            <!-- Restaurant Name -->
            <div class="flex-shrink-0 hidden md:flex">
                <span class="text-xl font-black uppercase tracking-tighter text-brand restaurant-name">{{ $restaurant->name }}</span>
            </div>

            <div class="flex row-gap-4 items-center md:hidden gap-6">
                {{-- 2. THE TRIGGER (Hamburger button for mobile) --}}
                @if ($restaurant->subscription_level > 0)
                    <button @click="sideMenu = true"
                        class="md:hidden bg-white/90 backdrop-blur-md p-3 rounded-full shadow-lg border border-slate-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7" />
                        </svg>
                    </button>
                @endif
                <!-- Restaurant Name -->
                <div class="flex-shrink-0">
                    <span class="text-xl font-black uppercase tracking-tighter text-brand restaurant-name">{{ $restaurant->name }}</span>
                </div>
            </div>


            <!-- Navigation Links (Desktop) -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" class="text-slate-700 hover:text-brand font-medium transition-colors">Home</a>
                @if ($restaurant->menus()->where('is_active', true)->exists())
                    <a href="#menu" class="text-slate-700 hover:text-brand font-medium transition-colors">Menu</a>
                @endif
                <a href="#gallery" class="text-slate-700 hover:text-brand font-medium transition-colors">Gallery</a>
                <a href="#contact" class="text-slate-700 hover:text-brand font-medium transition-colors">Contact Us</a>
            </div>

            <!-- Language Selector -->
            <div class="relative">
                <button @click="langOpen = !langOpen"
                    class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors">
                    @php $currentLang = app()->getLocale(); @endphp
                    <img src="{{ asset("images/flags/{$currentLang}.svg") }}" alt="{{ $currentLang }}"
                        class="w-5 h-5 rounded-sm">
                    <span class="text-sm font-semibold uppercase hidden sm:inline">{{ $currentLang }}</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </button>

                <!-- Language Dropdown -->
                <div x-show="langOpen" @click.away="langOpen = false" x-transition
                    class="absolute right-0 mt-2 w-40 bg-white rounded-lg shadow-xl border border-slate-200 py-2 z-10">
                    @foreach (['it', 'en', 'fr', 'de'] as $lang)
                        <a href="{{ route(Route::currentRouteName(), ['lang' => $lang, 'restaurant' => $restaurant->slug]) }}"
                            class="flex items-center space-x-3 px-4 py-2 hover:bg-slate-100 transition-colors {{ $lang === $currentLang ? 'bg-slate-50' : '' }}"
                            @click="langOpen = false">
                            <img src="{{ asset("images/flags/{$lang}.svg") }}" alt="{{ $lang }}" class="w-5 h-5 rounded-sm">
                            <span class="text-sm font-medium uppercase">{{ $lang }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        </nav>

        {{-- 1. THE SIDE NAV (Must be inside body to access sideMenu state) --}}
        @if ($restaurant->subscription_level > 0)
            <div x-show="sideMenu" x-cloak class="fixed inset-0 z-[200]">
                {{-- Backdrop --}}
                <div x-show="sideMenu" x-transition:opacity @click="sideMenu = false"
                    class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>

                {{-- Menu Panel --}}
                <nav x-show="sideMenu" x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full" @click.stop
                    class="relative w-80 bg-white h-full p-8 shadow-2xl flex flex-col justify-between">

                    <div>
                        <div class="mb-12 flex justify-between items-center">
                            <span class="text-2xl font-black uppercase tracking-tighter">{{ $restaurant->name }}</span>
                            <button @click="sideMenu = false" class="text-slate-400">&times;</button>
                        </div>
                        <ul class="space-y-6 text-xl font-bold">
                            <li><a href="#home" @click="sideMenu = false" class="hover:text-brand">Home</a></li>
                            @if ($restaurant->menus()->where('is_active', true)->exists())
                                <li><a href="#menu" @click="sideMenu = false" class="hover:text-brand">Menu</a></li>
                            @endif
                            <li><a href="#contact" @click="sideMenu = false" class="hover:text-brand">Contact Us</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Language Selector in Mobile Menu -->
                    <div class="pt-8 border-t border-slate-200">
                        <p class="text-sm font-semibold text-slate-700 mb-4 uppercase">Language</p>
                        <div class="space-y-2">
                            @php $currentLang = app()->getLocale(); @endphp
                            @foreach (['it', 'en', 'fr', 'de'] as $lang)
                                <a href="{{ route(Route::currentRouteName(), ['lang' => $lang, 'restaurant' => $restaurant->slug]) }}"
                                    class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors {{ $lang === $currentLang ? 'bg-slate-50 border-l-2 border-brand pl-2' : '' }}"
                                    @click="sideMenu = false">
                                    <img src="{{ asset("images/flags/{$lang}.svg") }}" alt="{{ $lang }}"
                                        class="w-5 h-5 rounded-sm">
                                    <span class="text-sm font-medium uppercase">{{ $lang }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </nav>
            </div>
        @endif

        <main>
            {{-- Hero Section --}}
            <header
                class="relative h-[60vh] md:h-[80vh] w-full flex items-center justify-center overflow-hidden bg-slate-900 pt-16 md:pt-20">
                {{-- Hero Image --}}
                <img src="{{ $restaurant->getHeroUrl() }}" alt="{{ $restaurant->name }}"
                    class="absolute inset-0 w-full h-full object-cover">

                {{-- Dark Overlay --}}
                <div class="absolute inset-0 hero-gradient"></div>

                {{-- Hero Content --}}
                <div class="relative z-10 text-center px-6 max-w-4xl">
                    <h1 class="text-4xl md:text-7xl font-black text-white mb-4 drop-shadow-lg">
                        {{ $restaurant->name }}
                    </h1>
                    @if ($restaurant->description)
                        <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto leading-relaxed drop-shadow-md">
                            {{ Str::limit($restaurant->description, 120) }}
                        </p>
                    @endif

                    {{-- CTA Button (Optional) --}}
                    <div class="mt-8">
                        <a href="#menu"
                            class="inline-block bg-brand text-white px-8 py-4 rounded-full font-bold text-lg shadow-xl hover:brightness-110 transition-all transform hover:-translate-y-1">
                            {{ __('View Menu') }}
                        </a>
                    </div>
                </div>
            </header>

            {{-- Content Area --}}
            <section id="menu" class="relative -mt-10 md:-mt-20 z-20 pb-20">
                <div class="max-w-5xl mx-auto px-4 md:px-6">
                    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
                        @yield('content')
                    </div>
                </div>
            </section>
        </main>

        {{-- Simple Footer --}}
        <footer class="py-12 text-center text-slate-400 text-sm">
            <p>&copy; {{ date('Y') }} {{ $restaurant->name }}. Powered by YourPlatform.</p>
        </footer>

</body>

</html>