<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('logo_path');
            $table->string('whatsapp_number')->nullable()->after('phone');
            $table->string('address')->nullable()->after('whatsapp_number');
            $table->string('google_maps_url')->nullable()->after('address');
            $table->string('facebook_url')->nullable()->after('google_maps_url');
            $table->string('instagram_url')->nullable()->after('facebook_url');
            $table->string('tiktok_url')->nullable()->after('instagram_url');
            $table->string('x_url')->nullable()->after('tiktok_url');
            $table->string('hero_title')->nullable()->after('x_url');
            $table->string('hero_subtitle')->nullable()->after('hero_title');
            $table->string('hero_image_path')->nullable()->after('hero_subtitle');
            $table->text('about_content')->nullable()->after('hero_image_path');
            $table->text('footer_content')->nullable()->after('about_content');
            $table->boolean('show_whatsapp_button')->default(true)->after('footer_content');
            $table->boolean('show_call_button')->default(true)->after('show_whatsapp_button');
            $table->boolean('show_location_button')->default(true)->after('show_call_button');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description');
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('included_description');
            $table->decimal('original_price', 10, 2);
            $table->decimal('discounted_price', 10, 2);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['tenant_id', 'is_active']);
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('title')->nullable();
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
        Schema::dropIfExists('staff_members');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('services');

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'whatsapp_number',
                'address',
                'google_maps_url',
                'facebook_url',
                'instagram_url',
                'tiktok_url',
                'x_url',
                'hero_title',
                'hero_subtitle',
                'hero_image_path',
                'about_content',
                'footer_content',
                'show_whatsapp_button',
                'show_call_button',
                'show_location_button',
            ]);
        });
    }
};
