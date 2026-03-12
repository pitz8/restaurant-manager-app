@extends('layouts.microsite')

@section('content')
    {{-- --- MENU SECTION --- --}}
    <section id="menu" class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black mb-4">{{ app()->getLocale() === 'it' ? 'Il Nostro Menu' : 'Our Menu' }}</h2>
                <div class="w-20 h-1.5 bg-brand mx-auto rounded-full"></div>
            </div>

            {{-- Make sure your menu partial is also updated to use accessors --}}
            @include('restaurant.menu', [
                'menu' => $restaurant->menus()->where('is_active', true)->with('categories.items')->first(),
            ])
        </div>
    </section>

    {{-- --- GALLERY --- --}}
    @if ($restaurant->subscription_level > 1 && count($galleryImages) > 0)
        <section id="gallery" class="py-24 bg-white" x-data="{ 
            expanded: false, 
            lightbox: false, 
            imgSrc: '' 
        }">
            <div class="max-w-7xl mx-auto px-6">
                
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-black mb-4">
                        {{ app()->getLocale() === 'it' ? 'Galleria' : 'Gallery' }}
                    </h2>
                    <div class="w-20 h-1.5 bg-brand mx-auto rounded-full"></div>
                </div>

                <div class="relative">
                    {{-- 
                        Container height: 
                        Collapsed: max-h-[400px] (Usually 1.5 rows on desktop)
                        Expanded: max-h-[none] 
                    --}}
                    <div 
                        id="gallery-container" 
                        :class="expanded ? 'max-h-full' : 'max-h-[400px]'"
                        class="relative overflow-hidden transition-[max-height] duration-700 ease-in-out"
                    >
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($galleryImages as $image)
                                @php
                                    $filename = pathinfo($image, PATHINFO_FILENAME);
                                    $alt = $restaurant->name . ' - ' . str_replace(['-', '_'], ' ', $filename);
                                    $fullPath = asset('storage/' . $image);
                                @endphp

                                <div 
                                    class="group relative overflow-hidden rounded-2xl bg-slate-100 aspect-square cursor-pointer"
                                    @click="imgSrc = '{{ $fullPath }}'; lightbox = true"
                                >
                                    <img
                                        src="{{ $fullPath }}"
                                        alt="{{ $alt }}"
                                        loading="lazy"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    >
                                    <div class="absolute inset-0 bg-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <div class="bg-white/20 backdrop-blur-md p-3 rounded-full">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Fade gradient overlay --}}
                        <div 
                            x-show="!expanded"
                            class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent pointer-events-none transition-opacity duration-500"
                        ></div>
                    </div>

                    {{-- The Toggle Button --}}
                    <div class="flex justify-center mt-12">
                        <button 
                            @click="expanded = !expanded"
                            type="button"
                            class="group inline-flex items-center px-8 py-3 bg-slate-900 text-white font-bold rounded-full hover:bg-brand transition-all duration-300 shadow-xl"
                        >
                            <span x-text="expanded ? '{{ app()->getLocale() === 'it' ? 'Mostra Meno' : 'Show Less' }}' : '{{ app()->getLocale() === 'it' ? 'Vedi Galleria Completa' : 'View Full Gallery' }}'"></span>
                            <svg 
                                :class="expanded ? 'rotate-180' : ''"
                                class="ml-2 h-5 w-5 transition-transform duration-500" 
                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Lightbox Modal --}}
            <template x-teleport="body">
                <div 
                    x-show="lightbox" 
                    class="fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/95 p-4"
                    @keydown.escape.window="lightbox = false"
                    x-cloak
                >
                    <button @click="lightbox = false" class="absolute top-6 right-6 text-white hover:text-brand z-[1001]">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    <img :src="imgSrc" class="max-w-full max-h-full rounded-lg object-contain" @click.away="lightbox = false">
                </div>
            </template>
        </section>
    @endif

    {{-- --- CONTACT & FOOTER --- --}}
    <section id="contact" class="py-24 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-16">
            <div>
                <h2 class="text-4xl font-black mb-8">{{ app()->getLocale() === 'it' ? 'Vieni a Trovarci' : 'Visit Us' }}
                </h2>
                <span class="text-brand font-bold">Address:</span>
                <p class="text-slate-400 text-lg mb-8">{{ $restaurant->address }}</p>
        
                <div class="space-y-4">
                    <p class="flex items-center gap-4">
                        <span class="text-brand font-bold">Phone:</span>
                        <a href="tel:{{ $restaurant->phone }}" class="underline hover:text-brand transition-colors">
                            {{ $restaurant->phone }}
                        </a>
                    </p>
                    <p class="flex items-center gap-4">
                        <span class="text-brand font-bold">Email:</span> 
                        <a href="mailto:{{ $restaurant->email }}" class="underline hover:text-brand transition-colors">
                        {{ $restaurant->email }}
                        </a>
                    </p>
                </div>
            </div>

            @if ($restaurant->google_maps_link)
                <div class="rounded-3xl overflow-hidden h-64 bg-slate-800 border border-slate-700">
                    <iframe width="100%" height="100%" frameborder="0" style="border:0"
                        src="{{ $restaurant->google_maps_link }}" allowfullscreen>
                    </iframe>
                </div>
            @endif
        </div>

        <div class="text-center mt-24 pt-8 border-t border-slate-800 text-slate-500 text-sm">
            &copy; {{ date('Y') }} {{ $restaurant->name }}. Powered by YourSoftwareName.
        </div>
    </section>
@endsection
