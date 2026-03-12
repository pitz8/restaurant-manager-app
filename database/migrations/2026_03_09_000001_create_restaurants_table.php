<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Change these two to JSON
            $table->json('name');
            $table->json('description')->nullable();

            $table->string('slug')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('theme')->default('modern');
            $table->string('google_maps_link')->nullable();

            // Socials
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();

            // Branding & SEO
            $table->string('hero_image')->nullable();
            $table->string('logo')->nullable();
            $table->string('primary_color')->default('#4f46e5');
            $table->string('font_family')->default('sans');

            // Premium Features
            $table->integer('subscription_level')->default(0);

            // SEO Specifics (These are already correct)
            $table->json('meta_title')->nullable();
            $table->json('meta_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
