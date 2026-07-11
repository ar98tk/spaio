<?php

namespace App\Services;

use App\Models\Subscription;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdf;
use Illuminate\Support\Carbon;

class BillingInvoiceService
{
    /**
     * @return array<string, mixed>
     */
    public function payload(Subscription $subscription, ?string $customerEmail = null): array
    {
        $subscription->loadMissing('plan', 'tenant.owner');

        $plan = $subscription->plan;
        $cycle = $subscription->billing_cycle === 'yearly' ? 'yearly' : 'monthly';
        $amount = $subscription->status === 'trialing'
            ? 0
            : (float) ($cycle === 'yearly' ? $plan?->yearly_price : $plan?->monthly_price);
        $issuedAt = $subscription->updated_at ?? $subscription->created_at ?? now();
        $periodEndsAt = $subscription->current_period_ends_at ?? $subscription->trial_ends_at;

        return [
            'id' => $subscription->id,
            'number' => 'SPAIO-'.$subscription->id.'-'.$issuedAt->format('Ymd'),
            'description' => $subscription->status === 'trialing'
                ? 'Free trial'
                : ($plan?->name ?? 'Spaio plan').' '.$cycle.' subscription',
            'status' => $subscription->status,
            'billing_cycle' => $cycle,
            'issued_at' => $issuedAt,
            'period_ends_at' => $periodEndsAt,
            'amount' => $amount,
            'download_url' => route('panel.billing.invoice', $subscription),
            'customer_name' => $subscription->tenant?->business_name,
            'customer_email' => $customerEmail ?? $subscription->tenant?->owner?->email,
            'generated_at' => Carbon::now(),
        ];
    }

    public function pdf(Subscription $subscription, array $invoice): DomPdf
    {
        $subscription->loadMissing('plan', 'tenant.owner');

        return Pdf::loadView('pdf.invoice', [
            'invoice' => $invoice,
            'subscription' => $subscription,
            'tenant' => $subscription->tenant,
            'owner' => $subscription->tenant?->owner,
            'logoPath' => public_path('spaio-logo.png'),
        ])->setPaper('a4');
    }
}
