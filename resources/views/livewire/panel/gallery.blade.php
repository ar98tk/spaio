@php
    $titledCount = $images->filter(fn ($image) => filled($image->title))->count();
    $altCount = $images->filter(fn ($image) => filled($image->alt_text))->count();
    $uploadUrl = static function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : '/uploads/'.$path;
    };
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-12">
            <div class="card card-flush h-xl-100">
                <div class="card-body p-9">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-8">
                        <div>
                            <span class="badge badge-light-warning mb-4">Media</span>
                            <h2 class="fs-2 fw-bold text-gray-900 mb-3">Gallery images</h2>
                            <p class="text-gray-600 mb-0 mw-650px">Curate the visual story shown on the public website. Add captions, accessible alt text, and ordering for your best spa moments.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-6">
                            <div class="d-flex gap-8">
                                <div>
                                    <div class="fs-2 fw-bold text-gray-900">{{ $images->count() }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Images</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-warning">{{ $titledCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Titled</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-success">{{ $altCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Alt text</div>
                                </div>
                            </div>

                            @if ($canCreate)
                                <button type="button" wire:click="create" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Upload image
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($images->isNotEmpty())
        <div class="row g-5 g-xl-8">
            @foreach ($images as $image)
                <div class="col-md-6 col-xl-4 col-xxl-3" wire:key="gallery-card-{{ $image->id }}">
                    <div class="card card-flush h-100 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $uploadUrl($image->image_path) }}" alt="{{ $image->alt_text ?: $image->title ?: 'Gallery image' }}" class="w-100 object-fit-cover" style="height: 220px;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge badge-light-dark">#{{ $image->sort_order }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="fs-5 fw-bold text-gray-900 mb-2">{{ $image->title ?: 'Untitled image' }}</h3>
                            <p class="text-gray-600 mb-5">{{ $image->alt_text ?: 'No alt text added.' }}</p>
                            <div class="d-flex justify-content-end gap-2">
                                @if ($canUpdate)
                                    <button type="button" wire:click="edit({{ $image->id }})" class="btn btn-icon btn-light-primary btn-sm" aria-label="Edit {{ $image->title ?: 'image' }}">
                                        <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                    </button>
                                @endif
                                @if ($canDelete)
                                    <button type="button" wire:click="confirmDelete({{ $image->id }})" class="btn btn-icon btn-light-danger btn-sm" aria-label="Delete {{ $image->title ?: 'image' }}">
                                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card card-flush">
            <div class="card-body text-center py-15">
                <div class="symbol symbol-80px mb-6">
                    <span class="symbol-label bg-light-warning">
                        <i class="ki-duotone ki-picture fs-3x text-warning"><span class="path1"></span><span class="path2"></span></i>
                    </span>
                </div>
                <h3 class="fw-bold text-gray-900">No gallery images yet</h3>
                <p class="text-gray-600 mb-0">Upload your first image to start building a visual gallery for the public website.</p>
            </div>
        </div>
    @endif

    <div wire:ignore.self class="modal fade" id="galleryDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-trash fs-3x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Delete this image?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        <span class="fw-bold text-gray-900">{{ $deletingName ?: 'This image' }}</span>
                        will be removed from your public gallery. This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelDelete" class="btn btn-light">Keep image</button>
                        <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" wire:loading.attr="disabled" wire:target="deleteConfirmed">
                            <span wire:loading.remove wire:target="deleteConfirmed">Delete image</span>
                            <span wire:loading wire:target="deleteConfirmed">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $editingId ? 'Edit gallery image' : 'Upload gallery image' }}</h2>
                            <div class="text-gray-600 fs-7">JPG, PNG, or WebP. Max 2 MB and 3000px per side.</div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body py-10 px-lg-17">
                        <div class="row g-5">
                            <div class="col-md-8">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" wire:model="title" placeholder="Private treatment suite">
                                @error('title') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label required">Sort order</label>
                                <input type="number" min="0" class="form-control" wire:model="sort_order">
                                @error('sort_order') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alt text</label>
                                <input type="text" class="form-control" wire:model="alt_text" placeholder="Warm luxury spa treatment room with soft lighting">
                                @error('alt_text') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label {{ $editingId ? '' : 'required' }}">Image</label>
                                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="image">
                                <div wire:loading wire:target="image" class="text-primary fs-7 mt-2">Uploading image...</div>
                                @error('image') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                @if ($editingId)
                                    <div class="text-gray-600 fs-7 mt-2">Leave empty to keep the current image.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" wire:click="resetForm" class="btn btn-light me-3">Reset</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,image">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update image' : 'Upload image' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
        <script>
            const galleryModalElement = document.getElementById('galleryModal');

            $wire.on('gallery-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(galleryModalElement).show();
            });

            $wire.on('gallery-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(galleryModalElement).hide();
            });

            const galleryDeleteModalElement = document.getElementById('galleryDeleteModal');

            $wire.on('gallery-delete-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(galleryDeleteModalElement).show();
            });

            $wire.on('gallery-delete-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(galleryDeleteModalElement).hide();
            });
        </script>
    @endscript
</div>
