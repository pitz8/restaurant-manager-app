<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create the User first
        $pietro = User::factory()->create([
            'name' => 'Pietro Mambelli',
            'email' => 'admin@pietro.com',
            'password' => bcrypt('password'), // Always good to have a known password
        ]);

        // 2. Data for the two restaurants, both linked to $pietro->id
        $restaurantsData = [
            [
                'name' => ['it' => 'Pizzeria Roma', 'en' => 'Roma Pizzeria'],
                'slug' => 'pizzeria-roma',
                'description' => [
                    'it' => 'Autentiche pizze cotte nel forno a legna.',
                    'en' => 'Authentic wood-fired pizzas.'
                ],
                'email' => 'admin@pizzeriaroma.com',
                'subscription_level' => 2, // Subscription Tier 2
                'theme' => 'paper',
            ],
            [
                'name' => ['it' => 'Trattoria da Mario', 'en' => 'Marios Trattoria'],
                'slug' => 'trattoria-da-mario',
                'description' => [
                    'it' => 'Cucina casalinga tradizionale.',
                    'en' => 'Traditional home-cooked meals.'
                ],
                'email' => 'hello@mario.com',
                'subscription_level' => 0, // Free Tier
                'theme' => 'modern',
            ]
        ];

        foreach ($restaurantsData as $data) {
            // 3. Create Restaurant linked to Pietro
            $restaurant = Restaurant::create([
                'user_id' => $pietro->id,
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'email' => $data['email'],
                'phone' => '+123456789',
                'address' => '123 Food Street, Italy',
                'google_maps_link' => 'http://maps.google.com/0',
                'theme' => $data['theme'],
                'subscription_level' => $data['subscription_level'],
                'meta_title' => [
                    'it' => $data['name']['it'] . ' | Il Meglio in Città',
                    'en' => $data['name']['en'] . ' | Best in Town',
                ],
                'meta_description' => [
                    'it' => 'Vieni a trovarci per un pasto indimenticabile.',
                    'en' => 'Visit us for an unforgettable meal.',
                ],
            ]);

            // 4. Create Menu & Categories
            $menu = Menu::create([
                'restaurant_id' => $restaurant->id,
                'name' => 'Main Menu',
                'is_active' => true,
            ]);

            $starters = MenuCategory::create(['menu_id' => $menu->id, 'name' => 'Starters', 'position' => 1]);
            $pizzas = MenuCategory::create(['menu_id' => $menu->id, 'name' => 'Signature Pizzas', 'position' => 2]);
            $desserts = MenuCategory::create(['menu_id' => $menu->id, 'name' => 'Desserts', 'position' => 3]);
            $drinks = MenuCategory::create(['menu_id' => $menu->id, 'name' => 'Drinks', 'position' => 4]);

            // 5. Create Menu Items
            MenuItem::create([
                'category_id' => $starters->id,
                'name' => 'Bruschetta Classica',
                'description' => 'Toasted sourdough with tomatoes and garlic.',
                'price' => 8.50,
                'tags' => ['Vegan'],
                'is_available' => true,
            ]);

            MenuItem::create([
                'category_id' => $pizzas->id,
                'name' => 'The Devils Pizza',
                'description' => 'Spicy Nduja, hot salami, and chili flakes.',
                'price' => 16.50,
                'tags' => ['Spicy'],
                'is_available' => true,
            ]);

            // ... you can add more items here if needed ...
        }
    }
}
