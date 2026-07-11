<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\BillingInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BillingController extends Controller
{
    public function invoice(Request $request, Subscription $subscription): Response
    {
        $tenant = $request->user()->tenant;
        abort_unless($tenant && $subscription->tenant_id === $tenant->id, 403);

        $subscription->load('plan', 'tenant.owner');
        $invoiceService = app(BillingInvoiceService::class);
        $invoice = $invoiceService->payload($subscription, $request->user()->email);
        $pdf = $invoiceService->pdf($subscription, $invoice);

        return $pdf->download($invoice['number'].'.pdf');
    }
}
