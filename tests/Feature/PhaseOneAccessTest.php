<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseOneAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_admin_area(): void
    {
        $this->withoutVite();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk();
    }

    public function test_tenant_owner_cannot_access_admin_area(): void
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

        $this->actingAs($owner)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_orphan_non_admin_user_cannot_access_tenant_dashboard(): void
    {
        $user = User::create([
            'name' => 'No Tenant',
            'email' => 'orphan@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $this->actingAs($user)
            ->get('/panel')
            ->assertForbidden();
    }
}
