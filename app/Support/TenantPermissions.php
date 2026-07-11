<?php

namespace App\Support;

use App\Models\User;

class TenantPermissions
{
    public const RECEPTION_ACCESS = 'reception.access';

    public const PERMISSIONS = [
        'services.create',
        'services.update',
        'services.delete',
        'packages.create',
        'packages.update',
        'packages.delete',
        'staff.create',
        'staff.update',
        'staff.delete',
        'gallery.create',
        'gallery.update',
        'gallery.delete',
        'users.create',
        'users.update',
        'users.delete',
        'bookings.create',
        'bookings.update',
        'bookings.delete',
        'analytics.view',
        'settings.update',
        'templates.change',
        'billing.control',
    ];

    public const GROUPS = [
        'services' => ['services.create', 'services.update', 'services.delete'],
        'packages' => ['packages.create', 'packages.update', 'packages.delete'],
        'staff' => ['staff.create', 'staff.update', 'staff.delete'],
        'gallery' => ['gallery.create', 'gallery.update', 'gallery.delete'],
        'users' => ['users.create', 'users.update', 'users.delete'],
        'bookings' => ['bookings.create', 'bookings.update', 'bookings.delete'],
        'analytics' => ['analytics.view'],
        'settings' => ['settings.update'],
        'templates' => ['templates.change'],
        'billing' => ['billing.control'],
        'reception' => [self::RECEPTION_ACCESS, 'bookings.create'],
    ];

    public static function all(): array
    {
        return self::PERMISSIONS;
    }

    public static function can(?User $user, string $permission): bool
    {
        if (! $user) {
            return false;
        }

        if (in_array($user->role, ['owner', 'super_admin'], true)) {
            return true;
        }

        if ($user->role === 'receptionist') {
            return $permission === self::RECEPTION_ACCESS;
        }

        return in_array($permission, $user->permissions ?? [], true);
    }

    public static function canAny(?User $user, array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (self::can($user, $permission)) {
                return true;
            }
        }

        return false;
    }

    public static function group(?User $user, string $group): bool
    {
        return self::canAny($user, self::GROUPS[$group] ?? []);
    }

    public static function map(?User $user): array
    {
        $permissions = array_fill_keys(self::PERMISSIONS, false);
        $permissions[self::RECEPTION_ACCESS] = false;

        foreach (array_keys($permissions) as $permission) {
            $permissions[$permission] = self::can($user, $permission);
        }

        return [
            'permissions' => $permissions,
            'groups' => collect(array_keys(self::GROUPS))
                ->mapWithKeys(fn (string $group) => [$group => self::group($user, $group)])
                ->all(),
        ];
    }
}
