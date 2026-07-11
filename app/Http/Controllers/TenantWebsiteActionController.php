<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantWebsiteActionEvent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class TenantWebsiteActionController extends Controller
{
    public function store(Request $request, Tenant $tenant): Response
    {
        abort_unless($tenant->status === 'active', 404);

        $this->record($request, $tenant);

        return response()->noContent();
    }

    public function storeBySubdomain(Request $request, string $tenantSubdomain): Response
    {
        $tenant = Tenant::query()
            ->where('subdomain', $tenantSubdomain)
            ->where('status', 'active')
            ->firstOrFail();

        $this->record($request, $tenant);

        return response()->noContent();
    }

    private function record(Request $request, Tenant $tenant): void
    {
        $validated = $request->validate([
            'action_type' => ['required', 'string', Rule::in(TenantWebsiteActionEvent::TYPES)],
            'context' => ['nullable', 'string', 'max:100'],
            'label' => ['nullable', 'string', 'max:255'],
            'target_url' => ['nullable', 'string', 'max:1000'],
        ]);

        TenantWebsiteActionEvent::create([
            ...$validated,
            'tenant_id' => $tenant->id,
            'ip_address' => $request->ip(),
            'user_agent' => str($request->userAgent() ?? '')->limit(500, '')->toString(),
        ]);
    }
}
