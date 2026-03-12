<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public const AVAILABLE_TAGS = [
        // --- DIETARY & LIFESTYLE (Greens / Blue) ---
        'Vegan'        => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'Vegetarian'   => 'bg-green-50 text-green-700 border-green-200/50',
        'Plant-Based'  => 'bg-teal-50 text-teal-700 border-teal-200/50',
        'Halal'        => 'bg-indigo-50 text-indigo-700 border-indigo-200/50',
        'Kosher'       => 'bg-blue-50 text-blue-700 border-blue-200/50',

        // --- ALLERGENS & INTOLERANCES (Ambers / Oranges) ---
        'Gluten Free'  => 'bg-amber-50 text-amber-700 border-amber-200/50', // Gluten Free
        'Dairy Free'   => 'bg-sky-50 text-sky-700 border-sky-200/50',
        'Nuts'         => 'bg-orange-50 text-orange-700 border-orange-200/50',
        'Shellfish'    => 'bg-cyan-50 text-cyan-700 border-cyan-200/50',
        'Sugar Free'   => 'bg-zinc-100 text-zinc-700 border-zinc-200/50',
        'Keto'         => 'bg-purple-50 text-purple-700 border-purple-200/50',

        // --- HEAT & FLAVOR (Reds) ---
        'Mild'         => 'bg-yellow-50 text-yellow-700 border-yellow-200/50',
        'Spicy'        => 'bg-rose-50 text-rose-700 border-rose-200/50',
        'Extra Hot'    => 'bg-red-100 text-red-800 border-red-300/50',

        // --- PROMOTIONAL / STATUS (Brights) ---
        'New'          => 'bg-violet-100 text-violet-700 border-violet-200',
        'Popular'      => 'bg-pink-50 text-pink-700 border-pink-200/50',
        'Best Seller'  => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200/50',
        'Chef Choice'  => 'bg-slate-800 text-white border-slate-900', // Dark contrast for prestige
        'Limited'      => 'bg-black text-white border-black',

        // --- PREPARATION (Earth Tones) ---
        'Organic'      => 'bg-lime-50 text-lime-700 border-lime-200/50',
        'Alcohol'      => 'bg-stone-100 text-stone-700 border-stone-200/50',
        'Raw'          => 'bg-neutral-100 text-neutral-700 border-neutral-200/50',
    ];

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image',
        'is_available',
        'position',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array', // This automatically turns the JSON into a clean PHP array
    ];

    public function category()
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }
}
