@php
    $roleLabels = [
        'owner' => 'Owner',
        'manager' => 'Manager',
        'receptionist' => 'Receptionist',
    ];
    $roleDescriptions = [
        'owner' => 'Full business account access.',
        'manager' => 'Can help manage business operations and users.',
        'receptionist' => 'Best for reception desk booking workflows.',
    ];
    $verifiedCount = $users->filter(fn ($user) => filled($user->email_verified_at))->count();
@endphp

<div>
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <div class="col-12">
            <div class="card card-flush h-xl-100">
                <div class="card-body p-9">
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-8">
                        <div>
                            <span class="badge badge-light-primary mb-4">Access control</span>
                            <h2 class="fs-2 fw-bold text-gray-900 mb-3">Dashboard users</h2>
                            <p class="text-gray-600 mb-0 mw-650px">Create login accounts for managers and reception teams. Public website staff profiles stay separate in the Staff page.</p>
                        </div>

                        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-6">
                            <div class="d-flex gap-8">
                                <div>
                                    <div class="fs-2 fw-bold text-gray-900">{{ $users->count() }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Users</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-success">{{ $verifiedCount }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Verified</div>
                                </div>
                                <div>
                                    <div class="fs-2 fw-bold text-primary">{{ count($manageableRoles) }}</div>
                                    <div class="fs-7 text-gray-500 text-uppercase">Roles</div>
                                </div>
                            </div>

                            @if ($canCreate)
                                <button type="button" wire:click="create" class="btn btn-primary">
                                    <i class="ki-duotone ki-plus fs-2"></i>
                                    Add user
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        @foreach ($roles as $roleOption)
            <div class="col-md-4">
                <div class="card card-flush h-100 {{ in_array($roleOption, $manageableRoles, true) ? 'border border-primary' : '' }}">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between gap-3 mb-4">
                            <span class="symbol symbol-45px">
                                <span class="symbol-label {{ $roleOption === 'owner' ? 'bg-light-danger' : ($roleOption === 'manager' ? 'bg-light-primary' : 'bg-light-success') }}">
                                    <i class="ki-duotone ki-shield-tick fs-2 {{ $roleOption === 'owner' ? 'text-danger' : ($roleOption === 'manager' ? 'text-primary' : 'text-success') }}"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </span>
                            <span class="badge {{ in_array($roleOption, $manageableRoles, true) ? 'badge-light-primary' : 'badge-light' }}">
                                {{ in_array($roleOption, $manageableRoles, true) ? 'Manageable' : 'Protected' }}
                            </span>
                        </div>
                        <h3 class="fs-4 fw-bold text-gray-900">{{ $roleLabels[$roleOption] ?? $roleOption }}</h3>
                        <p class="text-gray-600 mb-0">{{ $roleDescriptions[$roleOption] ?? 'Dashboard access role.' }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card card-flush">
        <div class="card-header">
            <h3 class="card-title fw-bold text-gray-900">User accounts</h3>
        </div>
        <div class="card-body pt-0">
            @if ($users->isNotEmpty())
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                        <thead>
                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-260px">User</th>
                                <th>Role</th>
                                <th>Verification</th>
                                <th>Permissions</th>
                                <th class="text-end min-w-120px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-700">
                            @foreach ($users as $user)
                                @php
                                    $canManageRow = in_array($user->role, $manageableRoles, true);
                                    $isPrimaryOwner = auth()->user()?->tenant?->owner_user_id === $user->id;
                                @endphp
                                <tr wire:key="user-{{ $user->id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-50px me-5">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold fs-2">{{ str($user->name)->substr(0, 1)->upper() }}</span>
                                            </div>
                                            <div>
                                                <div class="text-gray-900 fw-bold fs-6">{{ $user->name }}</div>
                                                <div class="text-gray-600">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $roleLabels[$user->role] ?? $user->role }}</span>
                                        @if ($isPrimaryOwner)
                                            <span class="badge badge-light-danger ms-2">Primary</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->email_verified_at)
                                            <span class="badge badge-light-success">Verified</span>
                                            <div class="text-gray-500 fs-8 mt-1">{{ $user->email_verified_at->format('M j, Y') }}</div>
                                        @else
                                            <span class="badge badge-light-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->role === 'owner')
                                            <span class="text-gray-600">Full access</span>
                                        @elseif ($user->role === 'receptionist')
                                            <span class="text-gray-600">Reception only</span>
                                        @else
                                            <span class="badge badge-light">{{ count($user->permissions ?? []) }} assigned</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if ($canUpdate && $canManageRow)
                                            <button type="button" wire:click="edit({{ $user->id }})" class="btn btn-icon btn-light-primary btn-sm me-2" aria-label="Edit {{ $user->name }}">
                                                <i class="ki-duotone ki-pencil fs-3"><span class="path1"></span><span class="path2"></span></i>
                                            </button>
                                        @endif
                                        @if ($canDelete && $canManageRow && auth()->id() !== $user->id && ! $isPrimaryOwner)
                                            <button type="button" wire:click="confirmDelete({{ $user->id }})" class="btn btn-icon btn-light-danger btn-sm" aria-label="Delete {{ $user->name }}">
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
                            <i class="ki-duotone ki-profile-user fs-3x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                        </span>
                    </div>
                    <h3 class="fw-bold text-gray-900">No users yet</h3>
                    <p class="text-gray-600 mb-0">Create the first dashboard user to start assigning access.</p>
                </div>
            @endif
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="userDeleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-500px">
            <div class="modal-content">
                <div class="modal-body text-center py-15 px-10">
                    <div class="symbol symbol-90px mb-7">
                        <span class="symbol-label bg-light-danger">
                            <i class="ki-duotone ki-trash fs-3x text-danger"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                        </span>
                    </div>

                    <h2 class="fw-bold text-gray-900 mb-3">Delete this user?</h2>
                    <p class="text-gray-600 fs-6 mb-8">
                        <span class="fw-bold text-gray-900">{{ $deletingName ?: 'This user' }}</span>
                        will lose dashboard access. This action cannot be undone.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" wire:click="cancelDelete" class="btn btn-light">Keep user</button>
                        <button type="button" wire:click="deleteConfirmed" class="btn btn-danger" wire:loading.attr="disabled" wire:target="deleteConfirmed">
                            <span wire:loading.remove wire:target="deleteConfirmed">Delete user</span>
                            <span wire:loading wire:target="deleteConfirmed">Deleting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <form wire:submit="save">
                    <div class="modal-header">
                        <div>
                            <h2 class="fw-bold mb-1">{{ $editingId ? 'Edit dashboard user' : 'Create dashboard user' }}</h2>
                            <div class="text-gray-600 fs-7">Owners verify by email. Managers and receptionists are verified immediately.</div>
                        </div>
                        <button type="button" class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </button>
                    </div>

                    <div class="modal-body py-10 px-lg-17">
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label required">Full name</label>
                                <input type="text" class="form-control" wire:model="name">
                                @error('name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Email address</label>
                                <input type="email" class="form-control" wire:model="email">
                                @error('email') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required">Role</label>
                                <select class="form-select" wire:model.live="role">
                                    @foreach ($manageableRoles as $roleOption)
                                        <option value="{{ $roleOption }}">{{ $roleLabels[$roleOption] ?? $roleOption }}</option>
                                    @endforeach
                                </select>
                                <div class="text-gray-600 fs-7 mt-2">{{ $roleDescriptions[$role] ?? 'Dashboard access role.' }}</div>
                                @error('role') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label {{ $editingId ? '' : 'required' }}">{{ $editingId ? 'New password' : 'Password' }}</label>
                                <input type="password" class="form-control" wire:model="password">
                                @error('password') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label {{ $editingId ? '' : 'required' }}">Confirm password</label>
                                <input type="password" class="form-control" wire:model="password_confirmation">
                            </div>

                            <div class="col-12">
                                @if ($role !== 'manager')
                                    <div class="alert alert-primary d-flex align-items-center p-5 mb-0">
                                        <i class="ki-duotone ki-shield-tick fs-2hx text-primary me-4"><span class="path1"></span><span class="path2"></span></i>
                                        <div>
                                            <div class="fw-bold text-gray-900">{{ $role === 'owner' ? 'Owners receive full access.' : 'Receptionists only receive the reception portal.' }}</div>
                                            <div class="text-gray-600 fs-7">Permission checkboxes apply to manager users only.</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="border border-dashed border-gray-300 rounded p-5">
                                        <div class="mb-5">
                                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Manager permissions</h3>
                                            <p class="text-gray-600 mb-0">Choose exactly what this manager can create, update, delete, or control.</p>
                                        </div>
                                        <div class="row g-5">
                                            @foreach ($permissionCatalog as $group => $groupPermissions)
                                                @continue($group === 'reception')
                                                <div class="col-md-6 col-xl-4">
                                                    <div class="bg-light rounded p-4 h-100">
                                                        <div class="fw-bold text-gray-800 text-capitalize mb-3">{{ $group }}</div>
                                                        <div class="d-flex flex-column gap-3">
                                                            @foreach ($groupPermissions as $permission)
                                                                <label class="form-check form-check-custom form-check-solid">
                                                                    <input class="form-check-input" type="checkbox" wire:model="permissions" value="{{ $permission }}">
                                                                    <span class="form-check-label text-gray-700">{{ str($permission)->after('.')->headline() }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('permissions') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" wire:click="resetForm" class="btn btn-light me-3">Reset</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                            <span wire:loading.remove wire:target="save">{{ $editingId ? 'Update user' : 'Create user' }}</span>
                            <span wire:loading wire:target="save">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @script
        <script>
            const userModalElement = document.getElementById('userModal');

            $wire.on('user-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(userModalElement).show();
            });

            $wire.on('user-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(userModalElement).hide();
            });

            const userDeleteModalElement = document.getElementById('userDeleteModal');

            $wire.on('user-delete-modal:show', () => {
                bootstrap.Modal.getOrCreateInstance(userDeleteModalElement).show();
            });

            $wire.on('user-delete-modal:hide', () => {
                bootstrap.Modal.getOrCreateInstance(userDeleteModalElement).hide();
            });
        </script>
    @endscript
</div>
