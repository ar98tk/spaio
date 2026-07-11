<?php

namespace App\Livewire\Panel;

use App\Models\Plan;
use App\Models\Subscription;
use App\Notifications\SubscriptionInvoiceNotification;
use App\Services\BillingInvoiceService;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Billing extends Component
{
    public string $billingCycle = 'monthly';

    public function mount(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'billing.control'), 403);

        $this->billingCycle = $this->subscription()?->billing_cycle ?? 'monthly';
    }

    public function render(): View
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'billing.control'), 403);

        $subscription = $this->subscription();

        return view('livewire.panel.billing', [
            'subscription' => $subscription,
            'plans' => $this->plans(),
            'trial' => $this->trialSummary($subscription),
            'invoices' => $this->invoices($subscription),
        ])->layout('components.layouts.panel', [
            'title' => 'Billing',
        ]);
    }

    public function setBillingCycle(string $cycle): void
    {
        validator(['billingCycle' => $cycle], [
            'billingCycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ])->validate();

        $this->billingCycle = $cycle;
    }

    public function selectPlan(int $planId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'billing.control'), 403);

        validator([
            'plan_id' => $planId,
            'billing_cycle' => $this->billingCycle,
        ], [
            'plan_id' => ['required', 'exists:plans,id'],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
        ])->validate();

        $periodEndsAt = $this->billingCycle === 'yearly'
            ? now()->addYear()
            : now()->addMonth();

        $subscription = Subscription::updateOrCreate(
            ['tenant_id' => $this->tenantId()],
            [
                'plan_id' => $planId,
                'status' => 'active',
                'billing_cycle' => $this->billingCycle,
                'trial_ends_at' => null,
                'current_period_ends_at' => $periodEndsAt,
                'renewal_reminder_sent_at' => null,
            ]
        );

        $subscription->load('plan', 'tenant.owner');
        $subscription->tenant?->owner?->notify(new SubscriptionInvoiceNotification($subscription));
        $subscription->forceFill(['invoice_emailed_at' => now()])->save();

        session()->flash('success', 'Plan updated. An invoice email has been queued.');
    }

    /**
     * @return Collection<int, Plan>
     */
    private function plans(): Collection
    {
        return Plan::query()
            ->orderBy('sort_order')
            ->get();
    }

    private function subscription(): ?Subscription
    {
        return auth()->user()
            ?->tenant
            ?->subscription()
            ->with('plan', 'tenant.owner')
            ->first();
    }

    /**
     * @return array<string, mixed>
     */
    private function trialSummary(?Subscription $subscription): array
    {
        if (! $subscription || $subscription->status !== 'trialing' || ! $subscription->trial_ends_at) {
            return [
                'is_trialing' => false,
                'days_remaining' => 0,
                'ends_at' => null,
            ];
        }

        return [
            'is_trialing' => true,
            'days_remaining' => max(0, now()->startOfDay()->diffInDays($subscription->trial_ends_at->copy()->startOfDay(), false)),
            'ends_at' => $subscription->trial_ends_at,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function invoices(?Subscription $subscription): array
    {
        if (! $subscription || $subscription->status === 'trialing') {
            return [];
        }

        $invoice = app(BillingInvoiceService::class)->payload($subscription, auth()->user()?->email);
        $invoice['download_url'] = route('panel.billing.invoice', $subscription);

        return [$invoice];
    }

    private function tenantId(): int
    {
        $tenantId = auth()->user()?->tenant_id;

        abort_unless($tenantId, 403);

        return (int) $tenantId;
    }
}
