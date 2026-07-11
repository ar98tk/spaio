<?php

namespace App\Livewire\Panel;

use App\Models\Package;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Packages extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public string $name = '';

    public string $included_description = '';

    public string $original_price = '';

    public string $discounted_price = '';

    public $image = null;

    public bool $is_active = true;

    public int $sort_order = 0;

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'packages'), 403);

        return view('livewire.panel.packages', [
            'packages' => $this->packages(),
            'canCreate' => TenantPermissions::can(auth()->user(), 'packages.create'),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'packages.update'),
            'canDelete' => TenantPermissions::can(auth()->user(), 'packages.delete'),
        ])->layout('components.layouts.panel', [
            'title' => 'Packages',
        ]);
    }

    public function create(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'packages.create'), 403);

        $this->resetForm();
        $this->dispatch('package-modal:show');
    }

    public function edit(int $packageId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'packages.update'), 403);

        $package = $this->findTenantPackage($packageId);

        $this->editingId = $package->id;
        $this->name = $package->name;
        $this->included_description = $package->included_description;
        $this->original_price = (string) $package->original_price;
        $this->discounted_price = (string) $package->discounted_price;
        $this->image = null;
        $this->is_active = (bool) $package->is_active;
        $this->sort_order = (int) $package->sort_order;

        $this->dispatch('package-modal:show');
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId
                ? TenantPermissions::can(auth()->user(), 'packages.update')
                : TenantPermissions::can(auth()->user(), 'packages.create'),
            403
        );

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'included_description' => ['required', 'string', 'max:2500'],
            'original_price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'discounted_price' => ['required', 'numeric', 'min:0', 'max:999999.99', 'lte:original_price'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $tenantId = $this->tenantId();
        unset($validated['image']);

        if ($this->editingId) {
            $package = $this->findTenantPackage($this->editingId);

            if ($this->image) {
                $this->deleteUpload($package->image_path);
                $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('package-images'), 'public_uploads');
            }

            $package->update($validated);
            session()->flash('success', 'Package updated.');
        } else {
            if ($this->image) {
                $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('package-images'), 'public_uploads');
            }

            Package::create([
                ...$validated,
                'tenant_id' => $tenantId,
            ]);

            session()->flash('success', 'Package created.');
        }

        $this->resetForm();
        $this->dispatch('package-modal:hide');
    }

    public function confirmDelete(int $packageId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'packages.delete'), 403);

        $package = $this->findTenantPackage($packageId);

        $this->deletingId = $package->id;
        $this->deletingName = $package->name;

        $this->dispatch('package-delete-modal:show');
    }

    public function deleteConfirmed(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'packages.delete'), 403);
        abort_unless($this->deletingId, 404);

        $package = $this->findTenantPackage($this->deletingId);
        $this->deleteUpload($package->image_path);
        $package->delete();

        if ($this->editingId === $package->id) {
            $this->resetForm();
        }

        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('package-delete-modal:hide');
        session()->flash('success', 'Package deleted.');
    }

    public function cancelDelete(): void
    {
        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('package-delete-modal:hide');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'included_description', 'original_price', 'discounted_price', 'image']);
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * @return Collection<int, Package>
     */
    private function packages(): Collection
    {
        return Package::query()
            ->forTenant($this->tenantId())
            ->orderBy('sort_order')
            ->latest()
            ->get();
    }

    private function findTenantPackage(int $packageId): Package
    {
        return Package::query()
            ->forTenant($this->tenantId())
            ->findOrFail($packageId);
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
