@extends('layouts.dashboard')

@section('content')
    <div class="min-h-screen bg-slate-50 py-12 px-6">
        <div class="max-w-6xl mx-auto">

            <div class="mb-10 text-center">
                <h1 class="text-4xl font-black text-slate-900 tracking-tight">My Restaurants</h1>
                <p class="text-slate-500 mt-2 text-lg">Select a property to manage its menu and settings</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach (Auth::user()->restaurants as $res)
                    <a href="{{ url('/dashboard/' . $res->slug) }}"
                        class="group relative h-64 rounded-3xl overflow-hidden shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl">

                        {{-- Background Hero Image --}}
                        <img src="{{ $res->getHeroUrl() }}" alt="{{ $res->name }}"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                        {{-- Dark Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

                        {{-- Content --}}
                        <div class="absolute inset-0 p-8 flex flex-col justify-end">
                            <h2 class="text-2xl font-black text-white mb-1">{{ $res->name }}</h2>
                            <div class="flex items-center gap-2">
                                <span class="inline-block w-2 h-2 rounded-full bg-green-400"></span>
                                <p class="text-white/70 text-sm font-medium uppercase tracking-widest">Manage Dashboard</p>
                            </div>
                        </div>

                        {{-- Hover Border --}}
                        <div
                            class="absolute inset-0 border-4 border-transparent group-hover:border-white/20 rounded-3xl transition-colors">
                        </div>
                    </a>
                @endforeach

                {{-- Add New Restaurant Placeholder --}}
                <a href="#"
                    class="group h-64 rounded-3xl border-2 border-dashed border-slate-300 flex flex-col items-center justify-center gap-3 text-slate-400 hover:border-indigo-500 hover:text-indigo-500 hover:bg-indigo-50/50 transition-all">
                    <div
                        class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-indigo-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <span class="font-bold">Add New Restaurant</span>
                </a>
            </div>

        </div>
    </div>
@endsection
