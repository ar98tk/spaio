<?php

namespace Tests\Feature;

use App\Livewire\Panel\Packages as PanelPackages;
use App\Livewire\Panel\Services as PanelServices;
use App\Livewire\Panel\Settings as PanelSettings;
use App\Models\Package as SpaPackage;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class TenantContentManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_owner_can_create_service_with_secure_image_upload(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelServices::class)
            ->set('name', 'Deep Tissue Massage')
            ->set('price', '45')
            ->set('description', 'A focused therapeutic massage.')
            ->set('image', UploadedFile::fake()->image('service.jpg', 1200, 900)->size(800))
            ->set('is_active', true)
            ->set('sort_order', 1)
            ->call('save')
            ->assertHasNoErrors();

        $service = Service::firstOrFail();

        $this->assertSame($tenant->id, $service->tenant_id);
        $this->assertSame('Deep Tissue Massage', $service->name);
        $this->assertTrue($service->is_active);
        $this->assertNotNull($service->image_path);
        Storage::disk('public_uploads')->assertExists($service->image_path);
    }

    public function test_service_image_rejects_invalid_extension_and_large_files(): void
    {
        Storage::fake('public_uploads');
        [, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelServices::class)
            ->set('name', 'Hydra Facial')
            ->set('price', '35')
            ->set('description', 'Skin refresh treatment.')
            ->set('image', UploadedFile::fake()->create('invoice.pdf', 100, 'application/pdf'))
            ->call('save')
            ->assertHasErrors(['image']);

        Livewire::actingAs($owner)
            ->test(PanelServices::class)
            ->set('name', 'Hydra Facial')
            ->set('price', '35')
            ->set('description', 'Skin refresh treatment.')
            ->set('image', UploadedFile::fake()->image('huge.jpg', 1200, 900)->size(3000))
            ->call('save')
            ->assertHasErrors(['image']);

        $this->assertDatabaseCount('services', 0);
    }

    public function test_tenant_cannot_update_another_tenants_service(): void
    {
        [$tenant] = $this->tenantWithOwner('calm-spa', 'owner-one@example.test');
        [, $otherOwner] = $this->tenantWithOwner('urban-spa', 'owner-two@example.test');

        $service = Service::create([
            'tenant_id' => $tenant->id,
            'name' => 'Private Hammam',
            'price' => 60,
            'description' => 'Private bath experience.',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        Livewire::actingAs($otherOwner)
            ->test(PanelServices::class)
            ->call('edit', $service->id)
            ->assertNotFound();
    }

    public function test_package_requires_discount_not_to_exceed_original_price_and_accepts_image(): void
    {
        Storage::fake('public_uploads');
        [$tenant, $owner] = $this->tenantWithOwner();

        Livewire::actingAs($owner)
            ->test(PanelPackages::class)
            ->set('name', 'Couples Escape')
            ->set('included_description', 'Massage, sauna, and tea service.')
            ->set('original_price', '100')
            ->set('discounted_price', '130')
            ->set('image', UploadedFile::fake()->image('offer.jpg', 1000, 700)->size(700))
            ->call('save')
            ->assertHasErrors(['discounted_price']);

        Livewire::actingAs($owner)
            ->test(PanelPackages::class)
            ->set('name', 'Couples Escape')
            ->set('included_description', 'Massage, sauna, and tea service.')
            ->set('original_price', '100')
            ->set('discounted_price', '79')
            ->set('image', UploadedFile::fake()->image('offer.jpg', 1000, 700)->size(700))
            ->call('save')
            ->assertHasNoErrors();

        $package = SpaPackage::firstOrFail();

        $this->assertSame($tenant->id, $package->tenant_id);
        $this->assertSame('Couples Escape', $package->name);
        Storage::disk('public_uploads')->assertExists($package->image_path);
    }

    public function test_tenant_can_update_website_settings_with_location_socials_actions_and_hero_image(): void
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
            ->set('tiktok_url', 'https://tiktok.com/@spaio')
            ->set('x_url', 'https://x.com/spaio')
            ->set('hero_title', 'Luxury care in minutes')
            ->set('hero_subtitle', 'A modern spa experience.')
            ->set('hero_image', UploadedFile::fake()->image('hero.webp', 1600, 900)->size(1200))
            ->set('about_content', 'Premium spa services.')
            ->set('footer_content', 'Open daily.')
            ->set('currency_code', 'AED')
            ->set('show_whatsapp_button', true)
            ->set('show_call_button', false)
            ->set('show_location_button', true)
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

    /**
     * @return array{Tenant, User}
     */
    private function tenantWithOwner(string $subdomain = 'calm-spa', string $email = 'owner@example.test'): array
    {
        $tenant = Tenant::create([
            'business_name' => str($subdomain)->replace('-', ' ')->title()->toString(),
            'slug' => $subdomain,
            'subdomain' => $subdomain,
            'status' => 'active',
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Tenant Owner',
            'email' => $email,
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $tenant->update(['owner_user_id' => $owner->id]);

        return [$tenant, $owner];
    }
}
