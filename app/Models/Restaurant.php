<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'theme',
        'phone',
        'email',
        'address',
        'google_maps_link',
        'hero_image',
        'primary_color',
        'font_family',
        'meta_title',
        'meta_description',
        'subscription_level',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'meta_title' => 'array',
        'meta_description' => 'array',
        'subscription_level' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($restaurant) {
            if (empty($restaurant->slug)) {
                $restaurant->slug = Str::slug($restaurant->name[app()->getLocale()] ?? $restaurant->name['it']);
            }
        });
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * SEO Optimized Hero URL
     */
    public function getHeroUrl()
    {
        if (!$this->hero_image) return asset('images/placeholder-hero.webp');
        return asset("storage/restaurants/{$this->slug}/{$this->hero_image}") . '?t=' . time();
    }

    /**
     * Dynamic SEO Alt Tag
     */
    public function getHeroAlt(): string
    {
        return ($this->name) . " - " . $this->description;
    }

    /**
     * Helper to handle the translation logic for all JSON fields
     */
    protected function getTranslatedValue($value)
    {
        // If $casts is working, $value is already an array. 
        // If not, we decode it.
        $translations = is_array($value) ? $value : json_decode($value, true);

        if (!$translations) return '';

        return $translations[App::getLocale()]
            ?? $translations['it'] // Primary fallback
            ?? array_values($translations)[0] // If 'it' is missing, get the first available lang
            ?? '';
    }

    /** Accessors **/

    public function getNameAttribute($value)
    {
        return $this->getTranslatedValue($value);
    }

    public function getDescriptionAttribute($value)
    {
        return $this->getTranslatedValue($value);
    }

    public function getMetaTitleAttribute($value)
    {
        return $this->getTranslatedValue($value);
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $this->getTranslatedValue($value);
    }
}
