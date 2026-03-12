<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Dashboard</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://matcha.mambelli.com/alpine.js"></script> {{-- Or use the standard Alpine CDN --}}

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
</head>

<body class="bg-slate-50 antialiased text-slate-900">

    <nav class="bg-white border-b border-slate-200 py-4">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-indigo-200 shadow-lg">
                    <span class="text-white font-black">R</span>
                </div>
                <span class="text-xl font-bold tracking-tight">RestaurantHub</span>
            </div>

            <div class="flex items-center gap-6">
                <span class="text-sm font-medium text-slate-500">{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm font-bold text-red-500 hover:text-red-600 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

</body>

</html>
