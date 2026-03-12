@extends('layouts.admin')

@section('content')
    @php
        $currentTab = 'identity';
        if (session('tab')) {
            $currentTab = session('tab');
        }
        if (request('tab')) {
            $currentTab = request('tab');
        }

        // Auto-switch to tab with errors
        if ($errors->hasAny(['logo', 'hero'])) {
            $currentTab = 'images';
        }
        if ($errors->hasAny(['meta_title', 'meta_description'])) {
            $currentTab = 'languages';
        }
    @endphp

    <div x-data="{ tab: '{{ $currentTab }}' }">

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-sky-900">Website Settings</h1>
                <p class="text-sky-500">Customize how your restaurant appears to the world.</p>
            </div>
            <a href="{{ route('restaurant.home', $restaurant->slug) }}" target="_blank"
                class="flex items-center gap-2 px-4 py-2 bg-sky-100 hover:bg-sky-200 rounded-lg text-sky-700 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                View Live Site
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">

            {{-- Sidebar Navigation --}}
            <div class="space-y-1">
                <button @click="tab = 'identity'"
                    :class="tab === 'identity' ? 'bg-slate-50 text-slate-700' : 'text-sky-600 hover:bg-sky-50'"
                    class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition hover:text-sky-950">
                    Identity & Branding
                </button>
                <button @click="tab = 'languages'"
                    :class="tab === 'languages' ? 'bg-slate-50 text-slate-700' : 'text-sky-600 hover:bg-sky-50'"
                    class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition hover:text-sky-950">
                    Languages & SEO
                </button>
                <button @click="tab = 'images'"
                    :class="tab === 'images' ? 'bg-slate-50 text-slate-700' : 'text-sky-600 hover:bg-sky-50'"
                    class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition hover:text-sky-950">
                    Media Assets
                </button>
                <button @click="tab = 'gallery'"
                    :class="tab === 'gallery' ? 'bg-slate-50 text-slate-700' : 'text-sky-600 hover:bg-sky-50'"
                    class="w-full flex items-center px-4 py-3 text-sm font-semibold rounded-lg transition hover:text-sky-950">
                    Gallery
                </button>
            </div>

            {{-- Main Form Content --}}
            <div class="md:col-span-3">
                <form action="{{ route('website.update', $restaurant) }}" method="POST" enctype="multipart/form-data"
                    class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="current_tab" :value="tab">

                    {{-- Tab: Identity --}}
                    <div x-show="tab === 'identity'" class="p-8 space-y-10" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            {{-- Primary Color Section --}}
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-sky-900">Brand Accent Color</label>
                                <p class="text-xs text-sky-500 italic">This color will be used for buttons, links, and
                                    highlights.</p>
                                <div class="flex items-center gap-4 p-3 bg-sky-50 rounded-xl border border-sky-200 w-fit">
                                    <div
                                        class="relative w-12 h-12 rounded-lg overflow-hidden border border-white shadow-sm">
                                        <input type="color" name="primary_color"
                                            value="{{ $restaurant->primary_color ?? '#4f46e5' }}"
                                            class="absolute -inset-2 w-[200%] h-[200%] cursor-pointer border-none bg-transparent">
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-sky-400 uppercase tracking-tighter">Hex
                                            Code</span>
                                        <span
                                            class="text-sm font-mono text-sky-700">{{ $restaurant->primary_color ?? '#4f46e5' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Font Selection Section --}}
                            <div x-data="{ selectedFont: '{{ $restaurant->font_family ?? 'sans' }}' }" class="space-y-3">
                                <label class="block text-sm font-semibold text-sky-900">Typography Style</label>
                                <select name="font_family" x-model="selectedFont"
                                    class="w-full rounded-xl border-sky-200 bg-white px-4 py-3 text-sm shadow-sm transition focus:border-slate-500 focus:ring-4 focus:ring-slate-500/10">
                                    <option value="sans">Modern Sans (Inter)</option>
                                    <option value="serif">Classic Elegant (Playfair Display)</option>
                                    <option value="display">Bold Stylish (Calistoga)</option>
                                    <option value="monsterrat">Clean Geometric (Montserrat)</option>
                                    <option value="handwriting">Casual Script (Caveat)</option>
                                    <option value="rust">Rustic / Vintage (Special Elite)</option>
                                </select>

                                {{-- Live Preview Box --}}
                                <div
                                    class="relative overflow-hidden p-6 bg-sky-900 rounded-xl flex flex-col items-center justify-center min-h-[110px] shadow-inner group">
                                    <span
                                        class="absolute top-2 left-3 text-[10px] text-sky-500 uppercase tracking-[0.2em] font-bold">Preview</span>
                                    <p class="text-2xl text-white transition-all duration-300 group-hover:scale-105"
                                        :style="{ fontFamily: getFontFamily(selectedFont) }">
                                        {{ $restaurant->name ?? 'Restaurant Name' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Languages & SEO --}}
                    <div x-show="tab === 'languages'" x-data="{ langTab: 'it' }" class="p-8 space-y-8">

                        {{-- Flag Selector --}}
                        <div class="flex items-center gap-6 border-b border-sky-100 pb-8">
                            @foreach (['it', 'en', 'fr', 'de'] as $l)
                                @php
                                    $isLocked =
                                        ($l == 'en' && $restaurant->subscription_level < 1) ||
                                        (in_array($l, ['fr', 'de']) && $restaurant->subscription_level < 2);
                                @endphp
                                <button type="button" @click="{{ !$isLocked ? "langTab = '$l'" : '' }}"
                                    class="group relative flex flex-col items-center gap-2 transition"
                                    :class="langTab === '{{ $l }}' ? 'opacity-100' :
                                        '{{ $isLocked ? 'opacity-20 cursor-not-allowed' : 'opacity-40 hover:opacity-100' }}'">

                                    {{-- [Flags SVGs here - keeping your existing SVG code] --}}
                                    <img src="{{ asset('images/flags/' . $l . '.svg') }}" alt="{{ strtoupper($l) }} flag"
                                        class="w-12 h-9 rounded-lg shadow-sm object-cover border border-sky-100 transition-transform group-hover:scale-105">

                                    @if ($isLocked)
                                        <svg class="absolute -top-2 -right-2 w-4 h-4 text-amber-500 bg-white rounded-full p-0.5 shadow-sm"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" />
                                        </svg>
                                    @endif
                                    <span class="text-[10px] font-bold uppercase tracking-widest"
                                        :class="langTab === '{{ $l }}' ? 'text-slate-600' : 'text-sky-400'">{{ strtoupper($l) }}</span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Form Fields per Language --}}
                        <div class="space-y-6 min-h-[300px]">
                            @foreach (['it', 'en', 'fr', 'de'] as $lang)
                                @php
                                    $isLocked =
                                        ($lang == 'en' && $restaurant->subscription_level < 1) ||
                                        (in_array($lang, ['fr', 'de']) && $restaurant->subscription_level < 2);

                                    // This is the fix to ensure data loads even with Model Accessors
                                    $nameVal = $restaurant->getRawOriginal('name')
                                        ? json_decode($restaurant->getRawOriginal('name'), true)[$lang] ?? ''
                                        : $restaurant->name[$lang] ?? '';
                                    $descVal = $restaurant->getRawOriginal('description')
                                        ? json_decode($restaurant->getRawOriginal('description'), true)[$lang] ?? ''
                                        : $restaurant->description[$lang] ?? '';
                                    $metaVal = $restaurant->getRawOriginal('meta_title')
                                        ? json_decode($restaurant->getRawOriginal('meta_title'), true)[$lang] ?? ''
                                        : $restaurant->meta_title[$lang] ?? '';
                                @endphp

                                <div x-show="langTab === '{{ $lang }}'" x-transition:enter class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-bold text-sky-700 mb-2">Restaurant Name
                                            ({{ strtoupper($lang) }})</label>
                                        <input type="text" name="name[{{ $lang }}]" value="{{ $nameVal }}"
                                            {{ $isLocked ? 'readonly' : '' }}
                                            class="w-full rounded-lg border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 {{ $isLocked ? 'bg-sky-50 opacity-50' : '' }}">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-sky-700 mb-2">Description
                                            ({{ strtoupper($lang) }})</label>
                                        <textarea name="description[{{ $lang }}]" rows="4" {{ $isLocked ? 'readonly' : '' }}
                                            class="w-full rounded-lg border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 {{ $isLocked ? 'bg-sky-50 opacity-50' : '' }}">{{ $descVal }}</textarea>
                                    </div>

                                    <div class="bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                                        <label
                                            class="block text-xs font-black text-slate-700 uppercase tracking-widest mb-2">SEO
                                            Meta Title</label>
                                        <input type="text" name="meta_title[{{ $lang }}]"
                                            value="{{ $metaVal }}" {{ $isLocked ? 'readonly' : '' }}
                                            class="w-full rounded-lg border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 {{ $isLocked ? 'bg-transparent' : '' }}">
                                    </div>

                                    @if ($isLocked)
                                        <div
                                            class="bg-sky-900/5 backdrop-blur-[2px] rounded-2xl p-8 text-center border-2 border-dashed border-sky-200 mt-4">
                                            <h4 class="text-sky-900 font-bold italic">Language Locked</h4>
                                            <p class="text-sm text-sky-500 mb-4">Upgrade your plan to unlock
                                                {{ strtoupper($lang) }} translations.</p>
                                            <a href="/upgrade"
                                                class="inline-block bg-amber-500 text-white text-xs font-black px-6 py-2 rounded-full">Upgrade
                                                Plan</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tab: Images --}}
                    <div x-show="tab === 'images'" class="p-8 space-y-8">
                        <div>
                            <label class="block text-sm font-bold text-sky-700 mb-4">Hero Background Image</label>
                            <div class="flex items-start gap-6">
                                <img src="{{ $restaurant->getHeroUrl() }}"
                                    class="w-40 h-24 object-cover rounded-lg border border-sky-200">
                                <div class="flex-grow">
                                    <input type="file" name="hero"
                                        class="block w-full text-sm text-sky-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-sky-200 pt-6">
                            <label class="block text-sm font-bold text-sky-700 mb-3">Google Maps Embed Link</label>
                            <div class="space-y-2">
                                <input type="url" name="google_maps_link"
                                    value="{{ $restaurant->google_maps_link ?? '' }}"
                                    placeholder="https://maps.google.com/..."
                                    class="w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 border-t-0 border-l-0 border-r-0 border-b-2 border-slate-200">
                                <p class="text-xs text-sky-500 flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Paste your Google Maps embed or share link here. You can get this from Google Maps by
                                    clicking "Share" and copying the link.
                                </p>
                            </div>
                        </div>

                        <div class="border-t border-sky-200 pt-6">
                            <h3 class="text-sm font-bold text-sky-700 mb-4">Contact Information</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-sky-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ $restaurant->phone ?? '' }}"
                                        placeholder="+39 123 456 7890"
                                        class="w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 border-t-0 border-l-0 border-r-0 border-b-2 border-slate-200">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-sky-700 mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ $restaurant->email ?? '' }}"
                                        placeholder="info@restaurant.com"
                                        class="w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 border-t-0 border-l-0 border-r-0 border-b-2 border-slate-200">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-sky-700 mb-2">Street Address</label>
                                    <textarea name="address" rows="3" placeholder="123 Main Street, New York, NY 10001"
                                        class="w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-500 focus:ring-0 border-t-0 border-l-0 border-r-0 border-b-2 border-slate-200">{{ $restaurant->address ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tab: Gallery --}}
                    <div x-show="tab === 'gallery'" class="p-8 space-y-8">
                        <div>
                            <h3 class="text-lg font-bold text-sky-900 mb-4">Restaurant Photo Gallery</h3>
                            <p class="text-sm text-sky-600 mb-6">Upload high-quality photos of your restaurant. They will
                                be displayed in a horizontal scrollable gallery on your website.</p>

                            <div class="space-y-4">
                                {{-- Upload Area --}}
                                <div x-data="{ filesCount: 0 }"
                                    class="border-2 border-dashed border-sky-300 rounded-xl p-8 text-center hover:border-slate-400 transition bg-white">

                                    <label class="cursor-pointer inline-flex flex-col items-center w-full">
                                        {{-- Icon changes color if files are selected --}}
                                        <svg class="w-12 h-12 mb-3 transition-colors"
                                            :class="filesCount > 0 ? 'text-slate-500' : 'text-sky-400'" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>

                                        {{-- Dynamic Text --}}
                                        <span class="text-sm font-semibold text-sky-700">
                                            <template x-if="filesCount === 0">
                                                <span>Click to upload images or drag and drop</span>
                                            </template>
                                            <template x-if="filesCount > 0">
                                                <span class="text-slate-600">
                                                    <span x-text="filesCount"></span> files selected
                                                </span>
                                            </template>
                                        </span>

                                        <span class="text-xs text-sky-500 mt-1">PNG, JPG, WEBP up to 5MB each</span>

                                        {{-- The Input --}}
                                        <input type="file" name="gallery[]" multiple accept="image/*" class="hidden"
                                            id="gallery-input" @change="filesCount = $event.target.files.length">
                                    </label>

                                    {{-- Optional: Reset button if they made a mistake --}}
                                    <button type="button" x-show="filesCount > 0"
                                        @click="filesCount = 0; document.getElementById('gallery-input').value = ''"
                                        class="mt-3 text-xs text-red-500 hover:text-red-700 font-medium underline" x-cloak>
                                        Clear selection
                                    </button>
                                </div>

                                {{-- Gallery Preview --}}
                                <div>
                                    <h4 class="text-sm font-bold text-sky-700 mb-3">Current Gallery</h4>
                                    @if ($restaurant->gallery && count($restaurant->gallery) > 0)
                                        <div class="flex gap-3 overflow-x-auto pb-2">
                                            @foreach ($restaurant->gallery as $index => $image)
                                                <div class="relative group flex-shrink-0">
                                                    <img src="{{ asset("storage/restaurants/{$restaurant->slug}/gallery/{$image}") }}"
                                                        alt="Gallery image {{ $index + 1 }}"
                                                        class="h-24 w-32 object-cover rounded-lg border border-sky-200">
                                                    <button type="button"
                                                        onclick="removeGalleryImage('{{ $image }}')"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 bg-sky-50 rounded-lg border border-sky-200">
                                            <p class="text-sm text-sky-500">No gallery images yet. Upload images to get
                                                started.</p>
                                        </div>
                                    @endif
                                </div>

                                {{-- Hidden input to track deletions --}}
                                <input type="hidden" id="deleted-images" name="deleted_images" value="">
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-8 py-4 bg-sky-50 border-t border-sky-200 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-8 py-3 bg-sky-900 hover:bg-sky-950 hover:text-sky-600 text-white text-sm font-bold rounded-xl shadow-xl shadow-slate-500/10 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M5 13l4 4L19 7" stroke-width="3" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function getFontFamily(key) {
            const fonts = {
                'sans': "'Inter', sans-serif",
                'serif': "'Playfair Display', serif",
                'display': "'Calistoga', serif",
                'monsterrat': "'Montserrat', sans-serif",
                'handwriting': "'Caveat', cursive",
                'rust': "'Special Elite', system-ui"
            };
            return fonts[key] || fonts['sans'];
        }

        let deletedImages = [];

        // Handle file input for gallery
        document.getElementById('gallery-input')?.addEventListener('change', function(e) {
            const form = this.closest('form');
            // Files will be automatically submitted with the form
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('.border-dashed');
        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('bg-slate-50', 'border-slate-400');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('bg-slate-50', 'border-slate-400');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('bg-slate-50', 'border-slate-400');

                const input = document.getElementById('gallery-input');
                input.files = e.dataTransfer.files;

                // Trigger change event
                input.dispatchEvent(new Event('change', {
                    bubbles: true
                }));
            });
        }

        function removeGalleryImage(filename) {
            // Send AJAX request to delete the image
            fetch('{{ route('gallery.delete', $restaurant) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        filename: filename
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection
