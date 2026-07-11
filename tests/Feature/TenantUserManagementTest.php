<?php

namespace Tests\Feature;

use App\Livewire\Panel\Services as PanelServices;
use App\Livewire\Panel\Users as PanelUsers;
use App\Models\Service;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class TenantUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_manage_dashboard_users(): void
    {
        Notification::fake();
        [$tenant, $owner] = $this->tenantWithOwner();

        $this->actingAs($owner)
            ->get('/panel/users')
            ->assertOk()
            ->assertSee('Dashboard users');

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->set('name', 'Reception User')
            ->set('email', 'reception@example.test')
            ->set('role', 'receptionist')
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('save')
            ->assertHasNoErrors();

        $user = User::where('email', 'reception@example.test')->firstOrFail();
        $this->assertSame($tenant->id, $user->tenant_id);
        $this->assertSame('receptionist', $user->role);
        $this->assertNotNull($user->email_verified_at);
        Notification::assertNothingSent();

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->call('edit', $user->id)
            ->set('name', 'Reception Manager')
            ->set('email', 'manager@example.test')
            ->set('role', 'manager')
            ->set('permissions', ['services.create', 'services.update'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Reception Manager',
            'email' => 'manager@example.test',
            'role' => 'manager',
        ]);
        $this->assertSame(['services.create', 'services.update'], $user->refresh()->permissions);

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->call('confirmDelete', $user->id)
            ->call('deleteConfirmed')
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_receptionist_can_only_access_reception_portal(): void
    {
        [$tenant] = $this->tenantWithOwner();
        $receptionist = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Reception',
            'email' => 'reception@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'receptionist',
        ]);

        $this->actingAs($receptionist)
            ->get('/panel/users')
            ->assertRedirect(route('panel.reception'))
            ->assertSessionHas('error');

        $this->actingAs($receptionist)
            ->get('/panel')
            ->assertOk();

        $this->actingAs($receptionist)
            ->get('/panel/reception')
            ->assertOk();
    }

    public function test_manager_permissions_control_dashboard_actions(): void
    {
        [$tenant, $owner] = $this->tenantWithOwner();

        $manager = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Manager',
            'email' => 'manager@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'manager',
            'permissions' => ['services.create'],
        ]);

        $this->actingAs($manager)
            ->get('/panel/services')
            ->assertOk();

        Livewire::actingAs($manager)
            ->test(PanelServices::class)
            ->set('name', 'Massage')
            ->set('price', '100')
            ->set('description', 'Relaxing session')
            ->set('is_active', true)
            ->set('sort_order', 1)
            ->call('save')
            ->assertHasNoErrors();

        $service = $tenant->services()->firstOrFail();

        Livewire::actingAs($manager)
            ->test(PanelServices::class)
            ->call('edit', $service->id)
            ->assertForbidden();

        $this->actingAs($manager)
            ->get('/panel/billing')
            ->assertRedirect(route('panel.index'))
            ->assertSessionHas('error');

        Livewire::actingAs($owner)
            ->test(PanelUsers::class)
            ->call('edit', $manager->id)
            ->set('name', 'Manager')
            ->set('email', 'manager@example.test')
            ->set('role', 'manager')
            ->set('permissions', ['services.create', 'services.update', 'billing.control'])
            ->call('save')
            ->assertHasNoErrors();

        $this->actingAs($manager->refresh())
            ->get('/panel/billing')
            ->assertOk();
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
