@php
    $activeCount = $packages->where('is_active', true)->count();
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
                            <span class="badge badge-light-success mb-4">Offers</span>
                            <h2 class="fs-2 fw-bold text-gray-900 mb-3">Spa packages</h2>
                            <p class="text-gray-600 mb-0 mw-650px">Bundle treatments into premium offers with original and discounted prices. Active packages are bookable from the public website.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-6">
                            <div class="d-flex gap-8">
                                <div>
                                    <div class="fs-2 fw-bold text-gray-900">{{ $packages->count() }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Total</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-success">{{ $activeCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Active</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-warning">{{ $packages->count() - $activeCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Hidden</div>
                                </div>
                            </div>

                            @if ($canCreate)
                                <button type="button" wire:click="create" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add package
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header">
            <h3 class="card-title fw-bold text-gray-900">Package list</h3>
        </div>
        <div class="card-body pt-0">
            @if ($packages->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-300px">Package</th>
                                <th>Original</th>
                                <th>Discounted</th>
                                <th>Status</th>
                                <th>Sort</th>
                                <th class="text-end min-w-120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($packages as $package)
                                <tr wire:key="package-{{ $package->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-80px me-5">
                                                @if ($uploadUrl($package->image_path))
                                                    <img src="{{ $uploadUrl($package->image_path) }}" alt="{{ $package->name }}" class="object-fit-cover">
                                                @else
                                                    <span class="symbol-label bg-light-success">
                                                        <i class="ki-duotone ki-parcel fs-2x text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-gray-900 fw-bold fs-6">{{ $package->name }}</div>
                                                <div class="text-gray-600 mw-550px">{!! nl2br(e($package->included_description)) !!}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-gray-600 text-decoration-line-through">{{ number_format((float) $package->original_price, 2) }}</td>
                                    <td class="text-gray-900 fw-bold">{{ number_format((float) $package->discounted_price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $package->is_active ? 'badge-light-success' : 'badge-light-warning' }}">
                                            {{ $package->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>{{ $package->sort_order }}</td>
                                    <td class="text-end">
                                        @if ($canUpdate)
                                            <button type="button" wire:click="edit({{ $package->id }})" class="btn btn-icon btn-light-primary btn-sm me-2" aria-label="Edit {{ $package->name }}">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        @endif
                                        @if ($canDelete)
                                            <button type="button" wire:click="confirmDelete({{ $package->id }})" class="btn btn-icon btn-light-danger btn-sm" aria-label="Delete {{ $package->name }}">
                                                <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-15">
                    <div class="symbol symbol-80px mb-6">
                        <span class="symbol-label bg-light-success">
                            <i class="ki-duotone ki-parcel fs-3x text-success"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-gray-900">No packages yet</h3>
                    <p class="text-gray-600 mb-0">Create your first package to highlight bundled spa offers.</p>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="packageDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-trash fs-3x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Delete this package?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        <span class="fw-bold text-gray-900">{{ $deletingName ?: 'This package' }}</span>
                        will be removed from your package catalog and public website. This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelDelete" class="btn btn-light">Keep package</button>
                        <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" wire:loading.attr="disabled" wire:target="deleteConfirmed">
                            <span wire:loading.remove wire:target="deleteConfirmed">Delete package</span>
                            <span wire:loading wire:target="deleteConfirmed">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="packageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $editingId ? 'Edit package' : 'Create package' }}</h2>
                            <div class="text-gray-600 fs-7">Use one clear image and concise package inclusions.</div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body py-10 px-lg-17">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">Package name</label>
                                <input type="text" class="form-control" wire:model="name">
                                @error('name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Original price</label>
                                <input type="number" min="0" step="0.01" class="form-control" wire:model="original_price">
                                @error('original_price') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Discounted price</label>
                                <input type="number" min="0" step="0.01" class="form-control" wire:model="discounted_price">
                                @error('discounted_price') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label required">Included items</label>
                                <textarea rows="5" class="form-control" wire:model="included_description" placeholder="60-minute massage&#10;Moroccan Royal Bath&#10;Steam room access"></textarea>
                                @error('included_description') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Sort order</label>
                                <input type="number" min="0" class="form-control" wire:model="sort_order">
                                @error('sort_order') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Package image</label>
                                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="image">
                                <div wire:loading wire:target="image" class="text-primary fs-7 mt-2">Uploading image...</div>
                                @error('image') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active">
                                    <span class="form-check-label fw-semibold text-gray-700">Active online</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" wire:click="resetForm" class="btn btn-light me-3">Reset</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,image">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update package' : 'Create package' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
        <script>
            const packageModalElement = document.getElementById('packageModal');

            $wire.on('package-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(packageModalElement).show();
            });

            $wire.on('package-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(packageModalElement).hide();
            });

            const packageDeleteModalElement = document.getElementById('packageDeleteModal');

            $wire.on('package-delete-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(packageDeleteModalElement).show();
            });

            $wire.on('package-delete-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(packageDeleteModalElement).hide();
            });
        </script>
    @endscript
</div>
