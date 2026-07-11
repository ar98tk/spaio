@php
    $activeCount = $staff->where('is_active', true)->count();
    $withPhotos = $staff->filter(fn ($member) => filled($member->photo_path))->count();
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
                            <span class="badge badge-light-info mb-4">Team</span>
                            <h2 class="fs-2 fw-bold text-gray-900 mb-3">Staff profiles</h2>
                            <p class="text-gray-600 mb-0 mw-650px">Showcase therapists and specialists on the public website with polished photos, roles, and short bios.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-6">
                            <div class="d-flex gap-8">
                                <div>
                                    <div class="fs-2 fw-bold text-gray-900">{{ $staff->count() }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Total</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-success">{{ $activeCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Visible</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-info">{{ $withPhotos }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Photos</div>
                                </div>
                            </div>

                            @if ($canCreate)
                                <button type="button" wire:click="create" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add staff
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
            <h3 class="card-title fw-bold text-gray-900">Team list</h3>
        </div>
        <div class="card-body pt-0">
            @if ($staff->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-280px">Staff member</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Sort</th>
                                <th class="text-end min-w-120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($staff as $member)
                                <tr wire:key="staff-{{ $member->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-80px me-5">
                                                @if ($uploadUrl($member->photo_path))
                                                    <img src="{{ $uploadUrl($member->photo_path) }}" alt="{{ $member->name }}" class="object-fit-cover">
                                                @else
                                                    <span class="symbol-label bg-light-info text-info fw-bold fs-2">{{ str($member->name)->substr(0, 1)->upper() }}</span>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-gray-900 fw-bold fs-6">{{ $member->name }}</div>
                                                <div class="text-gray-600 mw-550px">{{ $member->bio ?: 'No bio added yet.' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-gray-900 fw-bold">{{ $member->role ?: 'Specialist' }}</td>
                                    <td>
                                        <span class="badge {{ $member->is_active ? 'badge-light-success' : 'badge-light-warning' }}">
                                            {{ $member->is_active ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>{{ $member->sort_order }}</td>
                                    <td class="text-end">
                                        @if ($canUpdate)
                                            <button type="button" wire:click="edit({{ $member->id }})" class="btn btn-icon btn-light-primary btn-sm me-2" aria-label="Edit {{ $member->name }}">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        @endif
                                        @if ($canDelete)
                                            <button type="button" wire:click="confirmDelete({{ $member->id }})" class="btn btn-icon btn-light-danger btn-sm" aria-label="Delete {{ $member->name }}">
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
                        <span class="symbol-label bg-light-info">
                            <i class="ki-duotone ki-profile-user fs-3x text-info"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-gray-900">No staff yet</h3>
                    <p class="text-gray-600 mb-0">Add therapists and specialists to build trust on your public website.</p>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="staffDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-trash fs-3x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Delete this staff member?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        <span class="fw-bold text-gray-900">{{ $deletingName ?: 'This staff member' }}</span>
                        will be removed from your public team section. This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelDelete" class="btn btn-light">Keep staff</button>
                        <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" wire:loading.attr="disabled" wire:target="deleteConfirmed">
                            <span wire:loading.remove wire:target="deleteConfirmed">Delete staff</span>
                            <span wire:loading wire:target="deleteConfirmed">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="staffModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $editingId ? 'Edit staff member' : 'Create staff member' }}</h2>
                            <div class="text-gray-600 fs-7">Use a clear portrait and concise bio for public website cards.</div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body py-10 px-lg-17">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">Name</label>
                                <input type="text" class="form-control" wire:model="name">
                                @error('name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <input type="text" class="form-control" wire:model="role" placeholder="Aromatherapy Specialist">
                                @error('role') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea rows="4" class="form-control" wire:model="bio"></textarea>
                                @error('bio') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label class="form-label required">Sort order</label>
                                <input type="number" min="0" class="form-control" wire:model="sort_order">
                                @error('sort_order') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-7">
                                <label class="form-label">Photo</label>
                                <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="photo">
                                <div wire:loading wire:target="photo" class="text-primary fs-7 mt-2">Uploading photo...</div>
                                @error('photo') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-check form-switch form-check-custom form-check-solid">
                                    <input class="form-check-input" type="checkbox" wire:model="is_active">
                                    <span class="form-check-label fw-semibold text-gray-700">Visible online</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" wire:click="resetForm" class="btn btn-light me-3">Reset</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save,photo">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update staff' : 'Create staff' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
        <script>
            const staffModalElement = document.getElementById('staffModal');

            $wire.on('staff-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(staffModalElement).show();
            });

            $wire.on('staff-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(staffModalElement).hide();
            });

            const staffDeleteModalElement = document.getElementById('staffDeleteModal');

            $wire.on('staff-delete-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(staffDeleteModalElement).show();
            });

            $wire.on('staff-delete-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(staffDeleteModalElement).hide();
            });
        </script>
    @endscript
</div>
