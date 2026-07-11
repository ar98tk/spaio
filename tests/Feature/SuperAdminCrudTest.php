<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SuperAdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.test',
            'password' => 'password',
            'role' => 'super_admin',
        ]);
    }

    public function test_super_admin_can_create_update_and_delete_plan(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/plans', [
                'name' => 'Growth',
                'slug' => '',
                'description' => 'Growth plan',
                'monthly_price' => 25,
                'yearly_price' => 250,
                'features_text' => "Unlimited Services\nAnalytics",
                'is_featured' => true,
                'sort_order' => 4,
            ])
            ->assertRedirect();

        $plan = Plan::where('slug', 'growth')->firstOrFail();
        $this->assertSame(['Unlimited Services', 'Analytics'], $plan->features);

        $this->actingAs($this->admin)
            ->put("/admin/plans/{$plan->id}", [
                'name' => 'Growth Plus',
                'slug' => 'growth-plus',
                'description' => 'Updated growth plan',
                'monthly_price' => 29,
                'yearly_price' => 290,
                'features_text' => "All Templates\nSEO",
                'is_featured' => false,
                'sort_order' => 5,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('plans', [
            'id' => $plan->id,
            'slug' => 'growth-plus',
            'monthly_price' => 29,
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/plans/{$plan->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('plans', ['id' => $plan->id]);
    }

    public function test_super_admin_can_create_update_and_delete_tenant(): void
    {
        $plan = $this->plan();

        $this->actingAs($this->admin)
            ->post('/admin/tenants', [
                'business_name' => 'Lotus Spa',
                'subdomain' => 'lotus-spa',
                'status' => 'active',
                'plan_id' => $plan->id,
            ])
            ->assertRedirect();

        $tenant = Tenant::where('subdomain', 'lotus-spa')->firstOrFail();
        $this->assertDatabaseHas('subscriptions', ['tenant_id' => $tenant->id, 'plan_id' => $plan->id]);

        $this->actingAs($this->admin)
            ->put("/admin/tenants/{$tenant->id}", [
                'business_name' => 'Lotus Wellness',
                'subdomain' => 'lotus-wellness',
                'status' => 'paused',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'business_name' => 'Lotus Wellness',
            'subdomain' => 'lotus-wellness',
            'status' => 'paused',
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/tenants/{$tenant->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('tenants', ['id' => $tenant->id]);
    }

    public function test_super_admin_can_create_update_and_delete_user(): void
    {
        $tenant = $this->tenant();

        $this->actingAs($this->admin)
            ->post('/admin/users', [
                'tenant_id' => $tenant->id,
                'name' => 'Tenant Owner',
                'email' => 'tenant-owner@example.test',
                'role' => 'owner',
                'password' => 'password',
            ])
            ->assertRedirect();

        $user = User::where('email', 'tenant-owner@example.test')->firstOrFail();
        $this->assertSame($user->id, $tenant->fresh()->owner_user_id);

        $this->actingAs($this->admin)
            ->put("/admin/users/{$user->id}", [
                'tenant_id' => $tenant->id,
                'name' => 'Tenant Manager',
                'email' => 'tenant-manager@example.test',
                'role' => 'staff',
                'password' => '',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Tenant Manager',
            'email' => 'tenant-manager@example.test',
            'role' => 'staff',
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/users/{$user->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_super_admin_can_create_update_and_delete_subscription(): void
    {
        $tenant = $this->tenant();
        $starter = $this->plan('Starter', 'starter');
        $pro = $this->plan('Pro', 'pro');

        $this->actingAs($this->admin)
            ->post('/admin/subscriptions', [
                'tenant_id' => $tenant->id,
                'plan_id' => $starter->id,
                'status' => 'trialing',
                'billing_cycle' => 'monthly',
                'current_period_ends_at' => '',
            ])
            ->assertRedirect();

        $subscription = Subscription::where('tenant_id', $tenant->id)->firstOrFail();

        $this->actingAs($this->admin)
            ->put("/admin/subscriptions/{$subscription->id}", [
                'plan_id' => $pro->id,
                'status' => 'active',
                'billing_cycle' => 'yearly',
                'current_period_ends_at' => now()->addYear()->toDateString(),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'plan_id' => $pro->id,
            'status' => 'active',
            'billing_cycle' => 'yearly',
        ]);

        $this->actingAs($this->admin)
            ->delete("/admin/subscriptions/{$subscription->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('subscriptions', ['id' => $subscription->id]);
    }

    public function test_last_super_admin_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin)
            ->delete("/admin/users/{$this->admin->id}")
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'role' => 'super_admin',
        ]);
    }

    private function tenant(): Tenant
    {
        return Tenant::create([
            'business_name' => 'Calm Spa',
            'slug' => 'calm-spa',
            'subdomain' => 'calm-spa',
            'status' => 'active',
        ]);
    }

    private function plan(string $name = 'Starter', string $slug = 'starter'): Plan
    {
        return Plan::create([
            'name' => $name,
            'slug' => $slug,
            'description' => "{$name} plan",
            'monthly_price' => 9,
            'yearly_price' => 90,
            'features' => ['Dashboard'],
            'is_featured' => false,
            'sort_order' => 1,
        ]);
    }
}
