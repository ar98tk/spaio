<?php

namespace Tests\Feature;

use App\Livewire\Panel\Reception as PanelReception;
use App\Models\Package as SpaPackage;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReceptionBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_reception_page_lists_bookable_items_and_creates_booking(): void
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

        SpaPackage::create([
            'tenant_id' => $tenant->id,
            'name' => 'Glow Package',
            'included_description' => 'Massage and facial.',
            'original_price' => 500,
            'discounted_price' => 420,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->actingAs($owner)
            ->get('/panel/reception')
            ->assertOk()
            ->assertSee('Front-desk booking desk')
            ->assertSee('Aromatherapy Massage')
            ->assertSee('Glow Package');

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

    public function test_reception_can_create_custom_walk_in_booking(): void
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

        $this->assertDatabaseHas('bookings', [
            'tenant_id' => $tenant->id,
            'item_type' => 'custom',
            'item_name' => 'Walk-in consultation',
            'item_price' => 75,
            'source' => 'reception',
            'status' => 'completed',
        ]);
    }

    private function tenantWithOwner(): array
    {
        $tenant = Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'currency_code' => 'AED',
            'status' => 'active',
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Owner',
            'email' => 'owner@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $tenant->update(['owner_user_id' => $owner->id]);

        return [$tenant, $owner];
    }
}
