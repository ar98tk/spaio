<?php

namespace Tests\Feature;

use App\Livewire\Panel\Bookings as PanelBookings;
use App\Models\Package as SpaPackage;
use App\Models\Booking;
use App\Models\Service;
use App\Models\StaffMember;
use App\Models\Tenant;
use App\Models\TenantWebsiteActionEvent;
use App\Models\User;
use App\Models\WebsiteTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;
use Tests\TestCase;

class TenantWebsiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_preview_renders_active_public_content(): void
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'phone' => '+201000000000',
            'whatsapp_number' => '+201111111111',
            'address' => '12 Wellness Street',
            'google_maps_url' => 'https://maps.google.com/?q=Calm+Spa',
            'logo_path' => 'logos/calm-spa.png',
            'instagram_url' => 'https://instagram.com/calmspa',
            'facebook_url' => 'https://facebook.com/calmspa',
            'snapchat_url' => 'https://snapchat.com/add/calmspa',
            'youtube_url' => 'https://youtube.com/@calmspa',
            'tiktok_url' => 'https://tiktok.com/@calmspa',
            'x_url' => 'https://x.com/calmspa',
            'hero_title' => 'Calm luxury treatments',
            'hero_subtitle' => 'A refined wellness escape in the city.',
            'hero_image_path' => 'heroes/calm-spa.jpg',
            'about_content' => 'Calm Spa is a private wellness studio.',
            'footer_content' => 'Book calm rituals every day.',
            'show_whatsapp_button' => true,
            'show_call_button' => false,
            'show_location_button' => true,
            'currency_code' => 'AED',
            'status' => 'active',
        ]);

        Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Signature Massage',
            'price' => 55,
            'description' => 'Relaxing massage.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Hidden Massage',
            'price' => 40,
            'description' => 'Not public.',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        SpaPackage::create([
            'tenant_id' => $tenant->id,
            'name' => 'Weekend Escape',
            'included_description' => 'Massage and sauna.',
            'original_price' => 120,
            'discounted_price' => 95,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        StaffMember::create([
            'tenant_id' => $tenant->id,
            'name' => 'Sara Ahmed',
            'role' => 'Therapist',
            'bio' => 'Senior specialist.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->get('/site/calm-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/FirstTemplate')
                ->where('tenant.business_name', 'Calm Spa')
                ->where('tenant.logo_path', 'logos/calm-spa.png')
                ->where('tenant.phone', '+201000000000')
                ->where('tenant.whatsapp_number', '+201111111111')
                ->where('tenant.address', '12 Wellness Street')
                ->where('tenant.google_maps_url', 'https://maps.google.com/?q=Calm+Spa')
                ->where('tenant.facebook_url', 'https://facebook.com/calmspa')
                ->where('tenant.instagram_url', 'https://instagram.com/calmspa')
                ->where('tenant.snapchat_url', 'https://snapchat.com/add/calmspa')
                ->where('tenant.youtube_url', 'https://youtube.com/@calmspa')
                ->where('tenant.tiktok_url', 'https://tiktok.com/@calmspa')
                ->where('tenant.x_url', 'https://x.com/calmspa')
                ->where('tenant.hero_title', 'Calm luxury treatments')
                ->where('tenant.hero_subtitle', 'A refined wellness escape in the city.')
                ->where('tenant.hero_image_path', 'heroes/calm-spa.jpg')
                ->where('tenant.about_content', 'Calm Spa is a private wellness studio.')
                ->where('tenant.footer_content', 'Book calm rituals every day.')
                ->where('tenant.show_call_button', false)
                ->where('tenant.show_whatsapp_button', true)
                ->where('tenant.show_location_button', true)
                ->where('tenant.currency_code', 'AED')
                ->where('booking_endpoint', route('tenant.preview.booking', $tenant))
                ->where('action_endpoint', route('tenant.preview.actions', $tenant))
                ->has('services', 1)
                ->where('services.0.name', 'Signature Massage')
                ->has('packages', 1)
                ->where('packages.0.name', 'Weekend Escape')
                ->has('staff', 1)
                ->where('staff.0.name', 'Sara Ahmed')
            );
    }

    public function test_inactive_tenant_preview_returns_not_found(): void
    {
        Tenant::create([
            'business_name' => 'Paused Spa',
            'slug' => 'paused-spa',
            'subdomain' => 'paused-spa',
            'status' => 'paused',
        ]);

        $this->get('/site/paused-spa')->assertNotFound();
    }

    public function test_tenant_preview_renders_selected_second_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Second Template',
            'slug' => 'second-template',
            'component' => 'Tenant/SecondTemplate',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/calm-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/SecondTemplate')
                ->where('template.slug', 'second-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_third_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Third Template',
            'slug' => 'third-template',
            'component' => 'Tenant/ThirdTemplate',
            'is_active' => true,
            'sort_order' => 3,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Clinical Spa',
            'slug' => 'clinical-spa',
            'subdomain' => 'clinical-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/clinical-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/ThirdTemplate')
                ->where('template.slug', 'third-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_fourth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Fourth Template',
            'slug' => 'fourth-template',
            'component' => 'Tenant/FourthTemplate',
            'is_active' => true,
            'sort_order' => 4,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Glow Spa',
            'slug' => 'glow-spa',
            'subdomain' => 'glow-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/glow-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/FourthTemplate')
                ->where('template.slug', 'fourth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_fifth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Fifth Template',
            'slug' => 'fifth-template',
            'component' => 'Tenant/FifthTemplate',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Harmony Spa',
            'slug' => 'harmony-spa',
            'subdomain' => 'harmony-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/harmony-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/FifthTemplate')
                ->where('template.slug', 'fifth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_sixth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Sixth Template',
            'slug' => 'sixth-template',
            'component' => 'Tenant/SixthTemplate',
            'is_active' => true,
            'sort_order' => 6,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Reluxe Spa',
            'slug' => 'reluxe-spa',
            'subdomain' => 'reluxe-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/reluxe-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/SixthTemplate')
                ->where('template.slug', 'sixth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_seventh_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Seventh Template',
            'slug' => 'seventh-template',
            'component' => 'Tenant/SeventhTemplate',
            'is_active' => true,
            'sort_order' => 7,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Serene Atelier',
            'slug' => 'serene-atelier',
            'subdomain' => 'serene-atelier',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/serene-atelier')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/SeventhTemplate')
                ->where('template.slug', 'seventh-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_eighth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Eighth Template',
            'slug' => 'eighth-template',
            'component' => 'Tenant/EighthTemplate',
            'is_active' => true,
            'sort_order' => 8,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Victoria Spa Resort',
            'slug' => 'victoria-spa-resort',
            'subdomain' => 'victoria-spa-resort',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/victoria-spa-resort')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/EighthTemplate')
                ->where('template.slug', 'eighth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_ninth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Ninth Template',
            'slug' => 'ninth-template',
            'component' => 'Tenant/NinthTemplate',
            'is_active' => true,
            'sort_order' => 9,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'BeautyZone Spa',
            'slug' => 'beautyzone-spa',
            'subdomain' => 'beautyzone-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/beautyzone-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/NinthTemplate')
                ->where('template.slug', 'ninth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_tenth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Tenth Template',
            'slug' => 'tenth-template',
            'component' => 'Tenant/TenthTemplate',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Blush Ritual Spa',
            'slug' => 'blush-ritual-spa',
            'subdomain' => 'blush-ritual-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/blush-ritual-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/TenthTemplate')
                ->where('template.slug', 'tenth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_eleventh_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Eleventh Template',
            'slug' => 'eleventh-template',
            'component' => 'Tenant/EleventhTemplate',
            'is_active' => true,
            'sort_order' => 11,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Paradise Spa Lounge',
            'slug' => 'paradise-spa-lounge',
            'subdomain' => 'paradise-spa-lounge',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/paradise-spa-lounge')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/EleventhTemplate')
                ->where('template.slug', 'eleventh-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_tenant_preview_renders_selected_twelfth_template(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Twelfth Template',
            'slug' => 'twelfth-template',
            'component' => 'Tenant/TwelfthTemplate',
            'is_active' => true,
            'sort_order' => 12,
        ]);

        $tenant = Tenant::create([
            'business_name' => 'Hairy Wellness Spa',
            'slug' => 'hairy-wellness-spa',
            'subdomain' => 'hairy-wellness-spa',
            'website_template_id' => $template->id,
            'status' => 'active',
        ]);

        $this->get('/site/hairy-wellness-spa')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Tenant/TwelfthTemplate')
                ->where('template.slug', 'twelfth-template')
                ->where('tenant.business_name', $tenant->business_name)
            );
    }

    public function test_customer_can_book_active_service_from_tenant_preview(): void
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'status' => 'active',
        ]);

        $service = Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Signature Massage',
            'price' => 220,
            'description' => 'Relaxing massage.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->from('/site/calm-spa')
            ->post('/site/calm-spa/book', [
                'item_type' => 'service',
                'item_id' => $service->id,
                'customer_name' => 'Mariam Hassan',
                'customer_phone' => '+971 50 888 4321',
                'customer_email' => 'mariam@example.ae',
                'preferred_date' => now()->addDay()->toDateString(),
                'preferred_time' => '18:30',
                'notes' => 'Please confirm by WhatsApp.',
            ])
            ->assertRedirect('/site/calm-spa')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('bookings', [
            'tenant_id' => $tenant->id,
            'service_id' => $service->id,
            'item_type' => 'service',
            'item_name' => 'Signature Massage',
            'customer_name' => 'Mariam Hassan',
            'status' => 'pending',
            'source' => 'website',
        ]);
    }

    public function test_customer_website_action_clicks_are_recorded_for_reports(): void
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'status' => 'active',
        ]);

        $this->post('/site/calm-spa/actions', [
            'action_type' => 'whatsapp',
            'context' => 'hero',
            'label' => 'WhatsApp booking',
            'target_url' => 'https://wa.me/201111111111',
        ])->assertNoContent();

        $this->assertDatabaseHas('tenant_website_action_events', [
            'tenant_id' => $tenant->id,
            'action_type' => 'whatsapp',
            'context' => 'hero',
            'label' => 'WhatsApp booking',
        ]);

        $this->post('/site/calm-spa/actions', [
            'action_type' => 'email',
        ])->assertSessionHasErrors('action_type');

        $this->assertSame(1, TenantWebsiteActionEvent::query()->count());
    }

    public function test_tenant_owner_can_view_and_update_booking_status(): void
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'status' => 'active',
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tenant Owner',
            'email' => 'owner@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'item_type' => 'service',
            'item_name' => 'Signature Massage',
            'item_price' => 220,
            'customer_name' => 'Mariam Hassan',
            'customer_phone' => '+971 50 888 4321',
            'status' => 'pending',
            'source' => 'website',
        ]);

        $this->actingAs($owner)
            ->get('/panel/bookings')
            ->assertOk()
            ->assertSee('Booking pipeline')
            ->assertSee('Mariam Hassan')
            ->assertSee('Pending');

        Livewire::actingAs($owner)
            ->test(PanelBookings::class)
            ->call('updateStatus', $booking->id, 'confirmed')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
        ]);
    }
}
