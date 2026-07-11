<?php

namespace Tests\Feature;

use App\Livewire\Panel\Services as PanelServices;
use App\Livewire\Panel\Packages as PanelPackages;
use App\Livewire\Panel\Staff as PanelStaff;
use App\Livewire\Panel\Gallery as PanelGallery;
use App\Livewire\Panel\Templates as PanelTemplates;
use App\Livewire\Panel\Users as PanelUsers;
use App\Livewire\Panel\Reception as PanelReception;
use App\Livewire\Panel\Bookings as PanelBookings;
use App\Livewire\Panel\Statistics as PanelStatistics;
use App\Livewire\Panel\Settings as PanelSettings;
use App\Livewire\Panel\Billing as PanelBilling;
use App\Models\Booking;
use App\Models\GalleryImage;
use App\Models\Package as SpaPackage;
use App\Models\Plan;
use App\Models\Service;
use App\Models\StaffMember;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantWebsiteActionEvent;
use App\Models\User;
use App\Models\WebsiteTemplate;
use App\Notifications\SubscriptionInvoiceNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PanelDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_owner_can_render_panel_pages(): void
    {
        [, $owner] = $this->tenantWithOwner();

        $this->actingAs($owner)
            ->get('/panel')
            ->assertOk()
            ->assertSee('Dashboard overview')
            ->assertSee('Tenant workspace');

        $this->actingAs($owner)
            ->get('/panel/services')
            ->assertOk()
            ->assertSee('Services menu')
            ->assertSee('Service list');

        $this->actingAs($owner)
            ->get('/panel/packages')
            ->assertOk()
            ->assertSee('Spa packages')
            ->assertSee('Package list');

        $this->actingAs($owner)
            ->get('/panel/staff')
            ->assertOk()
            ->assertSee('Staff profiles')
            ->assertSee('Team list');

        $this->actingAs($owner)
            ->get('/panel/gallery')
            ->assertOk()
            ->assertSee('Gallery images')
            ->assertSee('No gallery images yet');

        WebsiteTemplate::create([
            'name' => 'Full Screenshot Template',
            'slug' => 'full-screenshot-template',
            'component' => 'Tenant/FirstTemplate',
            'description' => 'Template with full page screenshot.',
            'preview_image_path' => '/default.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($owner)
            ->get('/panel/templates')
            ->assertOk()
            ->assertSee('Choose the public website look')
            ->assertSee('Full Screenshot Template');

        $this->actingAs($owner)
            ->get('/panel/users')
            ->assertOk()
            ->assertSee('Dashboard users')
            ->assertSee('User accounts');

        $this->actingAs($owner)
            ->get('/panel/reception')
            ->assertOk()
            ->assertSee('Front-desk booking desk')
            ->assertSee('Create booking');

        $this->actingAs($owner)
            ->get('/panel/bookings')
            ->assertOk()
            ->assertSee('Booking pipeline')
            ->assertSee('Incoming bookings');

        $this->actingAs($owner)
            ->get('/panel/statistics')
            ->assertOk()
            ->assertSee('Monthly performance')
            ->assertSee('Website action report');

        $this->actingAs($owner)
            ->get('/panel/settings')
            ->assertOk()
            ->assertSee('Business settings')
            ->assertSee('Business identity');

        $this->actingAs($owner)
            ->get('/panel/billing')
            ->assertOk()
            ->assertSee('Billing documents')
            ->assertSee('Choose the right plan');
    }

    public function test_packages_panel_can_create_package_with_image_upload(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelPackages::class)
            ->set('name', 'Couples Escape')
            ->set('included_description', "Massage\nSteam room\nTea service")
            ->set('original_price', '300')
            ->set('discounted_price', '220')
            ->set('sort_order', 2)
            ->set('image', UploadedFile::fake()->image('package.jpg', 1000, 700)->size(600))
            ->call('save')
            ->assertHasNoErrors();

        $package = SpaPackage::firstOrFail();

        $this->assertSame($tenant->id, $package->tenant_id);
        $this->assertSame('Couples Escape', $package->name);
        $this->assertTrue($package->is_active);
        $this->assertNotNull($package->image_path);
        Storage::disk('public_uploads')->assertExists($package->image_path);
    }

    public function test_services_panel_can_create_service_with_image_upload(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelServices::class)
            ->set('name', 'Signature Massage')
            ->set('price', '150')
            ->set('description', 'A complete relaxation treatment.')
            ->set('sort_order', 3)
            ->set('image', UploadedFile::fake()->image('signature.jpg', 1000, 700)->size(600))
            ->call('save')
            ->assertHasNoErrors();

        $service = Service::firstOrFail();

        $this->assertSame($tenant->id, $service->tenant_id);
        $this->assertSame('Signature Massage', $service->name);
        $this->assertTrue($service->is_active);
        $this->assertNotNull($service->image_path);
        Storage::disk('public_uploads')->assertExists($service->image_path);
    }

    public function test_staff_panel_can_create_staff_member_with_photo_upload(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelStaff::class)
            ->set('name', 'Noura')
            ->set('role', 'Facial Therapist')
            ->set('bio', 'Focused on glow facials and hydration.')
            ->set('sort_order', 1)
            ->set('photo', UploadedFile::fake()->image('noura.jpg', 900, 900)->size(600))
            ->call('save')
            ->assertHasNoErrors();

        $staffMember = StaffMember::firstOrFail();

        $this->assertSame($tenant->id, $staffMember->tenant_id);
        $this->assertSame('Noura', $staffMember->name);
        $this->assertSame('Facial Therapist', $staffMember->role);
        $this->assertTrue($staffMember->is_active);
        $this->assertNotNull($staffMember->photo_path);
        Storage::disk('public_uploads')->assertExists($staffMember->photo_path);
    }

    public function test_gallery_panel_can_upload_image(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelGallery::class)
            ->set('title', 'Treatment Room')
            ->set('alt_text', 'Warm treatment room with candles')
            ->set('sort_order', 4)
            ->set('image', UploadedFile::fake()->image('room.jpg', 1200, 800)->size(600))
            ->call('save')
            ->assertHasNoErrors();

        $image = GalleryImage::firstOrFail();

        $this->assertSame($tenant->id, $image->tenant_id);
        $this->assertSame('Treatment Room', $image->title);
        $this->assertSame('Warm treatment room with candles', $image->alt_text);
        Storage::disk('public_uploads')->assertExists($image->image_path);
    }

    public function test_templates_panel_can_switch_to_active_template(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        $template = WebsiteTemplate::create([
            'name' => 'Screenshot Template',
            'slug' => 'screenshot-template',
            'component' => 'Tenant/FirstTemplate',
            'description' => 'Large full website screenshot.',
            'preview_image_path' => '/default.png',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($owner)
            ->test(PanelTemplates::class)
            ->call('confirmSwitch', $template->id)
            ->call('switchTemplate')
            ->assertHasNoErrors();

        $this->assertSame($template->id, $tenant->fresh()->website_template_id);
    }

    public function test_users_panel_can_create_update_and_delete_dashboard_user(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->set('name', 'Manager User')
            ->set('email', 'manager@example.test')
            ->set('role', 'manager')
            ->set('permissions', ['services.create', 'services.update'])
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('save')
            ->assertHasNoErrors();

        $manager = User::where('email', 'manager@example.test')->firstOrFail();

        $this->assertSame($tenant->id, $manager->tenant_id);
        $this->assertSame('manager', $manager->role);
        $this->assertSame(['services.create', 'services.update'], $manager->permissions);
        $this->assertNotNull($manager->email_verified_at);

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->call('edit', $manager->id)
            ->set('name', 'Reception User')
            ->set('email', 'reception@example.test')
            ->set('role', 'receptionist')
            ->set('permissions', ['services.create'])
            ->call('save')
            ->assertHasNoErrors();

        $manager->refresh();
        $this->assertSame('Reception User', $manager->name);
        $this->assertSame('receptionist', $manager->role);
        $this->assertSame([], $manager->permissions);

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->call('confirmDelete', $manager->id)
            ->call('deleteConfirmed')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('users', ['id' => $manager->id]);
    }

    public function test_reception_panel_can_create_service_booking(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        $service = Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Aromatherapy Massage',
            'price' => 250,
            'description' => 'Relaxing massage.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Livewire::actingAs($owner)
            ->test(PanelReception::class)
            ->set('selected_item', 'service:'.$service->id)
            ->set('customer_name', 'Lina Customer')
            ->set('customer_phone', '+971 50 333 2222')
            ->set('customer_email', 'lina@example.test')
            ->set('preferred_date', now()->toDateString())
            ->set('preferred_time', '15:30')
            ->set('notes', 'Walk-in request from front desk.')
            ->set('status', 'confirmed')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('bookings', [
            'tenant_id' => $tenant->id,
            'service_id' => $service->id,
            'item_type' => 'service',
            'item_name' => 'Aromatherapy Massage',
            'customer_name' => 'Lina Customer',
            'status' => 'confirmed',
            'source' => 'reception',
        ]);
    }

    public function test_reception_panel_can_create_custom_booking(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelReception::class)
            ->set('selected_item', 'custom')
            ->set('item_name', 'Walk-in consultation')
            ->set('item_price', '75')
            ->set('customer_name', 'Walk In')
            ->set('customer_phone', '+971 50 111 0000')
            ->set('status', 'completed')
            ->call('save')
            ->assertHasNoErrors();

        $booking = Booking::firstOrFail();

        $this->assertSame($tenant->id, $booking->tenant_id);
        $this->assertSame('custom', $booking->item_type);
        $this->assertSame('Walk-in consultation', $booking->item_name);
        $this->assertSame('completed', $booking->status);
        $this->assertSame('reception', $booking->source);
    }

    public function test_bookings_panel_can_update_booking_status(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'item_type' => 'custom',
            'item_name' => 'Walk-in consultation',
            'item_price' => 75,
            'customer_name' => 'Walk In',
            'customer_phone' => '+971 50 111 0000',
            'status' => 'pending',
            'source' => 'website',
        ]);

        Livewire::actingAs($owner)
            ->test(PanelBookings::class)
            ->call('updateStatus', $booking->id, 'confirmed')
            ->assertHasNoErrors();

        $this->assertSame('confirmed', $booking->fresh()->status);
    }

    public function test_bookings_panel_shows_current_month_completed_chart_with_tenant_currency(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();
        $tenant->update(['currency_code' => 'AED']);

        $this->bookingForChart($tenant, 'completed', 75, now());
        $this->bookingForChart($tenant, 'completed', 125, now()->subDay());
        $this->bookingForChart($tenant, 'pending', 900, now());
        $this->bookingForChart($tenant, 'completed', 500, now()->subMonth());

        $this->actingAs($owner)
            ->get('/panel/bookings')
            ->assertOk()
            ->assertSee('Monthly bookings')
            ->assertSee('2 completed')
            ->assertSee('AED 200.00')
            ->assertSee('Amount of money');
    }

    public function test_statistics_panel_shows_booking_and_website_action_reports(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();
        $tenant->update(['currency_code' => 'AED']);

        $service = Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Signature Massage',
            'price' => 250,
            'description' => 'Relaxing massage.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        Booking::create([
            'tenant_id' => $tenant->id,
            'service_id' => $service->id,
            'item_type' => 'service',
            'item_name' => 'Signature Massage',
            'item_price' => 250,
            'customer_name' => 'Repeat Customer',
            'customer_phone' => '+971 50 111 0000',
            'status' => 'completed',
            'source' => 'website',
        ]);

        TenantWebsiteActionEvent::create([
            'tenant_id' => $tenant->id,
            'action_type' => 'whatsapp',
            'context' => 'hero',
            'label' => 'WhatsApp booking',
            'target_url' => 'https://wa.me/971501110000',
        ]);

        TenantWebsiteActionEvent::create([
            'tenant_id' => $tenant->id,
            'action_type' => 'location',
            'context' => 'footer',
            'label' => 'Open Google Maps',
            'target_url' => 'https://maps.google.com/?q=Calm+Spa',
        ]);

        $this->actingAs($owner)
            ->get('/panel/statistics')
            ->assertOk()
            ->assertSee('AED 250.00')
            ->assertSee('Signature Massage')
            ->assertSee('Website action report')
            ->assertSee('WhatsApp')
            ->assertSee('Location');

        Livewire::actingAs($owner)
            ->test(PanelStatistics::class)
            ->assertSee('Monthly performance')
            ->assertSee('Website button clicks');
    }

    public function test_settings_panel_can_update_identity_with_logo(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelSettings::class)
            ->set('business_name', 'Updated Calm Spa')
            ->set('name', 'Updated Owner')
            ->set('email', 'updated-owner@example.test')
            ->set('logo', UploadedFile::fake()->image('logo.png', 600, 600)->size(500))
            ->call('saveIdentity')
            ->assertHasNoErrors();

        $tenant->refresh();
        $owner->refresh();

        $this->assertSame('Updated Calm Spa', $tenant->business_name);
        $this->assertSame('Updated Owner', $owner->name);
        $this->assertSame('updated-owner@example.test', $owner->email);
        $this->assertNotNull($tenant->logo_path);
        Storage::disk('public_uploads')->assertExists($tenant->logo_path);
    }

    public function test_settings_panel_can_update_website_contact_currency_and_actions(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelSettings::class)
            ->set('phone', '+201000000000')
            ->set('whatsapp_number', '+201111111111')
            ->set('address', '12 Wellness Street, Cairo')
            ->set('google_maps_url', 'https://maps.google.com/?q=Spaio')
            ->set('facebook_url', 'https://facebook.com/spaio')
            ->set('instagram_url', 'https://instagram.com/spaio')
            ->set('hero_title', 'Luxury care in minutes')
            ->set('hero_subtitle', 'A modern spa experience.')
            ->set('about_content', 'Premium spa services.')
            ->set('footer_content', 'Open daily.')
            ->set('currency_code', 'AED')
            ->set('show_whatsapp_button', true)
            ->set('show_call_button', false)
            ->set('show_location_button', true)
            ->set('hero_image', UploadedFile::fake()->image('hero.webp', 1600, 900)->size(1200))
            ->call('saveWebsite')
            ->assertHasNoErrors();

        $tenant->refresh();

        $this->assertSame('12 Wellness Street, Cairo', $tenant->address);
        $this->assertSame('https://maps.google.com/?q=Spaio', $tenant->google_maps_url);
        $this->assertSame('AED', $tenant->currency_code);
        $this->assertTrue($tenant->show_whatsapp_button);
        $this->assertFalse($tenant->show_call_button);
        $this->assertTrue($tenant->show_location_button);
        $this->assertNotNull($tenant->hero_image_path);
        Storage::disk('public_uploads')->assertExists($tenant->hero_image_path);
    }

    public function test_settings_panel_can_update_password(): void
    {
        [, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelSettings::class)
            ->set('current_password', 'password')
            ->set('password', 'new-secure-password')
            ->set('password_confirmation', 'new-secure-password')
            ->call('savePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('new-secure-password', $owner->fresh()->password));
    }

    public function test_billing_panel_can_change_plan(): void
    {
        Notification::fake();
        [$tenant, $owner] = $this->tenantWithOwner();

        $starter = $this->plan('starter', 'Starter', 9, 90);
        $pro = $this->plan('pro', 'Pro', 19, 190, true);

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $starter->id,
            'status' => 'trialing',
            'billing_cycle' => 'monthly',
            'trial_ends_at' => now()->addDays(7),
        ]);

        Livewire::actingAs($owner)
            ->test(PanelBilling::class)
            ->assertSee('Free trial is active')
            ->call('setBillingCycle', 'yearly')
            ->call('selectPlan', $pro->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'plan_id' => $pro->id,
            'status' => 'active',
            'billing_cycle' => 'yearly',
            'trial_ends_at' => null,
        ]);

        Notification::assertSentTo($owner, SubscriptionInvoiceNotification::class);
    }

    /**
     * @return array{Tenant, User}
     */
    private function tenantWithOwner(): array
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'phone' => '+201000000000',
            'hero_title' => 'Calm Spa Rituals',
            'hero_image_path' => 'https://example.test/hero.jpg',
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

        $tenant->update(['owner_user_id' => $owner->id]);

        return [$tenant, $owner];
    }

    private function bookingForChart(Tenant $tenant, string $status, int $price, mixed $createdAt): Booking
    {
        $booking = Booking::create([
            'tenant_id' => $tenant->id,
            'item_type' => 'custom',
            'item_name' => 'Chart booking',
            'item_price' => $price,
            'customer_name' => 'Chart Customer',
            'customer_phone' => '+971 50 111 0000',
            'status' => $status,
            'source' => 'website',
        ]);

        $booking->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->save();

        return $booking;
    }

    private function plan(string $slug, string $name, int $monthlyPrice, int $yearlyPrice, bool $featured = false): Plan
    {
        return Plan::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $name.' subscription plan.',
            'monthly_price' => $monthlyPrice,
            'yearly_price' => $yearlyPrice,
            'features' => ['Website', 'Bookings'],
            'is_featured' => $featured,
            'sort_order' => $featured ? 2 : 1,
        ]);
    }
}
