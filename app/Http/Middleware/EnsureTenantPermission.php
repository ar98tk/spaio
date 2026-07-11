<?php

namespace App\Http\Middleware;

use App\Support\TenantPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantPermission
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (! TenantPermissions::canAny($request->user(), $permissions)) {
            if ($request->expectsJson()) {
                abort(403);
            }

            $request->session()->flash('error', 'You do not have access to that area. Ask the owner to update your permissions.');

            return new RedirectResponse($this->fallbackPath($request));
        }

        return $next($request);
    }

    private function fallbackPath(Request $request): string
    {
        $user = $request->user();

        if ($user?->role === 'receptionist' || TenantPermissions::group($user, 'reception')) {
            return route('panel.reception');
        }

        return route('panel.index');
    }
}
