<?php

namespace App\Livewire\Panel;

use App\Models\User;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Users extends Component
{
    private const ROLES = ['owner', 'manager', 'receptionist'];

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $deletingName = '';

    public string $name = '';

    public string $email = '';

    public string $role = 'receptionist';

    /**
     * @var array<int, string>
     */
    public array $permissions = [];

    public string $password = '';

    public string $password_confirmation = '';

    public function render(): View
    {
        abort_unless(TenantPermissions::group(auth()->user(), 'users') && $this->canManageUsers(), 403);

        return view('livewire.panel.users', [
            'users' => $this->users(),
            'roles' => self::ROLES,
            'manageableRoles' => $this->manageableRoles(),
            'permissionCatalog' => TenantPermissions::GROUPS,
            'currentUserId' => auth()->id(),
            'canCreate' => TenantPermissions::can(auth()->user(), 'users.create'),
            'canUpdate' => TenantPermissions::can(auth()->user(), 'users.update'),
            'canDelete' => TenantPermissions::can(auth()->user(), 'users.delete'),
        ])->layout('components.layouts.panel', [
            'title' => 'Users',
        ]);
    }

    public function create(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'users.create') && $this->canManageUsers(), 403);

        $this->resetForm();
        $this->dispatch('user-modal:show');
    }

    public function edit(int $userId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'users.update') && $this->canManageUsers(), 403);

        $user = $this->findTenantUser($userId);
        abort_unless(in_array($user->role, $this->manageableRoles(), true), 403);

        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->permissions = $user->permissions ?? [];
        $this->password = '';
        $this->password_confirmation = '';

        $this->dispatch('user-modal:show');
    }

    public function save(): void
    {
        abort_unless(
            ($this->editingId
                ? TenantPermissions::can(auth()->user(), 'users.update')
                : TenantPermissions::can(auth()->user(), 'users.create'))
            && $this->canManageUsers(),
            403
        );

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:255',
                $this->editingId
                    ? Rule::unique('users', 'email')->ignore($this->editingId)
                    : Rule::unique('users', 'email'),
            ],
            'role' => ['required', Rule::in($this->manageableRoles())],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(TenantPermissions::all())],
            'password' => [
                $this->editingId ? 'nullable' : 'required',
                'confirmed',
                Password::defaults(),
            ],
        ]);

        $role = $validated['role'];
        $allowedPermissions = $role === 'manager'
            ? $this->allowedPermissions($validated['permissions'] ?? [])
            : [];

        if ($this->editingId) {
            $user = $this->findTenantUser($this->editingId);
            abort_unless(in_array($user->role, $this->manageableRoles(), true), 403);

            $emailChanged = $user->email !== $validated['email'];

            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $role,
                'permissions' => $allowedPermissions,
                'email_verified_at' => $role === 'owner'
                    ? ($emailChanged ? null : $user->email_verified_at)
                    : ($user->email_verified_at ?? now()),
                ...($validated['password'] ? ['password' => $validated['password']] : []),
            ]);

            if ($emailChanged && $role === 'owner') {
                $user->sendEmailVerificationNotification();
            }

            session()->flash('success', $emailChanged ? 'User updated. A new verification email was sent.' : 'User updated.');
        } else {
            $user = User::create([
                'tenant_id' => $this->tenantId(),
                'name' => $validated['name'],
                'email' => $validated['email'],
                'email_verified_at' => $role === 'owner' ? null : now(),
                'role' => $role,
                'permissions' => $allowedPermissions,
                'password' => $validated['password'],
            ]);

            if ($role === 'owner') {
                $user->sendEmailVerificationNotification();
            }

            session()->flash('success', $role === 'owner' ? 'User created. A verification email was sent.' : 'User created and can access the dashboard immediately.');
        }

        $this->resetForm();
        $this->dispatch('user-modal:hide');
    }

    public function confirmDelete(int $userId): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'users.delete') && $this->canManageUsers(), 403);

        $user = $this->findTenantUser($userId);
        $this->authorizeDelete($user);

        $this->deletingId = $user->id;
        $this->deletingName = $user->name;

        $this->dispatch('user-delete-modal:show');
    }

    public function deleteConfirmed(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'users.delete') && $this->canManageUsers(), 403);
        abort_unless($this->deletingId, 404);

        $user = $this->findTenantUser($this->deletingId);
        $this->authorizeDelete($user);
        $user->delete();

        if ($this->editingId === $user->id) {
            $this->resetForm();
        }

        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('user-delete-modal:hide');
        session()->flash('success', 'User deleted.');
    }

    public function cancelDelete(): void
    {
        $this->reset(['deletingId', 'deletingName']);
        $this->dispatch('user-delete-modal:hide');
    }

    public function resetForm(): void
    {
        $this->reset(['editingId', 'name', 'email', 'permissions', 'password', 'password_confirmation']);
        $this->role = in_array('receptionist', $this->manageableRoles(), true)
            ? 'receptionist'
            : $this->manageableRoles()[0];
        $this->resetValidation();
    }

    /**
     * @return Collection<int, User>
     */
    private function users(): Collection
    {
        return User::query()
            ->where('tenant_id', $this->tenantId())
            ->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'manager' THEN 2 WHEN 'receptionist' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'permissions', 'email_verified_at', 'created_at']);
    }

    private function findTenantUser(int $userId): User
    {
        return User::query()
            ->where('tenant_id', $this->tenantId())
            ->findOrFail($userId);
    }

    private function tenantId(): int
    {
        $tenantId = auth()->user()?->tenant_id;

        abort_unless($tenantId, 403);

        return (int) $tenantId;
    }

    private function canManageUsers(): bool
    {
        return in_array(auth()->user()?->role, ['owner', 'manager'], true);
    }

    /**
     * @return array<int, string>
     */
    private function manageableRoles(): array
    {
        return auth()->user()?->role === 'owner'
            ? self::ROLES
            : ['manager', 'receptionist'];
    }

    /**
     * @param  array<int, string>  $permissions
     * @return array<int, string>
     */
    private function allowedPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, TenantPermissions::all()));
    }

    private function authorizeDelete(User $user): void
    {
        $tenant = auth()->user()?->tenant;

        abort_unless($tenant, 403);
        abort_if($user->id === auth()->id(), 422, 'You cannot delete your own account.');
        abort_if((int) $tenant->owner_user_id === (int) $user->id, 422, 'The primary owner account cannot be deleted.');
        abort_unless(in_array($user->role, $this->manageableRoles(), true), 403);
    }
}
