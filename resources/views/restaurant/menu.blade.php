<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', App::getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Clean Accessors used here --}}
    <title>{{ $restaurant->name }} - Menu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Space+Mono&family=Inter:wght@400;700;900&family=Cormorant+Garamond:ital,wght@0,600;1,600&family=Montserrat:wght@900&family=Outfit:wght@300;800&family=Courier+Prime&display=swap" rel="stylesheet">

    <style>
        * {
            user-select: none;
        }

        .theme-elegant {
            font-family: 'Playfair Display', serif !important;
        }

        .theme-paper {
            font-family: 'Cormorant Garamond', serif !important;
            background-image: url('https://www.transparenttextures.com/patterns/p6.png') !important;
        }

        .theme-retro {
            font-family: 'Space Mono', monospace !important;
        }

        .theme-modern {
            font-family: 'Inter', sans-serif !important;
        }

        .theme-midnight {
            font-family: 'Outfit', sans-serif !important;
        }

        .theme-botanical {
            font-family: 'Cormorant Garamond', serif !important;
        }

        .theme-neon {
            font-family: 'Montserrat', sans-serif !important;
            text-shadow: 0 0 8px rgba(217, 70, 239, 0.5);
        }

        .theme-bistro {
            font-family: 'Courier Prime', monospace !important;
        }

        .neon-border {
            border: 1px solid rgba(217, 70, 239, 0.4);
            box-shadow: 0 0 15px rgba(217, 70, 239, 0.2);
        }
    </style>
</head>

@php
    $themeStyles = [
        'modern' => 'background: #ffffff; color: #0f172a;',
        'elegant' => 'background: radial-gradient(circle at top, #1e293b 0%, #020617 100%); color: #fde68a;',
        'retro' => 'background: #fff7ed; color: #431407;',
        'paper' => 'background: #fdfaf3; color: #1c1917;',
        'midnight' => 'background: #020617; color: #f8fafc;',
        'botanical' => 'background: #f0f4f0; color: #064e3b;',
        'neon' => 'background: #000000; color: #d946ef;',
        'bistro' => 'background: #ffffff; color: #b91c1c; border: 12px double #b91c1c;',
    ];
    $currentStyle = $themeStyles[$restaurant->theme] ?? $themeStyles['modern'];
@endphp

<body class="min-h-screen transition-all duration-500 theme-{{ $restaurant->theme }}" style="{{ $currentStyle }}">

    <div class="max-w-xl mx-auto px-6 py-12">
        <header class="text-center mb-16">
            <h1
                class="text-4xl @if ($restaurant->theme == 'neon') italic tracking-tighter @else font-black uppercase @endif mb-2">
                {{ $restaurant->name }}
            </h1>

            @if ($restaurant->theme == 'botanical')
                <span class="text-green-700 opacity-40">🌿 ━━━ 🌿</span>
            @elseif($restaurant->theme == 'neon')
                <div
                    class="h-1 w-full bg-gradient-to-r from-fuchsia-500 via-cyan-400 to-fuchsia-500 shadow-[0_0_15px_rgba(217,70,239,0.5)]">
                </div>
            @else
                <div class="flex justify-center items-center gap-3 opacity-40">
                    <div class="h-[1px] w-8 bg-current"></div>
                    <span class="text-[10px] tracking-[0.3em] uppercase">Menu</span>
                    <div class="h-[1px] w-8 bg-current"></div>
                </div>
            @endif
        </header>

        @foreach ($menu->categories()->orderBy('position')->get() as $category)
            <section class="mb-14">
                <h2
                    class="text-xl font-bold mb-8 @if ($restaurant->theme == 'bistro') border-double border-b-4 border-red-700 @else border-b border-current @endif pb-2 opacity-90 uppercase tracking-widest">
                    {{-- Automagically translated category name --}}
                    {{ $category->name }}
                </h2>

                <div class="space-y-10">
                    @foreach ($category->items()->orderBy('position')->get() as $item)
                        @if (!$item->is_available)
                            @continue
                        @endif

                        <div
                            class="@if ($restaurant->theme == 'neon') neon-card p-4 rounded-lg shadow-[0_0_10px_rgba(217,70,239,0.2)] @endif transition-transform">
                            <div class="flex justify-between items-baseline gap-2">
                                <h3
                                    class="font-bold text-lg leading-tight uppercase @if ($restaurant->theme == 'midnight') tracking-wide @endif">
                                    {{-- Automagically translated item name --}}
                                    {{ $item->name }}
                                </h3>

                                <div
                                    class="flex-grow border-b @if ($restaurant->theme == 'retro') border-dashed @else border-dotted @endif border-current opacity-20 mb-1">
                                </div>

                                <span class="font-black text-lg @if ($restaurant->theme == 'neon') text-cyan-400 @endif">
                                    {{-- Use 180°C format or similar if needed, but for price, simple is better --}}
                                    €{{ number_format($item->price, 2) }}
                                </span>
                            </div>

                            @if ($item->description)
                                <p
                                    class="text-sm opacity-60 italic mt-1 leading-relaxed @if ($restaurant->theme == 'botanical') text-green-800 @endif">
                                    {{-- Automagically translated item description --}}
                                    {{ $item->description }}
                                </p>
                            @endif

                            {{-- Tags --}}
                            <div class="flex flex-wrap gap-1 mt-2">
                                @if (is_array($item->tags))
                                    @foreach ($item->tags as $tag)
                                        @php
                                            $colors =
                                                \App\Models\MenuItem::AVAILABLE_TAGS[$tag] ??
                                                'bg-slate-50 text-slate-500 border-slate-100';
                                        @endphp
                                        <span
                                            class="{{ $colors }} text-[8px] px-1.5 py-0.5 rounded font-black uppercase tracking-tighter border">
                                            {{ $tag }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    {{-- SEO Footer --}}
    <footer class="text-center pb-12 opacity-40 text-[10px] uppercase tracking-widest">
        &copy; {{ date('Y') }} {{ $restaurant->name }}
    </footer>
</body>

</html>
