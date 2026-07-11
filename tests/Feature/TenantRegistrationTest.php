<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteTemplate;
use App\Notifications\VerifySpaioEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TenantRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_open_registration_creates_tenant_owner_and_subscription(): void
    {
        Notification::fake();

        Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Starter plan',
            'monthly_price' => 29,
            'yearly_price' => 290,
            'features' => ['Dashboard'],
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        $response = $this->post('/register', [
            'business_name' => 'Calm Water Spa',
            'name' => 'Maya Owner',
            'email' => 'maya@example.test',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/panel');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('tenants', [
            'business_name' => 'Calm Water Spa',
            'subdomain' => 'calm-water-spa',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'maya@example.test',
            'role' => 'owner',
        ]);

        $tenant = Tenant::first();
        $user = User::where('email', 'maya@example.test')->first();

        $this->assertSame($tenant->id, $user->tenant_id);
        $this->assertSame($user->id, $tenant->owner_user_id);
        $this->assertSame(1, Subscription::query()->where('tenant_id', $tenant->id)->count());
        $this->assertNull($user->email_verified_at);
        Notification::assertSentTo($user, VerifySpaioEmail::class);

        $this->get('/panel')->assertRedirect(route('verification.notice'));
    }

    public function test_registration_stores_optional_onboarding_fields(): void
    {
        $template = WebsiteTemplate::create([
            'name' => 'Luxury Template',
            'slug' => 'luxury-template',
            'component' => 'Tenant/FirstTemplate',
            'description' => 'Luxury preview',
            'preview_image_path' => '/default.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->post('/register', [
            'business_name' => 'Pearl Wellness Spa',
            'name' => 'Noura Owner',
            'email' => 'noura@example.test',
            'phone' => '+971 4 555 0000',
            'whatsapp_number' => '+971 50 555 0000',
            'address' => 'Dubai Marina, Dubai',
            'google_maps_url' => 'https://maps.google.com/?q=Pearl+Wellness+Spa',
            'facebook_url' => 'https://facebook.com/pearlspa',
            'instagram_url' => 'https://instagram.com/pearlspa',
            'snapchat_url' => 'https://snapchat.com/add/pearlspa',
            'youtube_url' => 'https://youtube.com/@pearlspa',
            'x_url' => 'https://x.com/pearlspa',
            'tiktok_url' => 'https://tiktok.com/@pearlspa',
            'website_template_id' => $template->id,
            'currency_code' => 'AED',
            'show_whatsapp_button' => true,
            'show_call_button' => false,
            'show_location_button' => true,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/panel');

        $this->assertDatabaseHas('tenants', [
            'business_name' => 'Pearl Wellness Spa',
            'phone' => '+971 4 555 0000',
            'whatsapp_number' => '+971 50 555 0000',
            'address' => 'Dubai Marina, Dubai',
            'snapchat_url' => 'https://snapchat.com/add/pearlspa',
            'youtube_url' => 'https://youtube.com/@pearlspa',
            'website_template_id' => $template->id,
            'currency_code' => 'AED',
            'show_whatsapp_button' => true,
            'show_call_button' => false,
            'show_location_button' => true,
        ]);
    }
}
