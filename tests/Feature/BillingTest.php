<?php

namespace Tests\Feature;

use App\Livewire\Panel\Billing as PanelBilling;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class BillingTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_billing_with_trial_status_and_plans(): void
    {
        $this->withoutVite();

        [$tenant, $owner, $plan] = $this->createBillingAccount();

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'trialing',
            'billing_cycle' => 'monthly',
            'trial_ends_at' => now()->addDays(7),
        ]);

        $this->actingAs($owner)
            ->get('/panel/billing')
            ->assertOk()
            ->assertSee('Free trial is active')
            ->assertSee('Starter')
            ->assertSee('Choose the right plan');
    }

    public function test_owner_can_change_billing_plan(): void
    {
        [$tenant, $owner, $starter] = $this->createBillingAccount();
        $pro = Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Full analytics and all templates.',
            'monthly_price' => 19,
            'yearly_price' => 190,
            'features' => ['Unlimited services', 'Full analytics'],
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $starter->id,
            'status' => 'trialing',
            'billing_cycle' => 'monthly',
            'trial_ends_at' => now()->addDays(7),
        ]);

        Notification::fake();

        Livewire::actingAs($owner)
            ->test(PanelBilling::class)
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
    }

    public function test_owner_can_download_subscription_invoice_pdf(): void
    {
        [$tenant, $owner, $plan] = $this->createBillingAccount();

        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'billing_cycle' => 'monthly',
            'current_period_ends_at' => now()->addMonth(),
        ]);

        $response = $this->actingAs($owner)
            ->get("/panel/billing/invoices/{$subscription->id}");

        $response->assertOk();
        $this->assertStringContainsString('application/pdf', $response->headers->get('content-type'));
        $this->assertStringContainsString('SPAIO-', $response->headers->get('content-disposition'));
    }

    private function createBillingAccount(): array
    {
        $tenant = Tenant::create([
            'business_name' => 'Dubai Glow Spa',
            'slug' => 'dubai-glow-spa',
            'subdomain' => 'dubai-glow-spa',
            'status' => 'active',
            'address' => 'Business Bay, Dubai',
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Alya Hassan',
            'email' => 'owner@example.test',
            'email_verified_at' => now(),
            'password' => 'password',
            'role' => 'owner',
        ]);

        $tenant->update(['owner_user_id' => $owner->id]);

        $plan = Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'description' => 'Launch plan for small spa businesses.',
            'monthly_price' => 9,
            'yearly_price' => 90,
            'features' => ['20 services', 'Basic analytics'],
            'sort_order' => 1,
        ]);

        return [$tenant, $owner, $plan];
    }
}
