<?php

namespace App\Livewire\Panel;

use App\Models\GalleryImage;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gallery extends Component
{
    use WithFileUploads;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public string $title = '';

    public string $alt_text = '';

    public $image = null;

    public int $sort_order = 0;

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'gallery'), 403);

        return view('livewire.panel.gallery', [
            'images' => $this->images(),
            'canCreate' => TenantPermissions::can(auth()->user(), 'gallery.create'),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'gallery.update'),
            'canDelete' => TenantPermissions::can(auth()->user(), 'gallery.delete'),
        ])->layout('components.layouts.panel', [
            'title' => 'Gallery',
        ]);
    }

    public function create(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'gallery.create'), 403);

        $this->resetForm();
        $this->dispatch('gallery-modal:show');
    }

    public function edit(int $imageId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'gallery.update'), 403);

        $image = $this->findTenantImage($imageId);

        $this->editingId = $image->id;
        $this->title = (string) $image->title;
        $this->alt_text = (string) $image->alt_text;
        $this->image = null;
        $this->sort_order = (int) $image->sort_order;

        $this->dispatch('gallery-modal:show');
    }

    public function save(): void
    {
        abort_unless(
            $this->editingId
                ? TenantPermissions::can(auth()->user(), 'gallery.update')
                : TenantPermissions::can(auth()->user(), 'gallery.create'),
            403
        );

        $validated = $this->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'alt_text' => ['nullable', 'string', 'max:160'],
            'image' => [
                $this->editingId ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:max_width=3000,max_height=3000',
            ],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        unset($validated['image']);

        if ($this->editingId) {
            $galleryImage = $this->findTenantImage($this->editingId);

            if ($this->image) {
                $this->deleteUpload($galleryImage->image_path);
                $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('gallery-images'), 'public_uploads');
            }

            $galleryImage->update($validated);
            session()->flash('success', 'Gallery image updated.');
        } else {
            $validated['image_path'] = $this->image->store($this->tenantUploadDirectory('gallery-images'), 'public_uploads');

            GalleryImage::create([
                ...$validated,
                'tenant_id' => $this->tenantId(),
            ]);

            session()->flash('success', 'Gallery image uploaded.');
        }

        $this->resetForm();
        $this->dispatch('gallery-modal:hide');
    }

    public function confirmDelete(int $imageId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'gallery.delete'), 403);

        $image = $this->findTenantImage($imageId);

        $this->deletingId = $image->id;
        $this->deletingName = $image->title ?: 'Gallery image';

        $this->dispatch('gallery-delete-modal:show');
    }

    public function deleteConfirmed(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'gallery.delete'), 403);
        abort_unless($this->deletingId, 404);

        $image = $this->findTenantImage($this->deletingId);
        $this->deleteUpload($image->image_path);
        $image->delete();

        if ($this->editingId === $image->id) {
            $this->resetForm();
        }

        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('gallery-delete-modal:hide');
        session()->flash('success', 'Gallery image deleted.');
    }

    public function cancelDelete(): void
    {
        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('gallery-delete-modal:hide');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'title', 'alt_text', 'image']);
        $this->sort_order = 0;
        $this->resetValidation();
    }

    /**
     * @return Collection<int, GalleryImage>
     */
    private function images(): Collection
    {
        return GalleryImage::query()
            ->forTenant($this->tenantId())
            ->orderBy('sort_order')
            ->latest()
            ->get();
    }

    private function findTenantImage(int $imageId): GalleryImage
    {
        return GalleryImage::query()
            ->forTenant($this->tenantId())
            ->findOrFail($imageId);
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
