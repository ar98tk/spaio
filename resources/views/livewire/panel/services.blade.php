@php
    $activeCount = $services->where('is_active', true)->count();
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
                            <span class="badge badge-light-primary mb-4">Catalog</span>
                            <h2 class="fs-2 fw-bold text-gray-900 mb-3">Services menu</h2>
                            <p class="text-gray-600 mb-0 mw-650px">Create the treatments customers can book from your website. Active services appear in the selected website template.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-6">
                            <div class="d-flex gap-8">
                                <div>
                                    <div class="fs-2 fw-bold text-gray-900">{{ $services->count() }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Total</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-success">{{ $activeCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Active</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-warning">{{ $services->count() - $activeCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Hidden</div>
                                </div>
                            </div>

                            @if ($canCreate)
                                <button type="button" wire:click="create" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add service
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
            <h3 class="card-title fw-bold text-gray-900">Service list</h3>
        </div>
        <div class="card-body pt-0">
            @if ($services->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-250px">Service</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Sort</th>
                                <th class="text-end min-w-120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($services as $service)
                                <tr wire:key="service-{{ $service->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-80px me-5">
                                                @if ($uploadUrl($service->image_path))
                                                    <img src="{{ $uploadUrl($service->image_path) }}" alt="{{ $service->name }}" class="object-fit-cover">
                                                @else
                                                    <span class="symbol-label bg-light-primary">
                                                        <i class="ki-duotone ki-picture fs-2x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-gray-900 fw-bold fs-6">{{ $service->name }}</div>
                                                <div class="text-gray-600 mw-500px">{{ $service->description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-gray-900 fw-bold">{{ number_format((float) $service->price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $service->is_active ? 'badge-light-success' : 'badge-light-warning' }}">
                                            {{ $service->is_active ? 'Active' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>{{ $service->sort_order }}</td>
                                    <td class="text-end">
                                        @if ($canUpdate)
                                            <button type="button" wire:click="edit({{ $service->id }})" class="btn btn-icon btn-light-primary btn-sm me-2" aria-label="Edit {{ $service->name }}">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        @endif
                                        @if ($canDelete)
                                            <button type="button" wire:click="confirmDelete({{ $service->id }})" class="btn btn-icon btn-light-danger btn-sm" aria-label="Delete {{ $service->name }}">
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
                        <span class="symbol-label bg-light-primary">
                            <i class="ki-duotone ki-shop fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-gray-900">No services yet</h3>
                    <p class="text-gray-600 mb-0">Create your first treatment to make it available on the tenant website.</p>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="serviceDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-trash fs-3x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Delete this service?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        <span class="fw-bold text-gray-900">{{ $deletingName ?: 'This service' }}</span>
                        will be removed from your catalog and public website. This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelDelete" class="btn btn-light">
                            Keep service
                        </button>
                        <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" wire:loading.attr="disabled" wire:target="deleteConfirmed">
                            <span wire:loading.remove wire:target="deleteConfirmed">Delete service</span>
                            <span wire:loading wire:target="deleteConfirmed">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="serviceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $editingId ? 'Edit service' : 'Create service' }}</h2>
                            <div class="text-gray-600 fs-7">JPG, PNG, or WebP. Max 2 MB and 3000px per side.</div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body py-10 px-lg-17">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">Service name</label>
                                <input type="text" class="form-control" wire:model="name">
                                @error('name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Price</label>
                                <input type="number" min="0" step="0.01" class="form-control" wire:model="price">
                                @error('price') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label required">Sort order</label>
                                <input type="number" min="0" class="form-control" wire:model="sort_order">
                                @error('sort_order') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label required">Description</label>
                                <textarea rows="4" class="form-control" wire:model="description"></textarea>
                                @error('description') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Service image</label>
                                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="image">
                                <div wire:loading wire:target="image" class="text-primary fs-7 mt-2">Uploading image...</div>
                                @error('image') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
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
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update service' : 'Create service' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
        <script>
            const serviceModalElement = document.getElementById('serviceModal');

            $wire.on('service-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(serviceModalElement).show();
            });

            $wire.on('service-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(serviceModalElement).hide();
            });

            const serviceDeleteModalElement = document.getElementById('serviceDeleteModal');

            $wire.on('service-delete-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(serviceDeleteModalElement).show();
            });

            $wire.on('service-delete-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(serviceDeleteModalElement).hide();
            });
        </script>
    @endscript
</div>
