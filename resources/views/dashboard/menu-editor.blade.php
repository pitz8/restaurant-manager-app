@extends('layouts.admin') {{-- Use your existing admin layout --}}

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    {{-- 1. MAIN WRAPPER: Handles all Modal states and the Active Category ID --}}
    <div x-data="{
        showCatModal: false,
        showItemModal: false,
        activeCategoryId: null,
        editingItem: { id: null, name: '', price: '', description: '', tags: [] },
    
        editItem(item) {
            this.editingItem = {
                id: item.id,
                name: item.name,
                price: item.price,
                description: item.description || '',
                tags: item.tags || []
            };
            this.activeCategoryId = item.category_id;
            this.showItemModal = true;
        }
    }" class="max-w-5xl mx-auto px-4 py-8">

        {{-- HEADER SECTION --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-tight">Menu Editor</h1>
                <p class="text-xs text-indigo-600 font-bold uppercase tracking-widest">⇅ Drag categories to reorder</p>
            </div>

            <button @click="showCatModal = true" type="button"
                class="bg-black text-white px-6 py-2 rounded-full font-bold text-xs tracking-widest hover:bg-gray-800 transition shadow-lg">
                + NEW CATEGORY
            </button>
        </div>

        {{-- CATEGORY LIST --}}
        <div id="category-list" class="space-y-6">
            @foreach ($menu->categories()->orderBy('position')->get() as $category)
                <div class="category-item bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:border-indigo-300 transition-colors"
                    data-id="{{ $category->id }}">

                    {{-- Category Header --}}
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center group/cat">
                        <div class="flex items-center">
                            {{-- THE DRAG HANDLE --}}
                            <div
                                class="drag-handle cursor-grab active:cursor-grabbing text-gray-300 hover:text-indigo-500 transition-colors pr-3">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M7 2a2 2 0 100 4 2 2 0 000-4zM7 8a2 2 0 100 4 2 2 0 000-4zM7 14a2 2 0 100 4 2 2 0 000-4zM13 2a2 2 0 100 4 2 2 0 000-4zM13 8a2 2 0 100 4 2 2 0 000-4zM13 14a2 2 0 100 4 2 2 0 000-4z" />
                                </svg>
                            </div>

                            <h3 class="font-black text-slate-800 uppercase tracking-wider text-sm">
                                {{ $category->name }}
                                <span class="ml-2 text-[10px] text-slate-400 font-normal">({{ $category->items->count() }}
                                    items)</span>
                            </h3>
                        </div>

                        <div class="flex items-center gap-2">
                            {{-- Trigger Add Item Modal --}}
                            <button @click="showItemModal = true; activeCategoryId = {{ $category->id }}" type="button"
                                class="text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition">
                                + Add Item
                            </button>

                            <div class="w-px h-3 bg-gray-200 mx-1"></div>

                            {{-- Delete Category --}}
                            <form action="{{ route('categories.destroy', [$restaurant, $category]) }}" method="POST"
                                onsubmit="return confirm('Delete \'{{ $category->name }}\' and all items?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Items Table (Grid) --}}
                    <div class="divide-y divide-gray-100">
                        @forelse($category->items()->orderBy('position')->get() as $item)
                            <div class="item-item px-6 py-4 grid grid-cols-12 items-center gap-4 group"
                                data-id="{{ $item->id }}"> {{-- <--- Add data-id here --}}

                                <div class="col-span-6 flex row items-center gap-3">
                                    {{-- ITEM DRAG HANDLE --}}
                                    <div
                                        class="item-drag-handle cursor-grab active:cursor-grabbing text-gray-200 hover:text-indigo-400 transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M7 2a2 2 0 100 4 2 2 0 000-4zM7 8a2 2 0 100 4 2 2 0 000-4zM7 14a2 2 0 100 4 2 2 0 000-4zM13 2a2 2 0 100 4 2 2 0 000-4zM13 8a2 2 0 100 4 2 2 0 000-4zM13 14a2 2 0 100 4 2 2 0 000-4z" />
                                        </svg>
                                    </div>
                                    <div class="col-span-6">
                                        <div class="flex items-center gap-2">
                                            <h4
                                                class="font-bold text-sm {{ $item->is_available ? 'text-gray-900' : 'text-gray-400 line-through' }}">
                                                {{ $item->name }}
                                            </h4>
                                        </div>
                                        {{-- Render Tags --}}
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            @if (is_array($item->tags) && count($item->tags) > 0)
                                                @foreach ($item->tags as $tag)
                                                    @php
                                                        // Look up the colors from the Model, fallback to gray if not found
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
                                        <p class="text-xs text-gray-500 truncate mt-0.5">{{ $item->description }}</p>
                                    </div>
                                </div>

                                <div class="col-span-2">
                                    <span
                                        class="font-mono font-bold {{ $item->is_available ? 'text-gray-700' : 'text-gray-300' }} text-sm">
                                        ${{ number_format($item->price, 2) }}
                                    </span>
                                </div>

                                <div class="col-span-4 flex items-center justify-end gap-2">
                                    {{-- Toggle Sold Out --}}
                                    <form action="{{ route('items.toggleAvailability', [$restaurant, $item]) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="type" value="sold_out">
                                        <button type="submit"
                                            class="text-[10px] font-black w-20 py-1 rounded-full border-2 transition-all 
                                        {{ $item->is_available ? 'border-gray-200 text-gray-400 hover:border-red-500 hover:text-red-500' : 'bg-red-500 border-red-500 text-white' }}">
                                            {{ $item->is_available ? 'STOCK' : 'SOLD OUT' }}
                                        </button>
                                    </form>

                                    {{-- Delete Item --}}
                                    <form action="{{ route('items.destroy', [$restaurant, $item]) }}" method="POST"
                                        onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-300 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                    {{-- Edit Item Button --}}
                                    <button type="button" @click="editItem({{ json_encode($item) }})"
                                        class="p-1.5 text-gray-300 hover:text-indigo-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400 text-xs italic">No items here yet.</div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>

        {{-- MODAL: NEW CATEGORY --}}
        <div x-show="showCatModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">

            <div @click="showCatModal = false" class="fixed inset-0 bg-slate-900/60"></div>

            <div
                class="relative bg-white rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] max-w-md w-full overflow-hidden border border-white/20">
                <form action="{{ route('categories.store', $restaurant) }}" method="POST">
                    @csrf
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div
                                class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M4 6h16M4 12h16m-7 6h7" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">New Category</h3>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-indigo-600 uppercase tracking-[0.2em] ml-1">Category
                                Name</label>
                            <input type="text" name="name" required placeholder="e.g. Signature Mains"
                                class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all outline-none text-slate-900 font-bold placeholder:text-slate-300">
                        </div>
                    </div>

                    <div class="bg-slate-50/80 px-8 py-6 flex items-center justify-between">
                        <button @click="showCatModal = false" type="button"
                            class="text-slate-400 font-black text-xs uppercase tracking-widest hover:text-slate-600 transition">Cancel</button>
                        <button type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 hover:-translate-y-0.5 transition-all active:scale-95 shadow-lg shadow-indigo-200">
                            Create Category
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL: ADD ITEM --}}
        <div x-show="showItemModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div @click="showItemModal = false; editingItem = { id: null, name: '', price: '', description: '', tags: [] }"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-md"></div>

            <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden">
                {{-- DYNAMIC FORM ACTION --}}
                <form :action="editingItem.id ? `/dashboard/items/${editingItem.id}` : '{{ route('items.store', $restaurant) }}'"
                    method="POST">
                    @csrf
                    {{-- ADD PUT METHOD IF EDITING --}}
                    <template x-if="editingItem.id">
                        @method('PUT')
                    </template>

                    <input type="hidden" name="category_id" :value="activeCategoryId">

                    <div class="p-10">
                        <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tighter mb-8"
                            x-text="editingItem.id ? 'Edit Menu Item' : 'Add Menu Item'"></h3>

                        <div class="space-y-6">
                            <div>
                                <label
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block">Name</label>
                                <input type="text" name="name" x-model="editingItem.name" required
                                    class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:bg-white transition-all outline-none font-bold">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block">Price</label>
                                <input type="number" step="0.01" name="price" x-model="editingItem.price" required
                                    class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl font-mono font-bold">
                            </div>

                            <div>
                                <label
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block">Description</label>
                                <textarea name="description" x-model="editingItem.description" rows="2"
                                    class="w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl"></textarea>
                            </div>

                            {{-- Tags Selection --}}
                            <div>
                                <label
                                    class="text-[10px] font-black text-indigo-600 uppercase tracking-widest block mb-2">Dietary
                                    Tags</label>
                                {{-- Dietary Tags Selection Grid --}}
                                <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                    @foreach (\App\Models\MenuItem::AVAILABLE_TAGS as $tag => $colors)
                                        <label class="group relative flex items-center cursor-pointer">
                                            {{-- The Hidden Checkbox --}}
                                            <input type="checkbox" name="tags[]" value="{{ $tag }}"
                                                x-model="editingItem.tags" class="hidden peer">

                                            {{-- The Styled Tag Button --}}
                                            <div
                                                class="w-full px-3 py-2.5 rounded-xl border-2 border-slate-100 bg-white 
                        text-[8px] font-black uppercase tracking-tight text-slate-400
                        peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-600 
                        transition-all duration-200 flex items-center justify-between group-hover:border-slate-200">

                                                <span>{{ $tag }}</span>

                                                {{-- Visual checkmark that appears when selected --}}
                                                <svg x-show="editingItem.tags && editingItem.tags.includes('{{ $tag }}')"
                                                    class="w-3 h-3 text-indigo-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L7 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-600 p-2">
                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-5 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-700 transition-all">
                            <span x-text="editingItem.id ? 'SAVE CHANGES' : 'ADD TO MENU'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div> {{-- END MAIN WRAPPER --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- 1. REUSABLE SAVE FUNCTION ---
            const saveOrder = (url, ids) => {
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ids: ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => console.log('Order updated:', data))
                    .catch(error => console.error('Error saving order:', error));
            };

            // --- 2. CATEGORY SORTING ---
            const categoryList = document.getElementById('category-list');
            if (categoryList) {
                Sortable.create(categoryList, {
                    handle: '.drag-handle', // Handle on the category div
                    animation: 250,
                    ghostClass: 'opacity-40',
                    chosenClass: 'border-indigo-500',
                    onEnd: function() {
                        let ids = Array.from(categoryList.querySelectorAll('.category-item'))
                            .map(item => item.dataset.id);

                        saveOrder('{{ route('categories.reorder', $restaurant) }}', ids);
                    }
                });
            }

            // --- 3. ITEM SORTING (Inside each category) ---
            // We target the item containers (your "divide-y" divs)
            document.querySelectorAll('.divide-y').forEach(itemList => {
                Sortable.create(itemList, {
                    handle: '.item-drag-handle', // Handle on the individual item rows
                    animation: 200,
                    ghostClass: 'bg-indigo-50',
                    onEnd: function() {
                        // Filter for only the item-item divs inside THIS specific list
                        let ids = Array.from(itemList.querySelectorAll('.item-item'))
                            .map(item => item.dataset.id);

                        saveOrder('{{ route('items.reorder', $restaurant) }}', ids);
                    }
                });
            });

        });
    </script>
@endsection
