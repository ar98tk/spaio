<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function store(Request $request, Tenant $tenant): RedirectResponse
    {
        abort_unless($tenant->status === 'active', 404);

        $validated = $request->validate([
            'item_type' => ['required', Rule::in(['service', 'package'])],
            'item_id' => ['required', 'integer', 'min:1'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:1500'],
        ]);

        $item = $this->bookableItem($tenant, $validated['item_type'], (int) $validated['item_id']);

        Booking::create([
            'tenant_id' => $tenant->id,
            'service_id' => $validated['item_type'] === 'service' ? $item->id : null,
            'package_id' => $validated['item_type'] === 'package' ? $item->id : null,
            'item_type' => $validated['item_type'],
            'item_name' => $item->name,
            'item_price' => $validated['item_type'] === 'service' ? $item->price : $item->discounted_price,
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?? null,
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
            'source' => 'website',
        ]);

        return back()->with('success', 'Booking request sent. The spa team will contact you shortly.');
    }

    public function storeBySubdomain(Request $request, string $tenantSubdomain): RedirectResponse
    {
        $tenant = Tenant::query()
            ->where('subdomain', $tenantSubdomain)
            ->where('status', 'active')
            ->firstOrFail();

        return $this->store($request, $tenant);
    }

    private function bookableItem(Tenant $tenant, string $itemType, int $itemId): Service|Package
    {
        if ($itemType === 'service') {
            return Service::query()
                ->where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->findOrFail($itemId);
        }

        return Package::query()
            ->where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->findOrFail($itemId);
    }
}
