<?php

namespace App\Livewire\Panel;

use App\Models\StaffMember;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Staff extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public string $name = '';

    public string $role = '';

    public string $bio = '';

    public $photo = null;

    public bool $is_active = true;

    public int $sort_order = 0;

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'staff'), 403);

        return view('livewire.panel.staff', [
            'staff' => $this->staffMembers(),
            'canCreate' => TenantPermissions::can(auth()->user(), 'staff.create'),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'staff.update'),
            'canDelete' => TenantPermissions::can(auth()->user(), 'staff.delete'),
        ])->layout('components.layouts.panel', [
            'title' => 'Staff',
        ]);
    }

    public function create(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'staff.create'), 403);

        $this->resetForm();
        $this->dispatch('staff-modal:show');
    }

    public function edit(int $staffId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'staff.update'), 403);

        $staffMember = $this->findTenantStaff($staffId);

        $this->editingId = $staffMember->id;
        $this->name = $staffMember->name;
        $this->role = (string) $staffMember->role;
        $this->bio = (string) $staffMember->bio;
        $this->photo = null;
        $this->is_active = (bool) $staffMember->is_active;
        $this->sort_order = (int) $staffMember->sort_order;

        $this->dispatch('staff-modal:show');
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId
                ? TenantPermissions::can(auth()->user(), 'staff.update')
                : TenantPermissions::can(auth()->user(), 'staff.create'),
            403
        );

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
            'is_active' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $tenantId = $this->tenantId();
        unset($validated['photo']);

        if ($this->editingId) {
            $staffMember = $this->findTenantStaff($this->editingId);

            if ($this->photo) {
                $this->deleteUpload($staffMember->photo_path);
                $validated['photo_path'] = $this->photo->store($this->tenantUploadDirectory('staff-photos'), 'public_uploads');
            }

            $staffMember->update($validated);
            session()->flash('success', 'Staff member updated.');
        } else {
            if ($this->photo) {
                $validated['photo_path'] = $this->photo->store($this->tenantUploadDirectory('staff-photos'), 'public_uploads');
            }

            StaffMember::create([
                ...$validated,
                'tenant_id' => $tenantId,
            ]);

            session()->flash('success', 'Staff member created.');
        }

        $this->resetForm();
        $this->dispatch('staff-modal:hide');
    }

    public function confirmDelete(int $staffId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'staff.delete'), 403);

        $staffMember = $this->findTenantStaff($staffId);

        $this->deletingId = $staffMember->id;
        $this->deletingName = $staffMember->name;

        $this->dispatch('staff-delete-modal:show');
    }

    public function deleteConfirmed(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'staff.delete'), 403);
        abort_unless($this->deletingId, 404);

        $staffMember = $this->findTenantStaff($this->deletingId);
        $this->deleteUpload($staffMember->photo_path);
        $staffMember->delete();

        if ($this->editingId === $staffMember->id) {
            $this->resetForm();
        }

        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('staff-delete-modal:hide');
        session()->flash('success', 'Staff member deleted.');
    }

    public function cancelDelete(): void
    {
        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('staff-delete-modal:hide');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'role', 'bio', 'photo']);
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * @return Collection<int, StaffMember>
     */
    private function staffMembers(): Collection
    {
        return StaffMember::query()
            ->forTenant($this->tenantId())
            ->orderBy('sort_order')
            ->latest()
            ->get();
    }

    private function findTenantStaff(int $staffId): StaffMember
    {
        return StaffMember::query()
            ->forTenant($this->tenantId())
            ->findOrFail($staffId);
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
