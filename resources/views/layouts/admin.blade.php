<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Admin</title>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;900&family=Playfair+Display:wght@700;900&family=Calistoga&family=Montserrat:wght@700&family=Caveat:wght@700&family=Special+Elite&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Your custom styles below */
        [x-cloak] {
            display: none !important;
        }

        .preload * {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -ms-transition: none !important;
            -o-transition: none !important;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-full" x-data="{ mobileMenu: false }">

    <div x-show="mobileMenu" x-cloak class="fixed inset-0 z-50 bg-slate-900/80 lg:hidden" @click="mobileMenu = false">
    </div>

    <div x-cloak :class="mobileMenu ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 z-50 flex w-72 flex-col transition-transform duration-300 lg:translate-x-0">

        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 px-6 pb-4">
            <div class="flex h-16 shrink-0 items-center border-b border-slate-800">
                <span class="text-xl font-black text-white tracking-tighter uppercase">Admin<span
                        class="text-indigo-500">Panel</span></span>
            </div>

            <nav class="flex flex-1 flex-col mt-4">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <ul role="list" class="-mx-2 space-y-1">
                            <li>
                                <a href="{{ route('dashboard') }}"
                                    class="{{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21.75h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21.75h7.5" />
                                    </svg>
                                    Overview
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('website.edit', $restaurant) }}"
                                    class="{{ request()->routeIs('website.edit') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9.53 16.122l9.37-9.37a2.828 2.828 0 114 4l-9.37 9.37a4.5 4.5 0 01-1.897 1.13L6.97 21.82a.75.75 0 01-.948-.948l.558-2.663a4.5 4.5 0 011.13-1.897l9.37-9.37zm0 0L19.44 6.212" />
                                    </svg>
                                    Website Design
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('menu.edit', $restaurant) }}"
                                    class="{{ request()->routeIs('menu.edit') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 transition">
                                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18c-2.305 0-4.408.867-6 2.292m0-14.25V20" />
                                    </svg>
                                    Menu Management
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="mt-auto">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="group -mx-2 flex w-full gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-slate-400 hover:bg-slate-800 hover:text-white">
                                <svg class="h-6 w-6 shrink-0 text-slate-400 group-hover:text-white" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Sign out
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="lg:pl-72">
        <div
            class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="mobileMenu = true">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>

            <div class="h-6 w-px bg-gray-200 lg:hidden"></div>

            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6 justify-end">
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <span class="text-sm font-semibold leading-6 text-gray-900">{{ auth()->user()->name }}</span>
                    <div
                        class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>
            </div>
        </div>

        <main class="py-10">
            <div class="px-4 sm:px-6 lg:px-8">
                <div x-data="{
                    show: {{ session('success') ? 'true' : 'false' }},
                    message: '{{ session('success') }}'
                }" x-init="if (show) { setTimeout(() => show = false, 4000) }" x-show="show"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed top-6 right-6 z-[9999] w-full max-w-sm pointer-events-none" x-cloak>
                    <div
                        class="bg-white border border-slate-200 shadow-2xl rounded-2xl p-4 pointer-events-auto flex items-center gap-4">
                        {{-- Success Icon --}}
                        <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        {{-- Text Content --}}
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">Success</p>
                            <p class="text-xs text-slate-500" x-text="message"></p>
                        </div>

                        {{-- Close Button --}}
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                @yield('content')
            </div>
        </main>
    </div>

</body>

</html>
