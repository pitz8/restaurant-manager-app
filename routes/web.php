<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestaurantSettingsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/{restaurant:slug}', [DashboardController::class, 'slugIndex'])->name('slug.dashboard');

    Route::prefix('/dashboard/{restaurant:slug}')->group(function () {
        // Menu Reordering
        Route::post('/categories/reorder', [MenuController::class, 'reorderCategories'])->name('categories.reorder');
        Route::post('/restaurant/theme', [DashboardController::class, 'updateTheme'])->name('restaurant.update-theme');

        // Categories
        Route::post('/categories', [MenuController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/categories/{category}', [MenuController::class, 'destroyCategory'])->name('categories.destroy');

        // Items
        Route::post('/items', [MenuController::class, 'storeItem'])->name('items.store');
        Route::put('/items/{item}', [MenuController::class, 'updateItem'])->name('items.update');
        Route::delete('/items/{item}', [MenuController::class, 'destroyItem'])->name('items.destroy');
        Route::post('/items/{item}/toggle', [MenuController::class, 'toggleStatus'])->name('items.toggle');
        Route::post('/items/{item}/toggleAvailability', [MenuController::class, 'toggleAvailability'])->name('items.toggleAvailability');
        Route::post('/items/reorder', [MenuController::class, 'reorderItems'])->name('items.reorder');

        // Microsite & Branding Settings
        // We use a GET to show the page and a PUT to save the data
        Route::get('/website', [RestaurantSettingsController::class, 'edit'])->name('website.edit');
        Route::put('/website', [RestaurantSettingsController::class, 'updateBranding'])->name('website.update');
        Route::post('/gallery/delete', [RestaurantSettingsController::class, 'deleteGalleryImage'])->name('gallery.delete');

        // Menu Editor
        Route::get('/menu', [MenuController::class, 'edit'])->name('menu.edit');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// Public restaurant routes with optional language prefix
Route::prefix('{lang?}')->where(['lang' => 'it|en|fr|de'])->group(function () {
    Route::get('/r/{restaurant:slug}', [RestaurantController::class, 'show'])->name('restaurant.home');
    Route::get('/r/{restaurant:slug}/menu', [MenuController::class, 'show'])->name('restaurant.menu');
    Route::get('/r/{restaurant:slug}/qr', [RestaurantController::class, 'qr'])->name('restaurant.qr');
});
