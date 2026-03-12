<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Laravel\Facades\Image;

class RestaurantSettingsController extends Controller
{
    public function edit(Restaurant $restaurant)
    {
        // Get gallery images from storage directory
        $galleryPath = "restaurants/{$restaurant->slug}/gallery";
        $galleryImages = [];
        
        if (Storage::disk('public')->exists($galleryPath)) {
            $files = Storage::disk('public')->files($galleryPath);
            $galleryImages = array_map(fn($file) => basename($file), $files);
        }
        
        $restaurant->gallery = $galleryImages;

        return view('restaurant.settings', compact('restaurant'));
    }
    public function updateBranding(Request $request, Restaurant $restaurant)
    {
        $level = $restaurant->subscription_level;

        // 1. Validation Logic
        $rules = [
            'hero' => 'nullable|image|max:5000',
            'gallery.*' => 'nullable|image|max:5000',
            'primary_color' => 'nullable|string|max:7',
            'font_family' => [
                'nullable',
                'string',
                Rule::in(['sans', 'serif', 'display', 'monsterrat', 'handwriting', 'rust']),
            ],
            'google_maps_link' => 'nullable|url',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',

            // IT is always allowed/required
            'name.it' => 'nullable|string|max:255',
            'description.it' => 'nullable|string',
            'meta_title.it' => 'nullable|string|max:255',
        ];

        // Conditionally add rules based on subscription level
        if ($level >= 1) {
            $rules['name.en'] = 'nullable|string|max:255';
            $rules['description.en'] = 'nullable|string';
            $rules['meta_title.en'] = 'nullable|string|max:255';
        }

        if ($level >= 2) {
            foreach (['fr', 'de'] as $lang) {
                $rules["name.$lang"] = 'nullable|string|max:255';
                $rules["description.$lang"] = 'nullable|string';
                $rules["meta_title.$lang"] = 'nullable|string|max:255';
            }
        }

        $validated = $request->validate($rules);

        // 2. Handle Image Upload
        if ($request->hasFile('hero')) {

            $file = $request->file('hero');

            // Cleanup: Delete old hero image if it exists to save disk space
            if ($restaurant->hero_image) {
                Storage::disk('public')->delete("restaurants/{$restaurant->slug}/{$restaurant->hero_image}");
            }

            // Use getClientOriginalExtension() for better reliability
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

            // Store file
            $path = $file->storeAs(
                "restaurants/{$restaurant->slug}",
                $filename,
                'public'
            );

            if ($path) {
                $restaurant->hero_image = $filename;
            }
        }

        // 2b. Handle Gallery Uploads
        if ($request->hasFile('gallery')) {
            $galleryPath = "restaurants/{$restaurant->slug}/gallery";
            
            // Create directory if it doesn't exist
            if (!Storage::disk('public')->exists($galleryPath)) {
                Storage::disk('public')->makeDirectory($galleryPath);
            }

            foreach ($request->file('gallery') as $file) {
                $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->storeAs($galleryPath, $filename, 'public');
            }
        }

        // 3. Security Filter: Only save what they paid for
        $allowedLangs = ['it'];
        if ($level >= 1) $allowedLangs[] = 'en';
        if ($level >= 2) {
            $allowedLangs[] = 'fr';
            $allowedLangs[] = 'de';
        }

        // Map inputs to the model
        $restaurant->name = array_intersect_key($request->input('name', []), array_flip($allowedLangs));
        $restaurant->description = array_intersect_key($request->input('description', []), array_flip($allowedLangs));
        $restaurant->meta_title = array_intersect_key($request->input('meta_title', []), array_flip($allowedLangs));

        // Non-translated fields
        $restaurant->primary_color = $request->input('primary_color');
        $restaurant->font_family = $request->input('font_family');
        $restaurant->google_maps_link = $request->input('google_maps_link');
        $restaurant->phone = $request->input('phone');
        $restaurant->email = $request->input('email');
        $restaurant->address = $request->input('address');

        $restaurant->save();

        return back()->with('tab', $request->input('current_tab'))->with('success', 'Website updated successfully!');
    }

    public function deleteGalleryImage(Request $request, Restaurant $restaurant)
    {
        $filename = $request->input('filename');
        
        $imagePath = "restaurants/{$restaurant->slug}/gallery/{$filename}";
        
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    }
}