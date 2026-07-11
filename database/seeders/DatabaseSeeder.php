<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\Booking;
use App\Models\Package;
use App\Models\Plan;
use App\Models\Service;
use App\Models\StaffMember;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'For new spa businesses launching a professional website.',
                'monthly_price' => 9,
                'yearly_price' => 90,
                'features' => ['20 Services', '50 Gallery Images', '1 Template', 'Basic Analytics', 'Free Subdomain'],
                'is_featured' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'For growing spas that need full analytics and custom domains.',
                'monthly_price' => 19,
                'yearly_price' => 190,
                'features' => ['Unlimited Services', 'Unlimited Gallery', 'All Templates', 'Full Analytics', 'SEO Settings', 'Custom Domain'],
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'For premium spa brands that need scale, reports, and priority support.',
                'monthly_price' => 39,
                'yearly_price' => 390,
                'features' => ['Everything in Pro', 'Multi Language', 'Staff Accounts', 'Advanced Reports', 'Priority Support'],
                'is_featured' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }

        Plan::query()
            ->whereNotIn('slug', collect($plans)->pluck('slug'))
            ->doesntHave('subscriptions')
            ->delete();

        User::updateOrCreate(
            ['email' => env('SPAIO_ADMIN_EMAIL', 'admin@spaio.test')],
            [
                'name' => env('SPAIO_ADMIN_NAME', 'Spaio Admin'),
                'email_verified_at' => now(),
                'password' => Hash::make(env('SPAIO_ADMIN_PASSWORD', 'password')),
                'role' => 'super_admin',
                'tenant_id' => null,
            ]
        );

        $firstTemplate = WebsiteTemplate::updateOrCreate(
            ['slug' => 'first-template'],
            [
                'name' => 'First Template',
                'component' => 'Tenant/FirstTemplate',
                'description' => 'Dark luxury Arabic spa website with gold accents, offers, services, staff cards, and gallery.',
                'preview_image_path' => '/default.png',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'second-template'],
            [
                'name' => 'Second Template',
                'component' => 'Tenant/SecondTemplate',
                'description' => 'Elegant light spa website inspired by classic wellness templates, with soft green, cream, and editorial service cards.',
                'preview_image_path' => '/template-kits/second-template/images/section-bg/slider-1.jpg',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'third-template'],
            [
                'name' => 'Third Template',
                'component' => 'Tenant/ThirdTemplate',
                'description' => 'Clean clinical massage and wellness template with blue accents, rounded treatment visuals, bookings, offers, team, and gallery.',
                'preview_image_path' => '/template-kits/third-template/assets/images/bg/bg-image-1.jpg',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'fourth-template'],
            [
                'name' => 'Fourth Template',
                'component' => 'Tenant/FourthTemplate',
                'description' => 'Vibrant spa and beauty template with navy hero, purple and pink accents, promotional offers, services, pricing-style packages, team, and gallery.',
                'preview_image_path' => '/template-kits/fourth-template/img/slider/slider_bg.png',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'fifth-template'],
            [
                'name' => 'Fifth Template',
                'component' => 'Tenant/FifthTemplate',
                'description' => 'Soft editorial massage therapy template with centered logo navigation, coral and aqua accents, service blocks, pricing rows, staff, gallery, and booking.',
                'preview_image_path' => '/template-kits/fifth-template/images/1920x1100.jpg',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'sixth-template'],
            [
                'name' => 'Sixth Template',
                'component' => 'Tenant/SixthTemplate',
                'description' => 'Warm Reluxe-inspired beauty and spa template with editorial typography, rounded service cards, offers, team, gallery, and booking.',
                'preview_image_path' => '/template-kits/sixth-template/assets/images/bg/introBg1_1.jpg',
                'is_active' => true,
                'sort_order' => 6,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'seventh-template'],
            [
                'name' => 'Seventh Template',
                'component' => 'Tenant/SeventhTemplate',
                'description' => 'Editorial private wellness template with dark botanical hero, champagne accents, premium service cards, offers, team, gallery, and booking.',
                'preview_image_path' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=88',
                'is_active' => true,
                'sort_order' => 7,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'eighth-template'],
            [
                'name' => 'Eighth Template',
                'component' => 'Tenant/EighthTemplate',
                'description' => 'Green spa resort template adapted from the eighth kit with Playfair headings, nature-inspired hero, services, offers, team, gallery, and booking.',
                'preview_image_path' => '/template-kits/eighth-template/images/home-1/slider/slider-1.jpg',
                'is_active' => true,
                'sort_order' => 8,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'ninth-template'],
            [
                'name' => 'Ninth Template',
                'component' => 'Tenant/NinthTemplate',
                'description' => 'BeautyZone-inspired salon and spa template with pink topbar, dramatic beauty hero, circular services, product-style offers, team, gallery, contact actions, and booking.',
                'preview_image_path' => '/template-kits/ninth-template/images/main-slider/slide1.jpg',
                'is_active' => true,
                'sort_order' => 9,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'tenth-template'],
            [
                'name' => 'Tenth Template',
                'component' => 'Tenant/TenthTemplate',
                'description' => 'Pink and charcoal beauty spa template adapted from the tenth kit with polished hero, services, offers, team, gallery, contact actions, and booking.',
                'preview_image_path' => '/template-kits/tenth-template/images/main-slider/slider1/slide1.jpg',
                'is_active' => true,
                'sort_order' => 10,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'eleventh-template'],
            [
                'name' => 'Eleventh Template',
                'component' => 'Tenant/EleventhTemplate',
                'description' => 'Classic Paradise-inspired spa template with contact topbar, portrait hero, circular services, pricing rows, specialist strips, masonry gallery, and booking.',
                'preview_image_path' => '/template-kits/eleventh-template/images/contactus-img.jpg',
                'is_active' => true,
                'sort_order' => 11,
            ]
        );

        WebsiteTemplate::updateOrCreate(
            ['slug' => 'twelfth-template'],
            [
                'name' => 'Twelfth Template',
                'component' => 'Tenant/TwelfthTemplate',
                'description' => 'Hairy-inspired dark salon template with transparent header, full-screen hero, image service panels, working-hours band, pricing menu, staff grid, gallery, and booking.',
                'preview_image_path' => '/template-kits/twelfth-template/assets/images/sliders/slide-bg/1.jpg',
                'is_active' => true,
                'sort_order' => 12,
            ]
        );

        $tenant = Tenant::query()
            ->where('subdomain', 'spaio-demo')
            ->orWhere('subdomain', 'laveira-demo')
            ->first() ?? new Tenant();

        $tenant->fill([
                'business_name' => 'Spaio Demo Spa',
                'slug' => 'spaio-demo',
                'subdomain' => 'spaio-demo',
                'phone' => '+971 4 555 0198',
                'whatsapp_number' => '+971 50 555 0198',
                'address' => 'Marina Gate, Dubai Marina, Dubai, United Arab Emirates',
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=Dubai+Marina+Spa',
                'facebook_url' => 'https://www.facebook.com/spaiodubai',
                'instagram_url' => 'https://www.instagram.com/spaio.dubai',
                'tiktok_url' => 'https://www.tiktok.com/@spaio.dubai',
                'x_url' => 'https://x.com/spaiodubai',
                'hero_title' => 'A Royal Dubai Spa Ritual Crafted for Modern Wellness',
                'hero_subtitle' => 'Golden serenity, expert therapists, and deeply restorative treatments in a private Dubai Marina wellness sanctuary.',
                'about_content' => 'Spaio Dubai is a premium wellness destination for massage, hammam rituals, beauty care, and curated spa packages in the heart of Dubai Marina.',
                'footer_content' => 'An intimate Dubai Marina sanctuary for golden rituals, soft stillness, and premium Arabic spa hospitality.',
                'show_whatsapp_button' => true,
                'show_call_button' => true,
                'show_location_button' => true,
                'website_template_id' => $firstTemplate->id,
                'currency_code' => 'AED',
                'status' => 'active',
            ]);
        $tenant->save();

        $owner = User::updateOrCreate(
            ['email' => 'owner@spaio.test'],
            [
                'tenant_id' => $tenant->id,
                'name' => 'Spaio Demo Owner',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        $tenant->update(['owner_user_id' => $owner->id]);

        $pro = Plan::query()->where('slug', 'pro')->first();

        if ($pro) {
            Subscription::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'plan_id' => $pro->id,
                    'status' => 'trialing',
                    'billing_cycle' => 'monthly',
                    'trial_ends_at' => now()->addDays(10),
                    'current_period_ends_at' => now()->addDays(10),
                ]
            );
        }

        $imagePaths = [
            'hero' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=2400&q=90',
            'service_oil' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&w=1200&q=88',
            'service_hammam' => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1200&q=88',
            'service_facial' => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1200&q=88',
            'service_nails' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=1200&q=88',
            'service_scrub' => 'https://images.unsplash.com/photo-1596178060671-7a80dc8059ea?auto=format&fit=crop&w=1200&q=88',
            'service_couples' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1200&q=88',
            'package_glow' => 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1400&q=88',
            'package_harmony' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1400&q=88',
            'package_jacuzzi' => 'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?auto=format&fit=crop&w=1400&q=88',
            'staff1' => 'https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=900&q=88',
            'staff2' => 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?auto=format&fit=crop&w=900&q=88',
            'staff3' => 'https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=900&q=88',
            'staff4' => 'https://images.unsplash.com/photo-1548142813-c348350df52b?auto=format&fit=crop&w=900&q=88',
            'gallery1' => 'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&w=1400&q=88',
            'gallery2' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?auto=format&fit=crop&w=1400&q=88',
            'gallery3' => 'https://images.unsplash.com/photo-1519824145371-296894a0daa9?auto=format&fit=crop&w=1400&q=88',
            'gallery4' => 'https://images.unsplash.com/photo-1552693673-1bf958298935?auto=format&fit=crop&w=1400&q=88',
            'gallery5' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=1400&q=88',
            'gallery6' => 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&w=1400&q=88',
        ];

        $tenant->update(['hero_image_path' => $imagePaths['hero']]);

        $services = [
            ['Natural Oil Massage', 350, 'A calming full body massage using pure oils for deep comfort and softness.', $imagePaths['service_oil']],
            ['Moroccan Bath', 500, 'A royal Moroccan hammam with black soap, exfoliation, steam, and a silky finish.', $imagePaths['service_hammam']],
            ['Rose Gold Glow Facial', 420, 'A luminous facial experience for hydrated, polished, camera-ready skin.', $imagePaths['service_facial']],
            ['Manicure & Pedicure', 220, 'Detailed hand and foot grooming with a clean, polished luxury spa finish.', $imagePaths['service_nails']],
            ['Desert Pearl Body Scrub', 380, 'A full body exfoliation ritual with mineral polish, warm towels, and soft hydration.', $imagePaths['service_scrub']],
            ['Couples Serenity Massage', 720, 'A private dual-room relaxation experience for two guests with calming aromatic oils.', $imagePaths['service_couples']],
        ];

        foreach ($services as $index => [$name, $price, $description, $image]) {
            Service::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $name],
                [
                    'price' => $price,
                    'description' => $description,
                    'image_path' => $image,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        Service::query()
            ->where('tenant_id', $tenant->id)
            ->whereNotIn('name', collect($services)->pluck(0))
            ->delete();

        $packages = [
            ['Velvet Glow Treatment', "60-minute full body massage\nRelaxing aromatherapy experience\nFace and hair mask", 450, 350, $imagePaths['package_glow']],
            ['Royal Harmony Experience', "60-minute massage\nMoroccan Royal Bath or Turkish Bath\nOpen access to steam room and sauna", 850, 700, $imagePaths['package_harmony']],
            ['Sultan Jacuzzi Ritual', "Luxury oil massage\n20-minute mineral salt jacuzzi\nSteam room access", 950, 800, $imagePaths['package_jacuzzi']],
        ];

        foreach ($packages as $index => [$name, $included, $original, $discounted, $image]) {
            Package::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $name],
                [
                    'included_description' => $included,
                    'original_price' => $original,
                    'discounted_price' => $discounted,
                    'image_path' => $image,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $staff = [
            ['Sophia', 'Aromatherapy Specialist', 'Expert in slow relaxation rituals and premium oil treatments.', $imagePaths['staff1']],
            ['Maya', 'Moroccan Bath Expert', 'Specialist in hammam, steam rituals, and traditional exfoliation.', $imagePaths['staff2']],
            ['Noura', 'Facial Therapist', 'Focused on glow facials, hydration, and skin renewal.', $imagePaths['staff3']],
            ['Anika', 'Deep Relaxation Therapist', 'Experienced therapist for deep comfort and body balance.', $imagePaths['staff4']],
        ];

        foreach ($staff as $index => [$name, $role, $bio, $photo]) {
            StaffMember::updateOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $name],
                [
                    'role' => $role,
                    'bio' => $bio,
                    'photo_path' => $photo,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }

        $gallery = [
            ['Private treatment suite', 'Warm luxury spa treatment room with soft lighting', $imagePaths['gallery1']],
            ['Massage ritual room', 'Massage room prepared with towels and natural spa oils', $imagePaths['gallery2']],
            ['Aromatherapy detail', 'Aromatherapy oils, candles, and calm spa details', $imagePaths['gallery3']],
            ['Hammam atmosphere', 'Elegant hammam and steam room ambience', $imagePaths['gallery4']],
            ['Relaxation lounge', 'Premium wellness lounge and quiet guest experience', $imagePaths['gallery5']],
            ['Golden spa ritual', 'Soft candles and warm luxury treatment details', $imagePaths['gallery6']],
        ];

        foreach ($gallery as $index => [$title, $alt, $path]) {
            GalleryImage::updateOrCreate(
                ['tenant_id' => $tenant->id, 'image_path' => $path],
                [
                    'title' => $title,
                    'alt_text' => $alt,
                    'sort_order' => $index + 1,
                ]
            );
        }

        GalleryImage::query()
            ->where('tenant_id', $tenant->id)
            ->whereNotIn('image_path', collect($gallery)->pluck(2))
            ->delete();

        $demoBookingPhones = ['+971 55 222 1044', '+971 50 888 4321'];

        Booking::query()
            ->where('tenant_id', $tenant->id)
            ->whereNotIn('customer_phone', $demoBookingPhones)
            ->delete();

        $oilMassage = Service::query()->where('tenant_id', $tenant->id)->where('name', 'Natural Oil Massage')->first();
        $harmony = Package::query()->where('tenant_id', $tenant->id)->where('name', 'Royal Harmony Experience')->first();

        if ($oilMassage) {
            Booking::updateOrCreate(
                ['tenant_id' => $tenant->id, 'customer_phone' => '+971 55 222 1044', 'item_name' => $oilMassage->name],
                [
                    'service_id' => $oilMassage->id,
                    'package_id' => null,
                    'item_type' => 'service',
                    'item_price' => $oilMassage->price,
                    'customer_name' => 'Aisha Al Mansoori',
                    'customer_email' => 'aisha@example.ae',
                    'preferred_date' => now()->addDays(1)->toDateString(),
                    'preferred_time' => '17:30',
                    'notes' => 'Prefers a quiet room and light pressure.',
                    'status' => 'pending',
                    'source' => 'website',
                ]
            );
        }

        if ($harmony) {
            Booking::updateOrCreate(
                ['tenant_id' => $tenant->id, 'customer_phone' => '+971 50 888 4321', 'item_name' => $harmony->name],
                [
                    'service_id' => null,
                    'package_id' => $harmony->id,
                    'item_type' => 'package',
                    'item_price' => $harmony->discounted_price,
                    'customer_name' => 'Mariam Hassan',
                    'customer_email' => 'mariam@example.ae',
                    'preferred_date' => now()->addDays(3)->toDateString(),
                    'preferred_time' => '19:00',
                    'notes' => 'Booking for two guests.',
                    'status' => 'confirmed',
                    'source' => 'website',
                ]
            );
        }
    }
}
