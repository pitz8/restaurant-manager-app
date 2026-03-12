<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('dashboard.index');
    }

    public function slugIndex(Restaurant $restaurant)
    {
        $menu = $restaurant->menus()->first();

        // Stats for the dashboard cards
        $stats = [
            'categories_count' => $menu ? $menu->categories()->count() : 0,
            'items_count' => $menu ? $menu->items()->count() : 0,
            'is_active' => $menu ? (bool)$menu->is_active : false,
        ];

        return view('dashboard.slug.index', compact('restaurant', 'menu', 'stats'));
    }

    public function toggleItem(MenuItem $item)
    {
        $user = Auth::user();

        // Check if the restaurant's user_id matches the current user's ID
        if ($item->category->menu->restaurant->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Flip the status
        $item->update([
            'is_available' => !$item->is_available
        ]);

        return response()->json([
            'success' => true,
            'is_available' => $item->is_available
        ]);
    }

    public function updateTheme(Request $request, Restaurant $restaurant)
    {
        // 1. Check if the data is arriving
        // dd($request->all()); 

        $request->validate([
            'theme' => 'required|in:modern,elegant,retro,paper,midnight,botanical,neon,bistro'
        ]);

        // 2. Perform the update
        $restaurant->update([
            'theme' => $request->theme
        ]);

        // 3. Optional: Force a save check
        // $restaurant->theme = $request->theme;
        // $restaurant->save();

        return back()->with('success', "Theme updated!");
    }
}
