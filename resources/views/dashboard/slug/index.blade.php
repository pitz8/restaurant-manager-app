@extends('layouts.admin') {{-- Use your existing admin layout --}}
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'identity', sideMenu: false }">

        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-900">{{ $restaurant->name }}</h1>
                <p class="text-gray-500 italic">{{ __('dashboard.overview.description') }}</p>
            </div>
            <a href="{{ url((App::getLocale() ?? 'it') . '/r/' . $restaurant->slug) }}" target="_blank"
                class="inline-flex items-center text-indigo-600 font-semibold hover:underline">
                {{ __('dashboard.overview.view_public_page') }}
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>
        </div>

        <div class="space-y-6">

            <div
                class="rounded-xl border-l-8 {{ $stats['is_active'] ? 'bg-white border-green-500 shadow-sm' : 'bg-red-50 border-red-500' }} p-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ __('dashboard.overview.service_status') }}:
                            {{ $stats['is_active'] ? __('dashboard.overview.active') : __('dashboard.overview.upgrade_required') }}
                        </h3>
                        <p class="text-sm text-gray-600">
                            {{ $stats['is_active'] ? __('dashboard.overview.service_status_desc') : __('dashboard.overview.menu_disabled_desc') }}
                        </p>
                    </div>
                    <div>
                        @if ($stats['is_active'])
                            {{-- Active: Link to the Menu Editor --}}
                            <a href="{{ route('menu.edit', $restaurant) }}"
                                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-sm transition shadow-lg text-center">
                                {{ __('dashboard.overview.edit_menu') }}
                            </a>
                        @else
                            {{-- Locked: Link to a Checkout/Pricing page (Placeholder for now) --}}
                            <a href="#" onclick="alert('Redirecting to Stripe Checkout...')"
                                class="inline-block bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-bold text-sm transition shadow-lg animate-pulse text-center">
                                💳 {{ __('dashboard.overview.upgrade_required') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div x-data="{
                selected: '{{ $restaurant->theme }}',
                preview: '{{ $restaurant->theme }}'
            }" class="mt-10 grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">

                <div class="space-y-4">
                    <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
                            {{ __('dashboard.overview.visual_identity') }}</h3>

                        <form action="{{ route('restaurant.update-theme', $restaurant) }}" method="POST"
                            class="grid grid-cols-2 lg:grid-cols-2 gap-3">
                            @csrf
                            @php
                                $allThemes = [
                                    'modern' => ['color' => '#1e293b', 'tag' => 'Minimal'],
                                    'elegant' => ['color' => '#fde68a', 'tag' => 'Luxury'],
                                    'retro' => ['color' => '#fb923c', 'tag' => 'Diner'],
                                    'paper' => ['color' => '#78716c', 'tag' => 'Classic'],
                                    'midnight' => ['color' => '#0f172a', 'tag' => 'Sleek'],
                                    'botanical' => ['color' => '#166534', 'tag' => 'Organic'],
                                    'neon' => ['color' => '#d946ef', 'tag' => 'Cyber'],
                                    'bistro' => ['color' => '#b91c1c', 'tag' => 'French'],
                                ];
                            @endphp

                            @foreach ($allThemes as $style => $info)
                                <button type="submit" name="theme" value="{{ $style }}"
                                    class="relative flex flex-col p-4 rounded-xl border-2 transition-all text-left group
                                {{ $restaurant->theme == $style ? 'border-indigo-600 bg-indigo-50' : 'border-gray-100 hover:border-indigo-200' }}">

                                    <div class="flex justify-between w-full mb-2">
                                        <div class="w-3 h-3 rounded-full shadow-inner"
                                            style="background-color: {{ $info['color'] }}"></div>
                                        @if ($restaurant->theme == $style)
                                            <div class="w-2 h-2 rounded-full bg-indigo-600"></div>
                                        @endif
                                    </div>

                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-gray-700">{{ $style }}</span>
                                    <span
                                        class="text-[8px] text-gray-400 font-bold uppercase tracking-tighter">{{ $info['tag'] }}</span>
                                </button>
                            @endforeach
                        </form>
                    </div>

                    <div class="p-4 bg-indigo-900 rounded-xl text-white shadow-lg">
                        <p class="text-xs font-bold opacity-80 uppercase tracking-tighter">
                            {{ __('dashboard.overview.live_preview') }}</p>
                    </div>
                </div>

                <div class="sticky top-8">
                    <div
                        class="relative mx-auto border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[600px] w-[300px] shadow-2xl overflow-hidden ring-4 ring-gray-700">
                        <div class="absolute top-0 inset-x-0 h-6 bg-gray-800 rounded-b-3xl z-20 w-40 mx-auto"></div>

                        <div class="h-full w-full bg-white overflow-hidden">
                            <iframe
                                :src="'{{ url((App::getLocale() ?? 'it') . '/r/' . $restaurant->slug . '/menu') }}?preview_theme=' +
                                preview"
                                class="w-full h-full border-0 transition-opacity duration-300" title="Live Menu Preview">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                /* Hide scrollbars inside the iframe for a cleaner look */
                iframe::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">{{ __('dashboard.overview.items_on_menu') }}</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['items_count'] }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase">{{ __('dashboard.overview.categories') }}</p>
                    <p class="text-4xl font-black text-gray-900">{{ $stats['categories_count'] }}</p>
                </div>

                <div class="bg-indigo-900 p-4 rounded-xl shadow-md flex items-center justify-between text-white">
                    <div>
                        <p class="text-xs font-bold opacity-70 uppercase tracking-tighter">
                            {{ __('dashboard.overview.scan_to_view') }}</p>
                        <p class="text-lg font-bold">{{ __('dashboard.overview.qr_code') }}</p>
                        <button
                            class="text-[10px] underline opacity-50 hover:opacity-100 mt-1">{{ __('dashboard.overview.download_png') }}</button>
                    </div>
                    <div class="bg-white p-2 rounded-lg shadow-inner">
                        {!! QrCode::size(60)->generate(url('/r/' . $restaurant->slug . '/menu')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
