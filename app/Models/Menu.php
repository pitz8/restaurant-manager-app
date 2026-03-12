<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Menu extends Model
{
    public function categories(): HasMany
    {
        return $this->hasMany(MenuCategory::class);
    }

    /**
     * Get all items for the menu through categories.
     */
    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(
            MenuItem::class,
            MenuCategory::class,
            'menu_id',     // Foreign key on MenuCategory table
            'category_id', // Foreign key on MenuItem table
            'id',          // Local key on Menu table
            'id'           // Local key on MenuCategory table
        );
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
