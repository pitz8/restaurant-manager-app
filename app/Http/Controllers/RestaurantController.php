<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Storage;

class RestaurantController extends Controller
{
    public function show($lang = null, Restaurant $restaurant)
    {
        // If you followed the Middleware step earlier, the language 
        // is already set. If not, you can set it here manually:
        // app()->setLocale($lang ?? 'it');

        $galleryImages = [];

        if ($restaurant->subscription_level > 1) {
            $path = "restaurants/{$restaurant->slug}/gallery";

            if (Storage::disk('public')->exists($path)) {
                $galleryImages = Storage::disk('public')->files($path);
            }
        }

        return view('restaurant.home', compact('restaurant', 'galleryImages'));
    }

    public function showMenu(Restaurant $restaurant)
    {
        // Refresh the model to make sure we have the latest theme from the DB
        $restaurant->refresh();

        $menu = $restaurant->menus()
            ->where('is_active', true)
            ->with('categories.items')
            ->first();

        return view('restaurant.menu', compact('restaurant', 'menu'));
    }

    /**
     * Return a QR code pointing to the restaurant menu.
     */
    public function qr(Restaurant $restaurant)
    {
        $url = url("/r/{$restaurant->slug}/menu");
        $svg = QrCode::size(300)->generate($url);

        return response($svg, 200)->header('Content-Type', 'image/svg+xml');
    }
}
