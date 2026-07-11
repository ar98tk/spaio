<?php

namespace App\Livewire\Panel;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Service;
use App\Models\Tenant;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Reception extends Component
{
    public string $selected_item = 'custom';

    public string $item_type = 'custom';

    public ?int $item_id = null;

    public string $item_name = '';

    public string $item_price = '';

    public string $customer_name = '';

    public string $customer_phone = '';

    public string $customer_email = '';

    public string $preferred_date = '';

    public string $preferred_time = '';

    public string $notes = '';

    public string $status = 'confirmed';

    public function mount(): void
    {
        $this->preferred_date = now()->toDateString();
        $this->selected_item = $this->defaultSelectedItem();
        $this->syncSelectedItem();
    }

    public function updatedSelectedItem(): void
    {
        $this->syncSelectedItem();
    }

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'reception'), 403);

        $tenant = $this->tenant();

        return view('livewire.panel.reception', [
            'tenant' => $tenant,
            'services' => $this->services(),
            'packages' => $this->packages(),
            'recentBookings' => $this->recentBookings(),
            'currency' => $tenant->currency_code ?: 'USD',
            'isReceptionist' => auth()->user()?->role === 'receptionist',
        ])->layout('components.layouts.panel', [
            'title' => 'Reception',
        ]);
    }

    public function save(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), TenantPermissions::RECEPTION_ACCESS) || TenantPermissions::can(auth()->user(), 'bookings.create'), 403);

        $this->syncSelectedItem();

        $validated = $this->validate([
            'item_type' => ['required', Rule::in(['service', 'package', 'custom'])],
            'item_id' => ['nullable', 'integer', 'min:1', 'required_unless:item_type,custom'],
            'item_name' => ['nullable', 'string', 'max:160', 'required_if:item_type,custom'],
            'item_price' => ['nullable', 'numeric', 'min:0', 'max:999999.99', 'required_if:item_type,custom'],
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'status' => ['required', Rule::in(Booking::STATUSES)],
        ]);

        $tenant = $this->tenant();
        $item = $validated['item_type'] === 'custom'
            ? null
            : $this->bookableItem($tenant, $validated['item_type'], (int) $validated['item_id']);

        Booking::create([
            'tenant_id' => $tenant->id,
            'service_id' => $validated['item_type'] === 'service' ? $item->id : null,
            'package_id' => $validated['item_type'] === 'package' ? $item->id : null,
            'item_type' => $validated['item_type'],
            'item_name' => $item?->name ?? $validated['item_name'],
            'item_price' => $validated['item_type'] === 'custom'
                ? $validated['item_price']
                : ($validated['item_type'] === 'service' ? $item->price : $item->discounted_price),
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_email' => $validated['customer_email'] ?: null,
            'preferred_date' => $validated['preferred_date'] ?: now()->toDateString(),
            'preferred_time' => $validated['preferred_time'] ?: null,
            'notes' => $validated['notes'] ?: null,
            'status' => $validated['status'],
            'source' => 'reception',
        ]);

        $selectedItem = $this->selected_item;
        $this->reset(['item_name', 'item_price', 'customer_name', 'customer_phone', 'customer_email', 'preferred_time', 'notes']);
        $this->selected_item = $selectedItem;
        $this->preferred_date = now()->toDateString();
        $this->status = 'confirmed';
        $this->syncSelectedItem();
        $this->resetValidation();

        session()->flash('success', 'Reception booking created.');
    }

    private function syncSelectedItem(): void
    {
        if ($this->selected_item === 'custom') {
            $this->item_type = 'custom';
            $this->item_id = null;

            return;
        }

        [$type, $id] = array_pad(explode(':', $this->selected_item, 2), 2, null);

        if (! in_array($type, ['service', 'package'], true) || ! $id) {
            $this->selected_item = 'custom';
            $this->item_type = 'custom';
            $this->item_id = null;

            return;
        }

        $this->item_type = $type;
        $this->item_id = (int) $id;
        $this->item_name = '';
        $this->item_price = '';
    }

    private function defaultSelectedItem(): string
    {
        $tenantId = $this->tenantId();
        $service = Service::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        if ($service) {
            return 'service:'.$service->id;
        }

        $package = Package::query()
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        return $package ? 'package:'.$package->id : 'custom';
    }

    /**
     * @return Collection<int, Service>
     */
    private function services(): Collection
    {
        return Service::query()
            ->where('tenant_id', $this->tenantId())
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'price']);
    }

    /**
     * @return Collection<int, Package>
     */
    private function packages(): Collection
    {
        return Package::query()
            ->where('tenant_id', $this->tenantId())
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'discounted_price']);
    }

    /**
     * @return Collection<int, Booking>
     */
    private function recentBookings(): Collection
    {
        return Booking::query()
            ->forTenant($this->tenantId())
            ->whereIn('source', ['reception', 'walk-in'])
            ->latest()
            ->limit(8)
            ->get();
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

    private function tenant(): Tenant
    {
        $tenant = auth()->user()?->tenant;

        abort_unless($tenant, 403);

        return $tenant;
    }

    private function tenantId(): int
    {
        $tenantId = auth()->user()?->tenant_id;

        abort_unless($tenantId, 403);

        return (int) $tenantId;
    }
}
