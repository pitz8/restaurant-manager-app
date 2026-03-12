<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function show($lang = null, Restaurant $restaurant)
    {
        $menu = $restaurant->menus()->where('is_active', true)->with('categories.items')->first();

        if (!$menu) {
            abort(404, 'No active menu found for this restaurant.');
        }

        return view('restaurant.menu', compact('restaurant', 'menu'));
    }

    /**
     * ADMIN VIEW: Dashboard Menu Editor
     */
    public function edit(Restaurant $restaurant)
    {
        $menu = $restaurant->menus()->first();

        // If the menu isn't active, redirect them back with a message
        if (!$menu->is_active) {
            return redirect()->route('dashboard')->with('error', 'Please upgrade to unlock the editor.');
        }

        return view('dashboard.menu-editor', compact('restaurant', 'menu'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string']);

        // Get the owner's restaurant and first menu
        $menu = Auth::user()->restaurant->menus()->first();

        $menu->categories()->create([
            'name' => $request->name,
            'position' => $menu->categories()->count() + 1
        ]);

        return back()->with('success', 'Category created!');
    }

    public function storeItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:menu_categories,id',
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        MenuItem::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'is_available' => true,
        ]);

        return back()->with('success', 'Item added to menu!');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'tags' => 'nullable|array',
            'tags.*' => [Rule::in(array_keys(MenuItem::AVAILABLE_TAGS))],
        ]);

        $validated['tags'] = $request->input('tags', []);
        $item->update($validated);

        return back()->with('success', 'Item updated!');
    }

    public function reorderCategories(Request $request)
    {
        $ids = $request->ids; // Array of IDs in the new order

        foreach ($ids as $index => $id) {
            MenuCategory::where('id', $id)
                ->whereHas('menu', function ($query) {
                    // Check if the menu's restaurant belongs to the authenticated user
                    $query->whereIn('restaurant_id', Auth::user()->restaurants->pluck('id'));
                })
                ->update(['position' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }


    public function reorderItems(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $index => $id) {
            MenuItem::where('id', $id)->update(['position' => $index]);
        }
        return response()->json(['status' => 'success']);
    }

    public function showMenu(Request $request, Restaurant $restaurant)
    {
        // 1. Get the menu
        $menu = $restaurant->menus()->where('is_active', true)->with('categories.items')->first();

        // 2. IMPORTANT: If there is a preview parameter, manually override the theme property
        // This doesn't save to the DB, it just changes it for this specific page load.
        if ($request->has('preview_theme')) {
            $restaurant->theme = $request->query('preview_theme');
        }

        return view('restaurant.menu', compact('restaurant', 'menu'));
    }

    public function updateTags(Request $request, MenuItem $item)
    {
        // 1. Security check: Does the owner of this restaurant match the authenticated user?
        if ($item->category->menu->restaurant->user_id !== Auth::user()->id) {
            abort(403);
        }

        // 2. Simply grab the array from the request. 
        // If no tags are selected, it defaults to an empty array [].
        $item->tags = $request->input('tags', []);

        // 3. Laravel will automatically JSON-encode this array because of the $cast in your Model
        $item->save();

        return back()->with('success', 'Menu item tags updated!');
    }

    public function toggleAvailability(Request $request, MenuItem $item)
    {
        // 1. Security check: Does the owner of this restaurant match the authenticated user?
        if ($item->category->menu->restaurant->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Toggle availability
        $item->is_available = !$item->is_available;
        $item->save();

        return back()->with('success', 'Item availability updated!');
    }

    public function destroyItem(MenuItem $item)
    {
        // 1. Security check: Does the owner of this restaurant match the authenticated user?
        if ($item->category->menu->restaurant->user_id !== Auth::id()) {
            abort(403);
        }

        $item->delete();
        return back()->with('success', 'Item removed from menu.');
    }

    public function destroyCategory(MenuCategory $category)
    {
        // Security: Ensure owner owns the restaurant linked to this category
        if ($category->menu->restaurant->user_id !== Auth::id()) {
            abort(403);
        }

        // This will delete the category and (if configured in migration) its items
        $category->delete();

        return back()->with('success', 'Category and all its items removed.');
    }
}
