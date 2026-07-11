<?php

namespace App\Livewire\Panel;

use App\Models\Service;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Services extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public string $name = '';

    public string $price = '';

    public string $description = '';

    public $image = null;

    public bool $is_active = true;

    public int $sort_order = 0;

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'services'), 403);

        return view('livewire.panel.services', [
            'services' => $this->services(),
            'canCreate' => TenantPermissions::can(auth()->user(), 'services.create'),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'services.update'),
            'canDelete' => TenantPermissions::can(auth()->user(), 'services.delete'),
        ])->layout('components.layouts.panel', [
            'title' => 'Services',
        ]);
    }

    public function create(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'services.create'), 403);

        $this->resetForm();
        $this->dispatch('service-modal:show');
    }

    public function edit(int $serviceId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'services.update'), 403);

        $service = $this->findTenantService($serviceId);

        $this->editingId = $service->id;
        $this->name = $service->name;
        $this->price = (string) $service->price;
        $this->description = $service->description;
        $this->image = null;
        $this->is_active = (bool) $service->is_active;
        $this->sort_order = (int) $service->sort_order;

        $this->dispatch('service-modal:show');
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId
                ? TenantPermissions::can(auth()->user(), 'services.update')
                : TenantPermissions::can(auth()->user(), 'services.create'),
            403
        );

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'description' => ['required', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $tenantId = $this->tenantId();
        unset($validated['image']);

        if ($this->editingId) {
            $service = $this->findTenantService($this->editingId);

            if ($this->image) {
                $this->deleteUpload($service->image_path);
                $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('service-images'), 'public_uploads');
            }

            $service->update($validated);
            session()->flash('success', 'Service updated.');
        } else {
            if ($this->image) {
                $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('service-images'), 'public_uploads');
            }

            Service::create([
                ...$validated,
                'tenant_id' => $tenantId,
            ]);

            session()->flash('success', 'Service created.');
        }

        $this->resetForm();
        $this->dispatch('service-modal:hide');
    }

    public function confirmDelete(int $serviceId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'services.delete'), 403);

        $service = $this->findTenantService($serviceId);

        $this->deletingId = $service->id;
        $this->deletingName = $service->name;

        $this->dispatch('service-delete-modal:show');
    }

    public function deleteConfirmed(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'services.delete'), 403);
        abort_unless($this->deletingId, 404);

        $service = $this->findTenantService($this->deletingId);
        $this->deleteUpload($service->image_path);
        $service->delete();

        if ($this->editingId === $service->id) {
            $this->resetForm();
        }

        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('service-delete-modal:hide');
        session()->flash('success', 'Service deleted.');
    }

    public function cancelDelete(): void
    {
        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('service-delete-modal:hide');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'price', 'description', 'image']);
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Service>
     */
    private function services(): Collection
    {
        return Service::query()
            ->forTenant($this->tenantId())
            ->orderBy('sort_order')
            ->latest()
            ->get();
    }

    private function findTenantService(int $serviceId): Service
    {
        $service = Service::query()
            ->forTenant($this->tenantId())
            ->find($serviceId);

        abort_unless($service, 404);

        return $service;
    }

    private function tenantId(): int
    {
        $tenantId = auth()->user()?->tenant_id;

        abort_unless($tenantId, 403);

        return (int) $tenantId;
    }

    private function tenantUploadDirectory(string $directory): string
    {
        return 'tenants/'.$this->tenantId().'/'.$directory;
    }

    private function deleteUpload(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://')) {
            Storage::disk('public_uploads')->delete($path);
        }
    }
}
