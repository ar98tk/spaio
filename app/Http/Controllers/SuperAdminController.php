<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlatformSetting;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteTemplate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class SuperAdminController extends Controller
{
    public function dashboard(): Response
    {
        return Inertia::render('SuperAdmin/Dashboard', [
            'stats' => [
                'tenants' => Tenant::query()->count(),
                'users' => User::query()->count(),
                'trialing' => Tenant::query()->whereHas('subscription', fn ($query) => $query->where('status', 'trialing'))->count(),
                'active' => Tenant::query()->where('status', 'active')->count(),
                'plans' => Plan::query()->count(),
                'subscriptions' => Subscription::query()->count(),
                'templates' => WebsiteTemplate::query()->count(),
            ],
            'tenants' => Tenant::query()
                ->with(['owner', 'subscription.plan'])
                ->latest()
                ->limit(8)
                ->get(),
            'plans' => Plan::query()->orderBy('sort_order')->get(),
            'subscriptionsByStatus' => Subscription::query()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function tenants(): Response
    {
        return Inertia::render('SuperAdmin/Tenants', [
            'tenants' => Tenant::query()
                ->with(['owner', 'subscription.plan'])
                ->latest()
                ->paginate(20),
            'plans' => Plan::query()->orderBy('sort_order')->get(['id', 'name']),
        ]);
    }

    public function users(): Response
    {
        return Inertia::render('SuperAdmin/Users', [
            'users' => User::query()
                ->with('tenant')
                ->latest()
                ->paginate(20),
            'tenants' => Tenant::query()->orderBy('business_name')->get(['id', 'business_name']),
        ]);
    }

    public function tenant(Tenant $tenant): Response
    {
        return Inertia::render('SuperAdmin/TenantDetail', [
            'tenant' => $tenant->load(['owner', 'users', 'subscription.plan']),
        ]);
    }

    public function plans(): Response
    {
        return Inertia::render('SuperAdmin/Plans', [
            'plans' => Plan::query()
                ->withCount('subscriptions')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function subscriptions(): Response
    {
        return Inertia::render('SuperAdmin/Subscriptions', [
            'subscriptions' => Subscription::query()
                ->with(['tenant.owner', 'plan'])
                ->latest()
                ->paginate(20),
            'tenants' => Tenant::query()->orderBy('business_name')->get(['id', 'business_name']),
            'plans' => Plan::query()->orderBy('sort_order')->get(['id', 'name']),
            'summary' => [
                'trialing' => Subscription::query()->where('status', 'trialing')->count(),
                'active' => Subscription::query()->where('status', 'active')->count(),
                'past_due' => Subscription::query()->where('status', 'past_due')->count(),
                'cancelled' => Subscription::query()->where('status', 'cancelled')->count(),
            ],
        ]);
    }

    public function templates(): Response
    {
        return Inertia::render('SuperAdmin/Templates', [
            'templates' => WebsiteTemplate::query()
                ->withCount('tenants')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function storeTenant(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'subdomain' => ['nullable', 'string', 'max:80', 'alpha_dash', 'unique:tenants,subdomain'],
            'status' => ['required', Rule::in(['active', 'paused', 'cancelled'])],
            'plan_id' => ['nullable', 'exists:plans,id'],
        ]);

        $subdomain = $validated['subdomain'] ?: $this->uniqueTenantSlug($validated['business_name']);
        $template = WebsiteTemplate::query()->where('is_active', true)->orderBy('sort_order')->first();

        $tenant = Tenant::create([
            'business_name' => $validated['business_name'],
            'slug' => $subdomain,
            'subdomain' => $subdomain,
            'status' => $validated['status'],
            'website_template_id' => $template?->id,
        ]);

        if (! empty($validated['plan_id'])) {
            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $validated['plan_id'],
                'status' => 'trialing',
                'billing_cycle' => 'monthly',
                'trial_ends_at' => now()->addDays(10),
                'current_period_ends_at' => now()->addDays(10),
            ]);
        }

        return back()->with('success', 'Tenant created.');
    }

    public function updateTenant(Request $request, Tenant $tenant): RedirectResponse
    {
        $oldStatus = $tenant->status;

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'subdomain' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('tenants', 'subdomain')->ignore($tenant->id)],
            'status' => ['required', Rule::in(['active', 'paused', 'cancelled'])],
        ]);

        $tenant->update([
            'business_name' => $validated['business_name'],
            'slug' => $validated['subdomain'],
            'subdomain' => $validated['subdomain'],
            'status' => $validated['status'],
        ]);

        if ($oldStatus !== $tenant->status) {
            return back()->with('success', "Tenant status changed to {$tenant->status}.");
        }

        return back()->with('success', 'Tenant updated.');
    }

    public function destroyTenant(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return back()->with('success', 'Tenant deleted.');
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['nullable', 'required_unless:role,super_admin', 'exists:tenants,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in(['super_admin', 'owner', 'staff'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'tenant_id' => $validated['role'] === 'super_admin' ? null : $validated['tenant_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        if ($user->role === 'owner' && $user->tenant && ! $user->tenant->owner_user_id) {
            $user->tenant->update(['owner_user_id' => $user->id]);
        }

        return back()->with('success', 'User created.');
    }

    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['nullable', 'required_unless:role,super_admin', 'exists:tenants,id'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'owner', 'staff'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'super_admin') {
            return back()->with('error', 'You cannot remove your own super admin access.');
        }

        if ($user->role === 'super_admin' && $validated['role'] !== 'super_admin' && User::query()->where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'At least one super admin account is required.');
        }

        $user->update([
            'tenant_id' => $validated['role'] === 'super_admin' ? null : $validated['tenant_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            ...(! empty($validated['password']) ? ['password' => Hash::make($validated['password'])] : []),
        ]);

        return back()->with('success', 'User updated.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'super_admin' && User::query()->where('role', 'super_admin')->count() <= 1) {
            return back()->with('error', 'At least one super admin account is required.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    public function storePlan(Request $request): RedirectResponse
    {
        $validated = $this->validatePlan($request);

        Plan::create($validated);

        return back()->with('success', 'Plan created.');
    }

    public function updatePlan(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $this->validatePlan($request, $plan);

        $plan->update($validated);

        return back()->with('success', 'Plan updated.');
    }

    public function destroyPlan(Plan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->exists()) {
            return back()->with('error', 'Plans with subscriptions cannot be deleted.');
        }

        $plan->delete();

        return back()->with('success', 'Plan deleted.');
    }

    public function storeSubscription(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => ['required', 'exists:tenants,id', 'unique:subscriptions,tenant_id'],
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', Rule::in(['trialing', 'active', 'past_due', 'cancelled'])],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'current_period_ends_at' => ['nullable', 'date'],
        ]);

        Subscription::create([
            ...$validated,
            'trial_ends_at' => $validated['status'] === 'trialing' ? now()->addDays(10) : null,
            'current_period_ends_at' => ($validated['current_period_ends_at'] ?? null) ?: now()->addMonth(),
        ]);

        return back()->with('success', 'Subscription created.');
    }

    public function updateSubscription(Request $request, Subscription $subscription): RedirectResponse
    {
        $oldStatus = $subscription->status;

        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
            'status' => ['required', Rule::in(['trialing', 'active', 'past_due', 'cancelled'])],
            'billing_cycle' => ['required', Rule::in(['monthly', 'yearly'])],
            'current_period_ends_at' => ['nullable', 'date'],
        ]);

        $subscription->update([
            ...$validated,
            'current_period_ends_at' => ($validated['current_period_ends_at'] ?? null) ?: null,
        ]);

        if ($oldStatus !== $subscription->status) {
            return back()->with('success', "Subscription status changed to {$subscription->status}.");
        }

        return back()->with('success', 'Subscription updated.');
    }

    public function destroySubscription(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return back()->with('success', 'Subscription deleted.');
    }

    public function storeTemplate(Request $request): RedirectResponse
    {
        WebsiteTemplate::create($this->validateTemplate($request));

        return back()->with('success', 'Template created.');
    }

    public function updateTemplate(Request $request, WebsiteTemplate $websiteTemplate): RedirectResponse
    {
        $wasActive = $websiteTemplate->is_active;

        $websiteTemplate->update($this->validateTemplate($request, $websiteTemplate));

        if ($wasActive !== $websiteTemplate->is_active) {
            return back()->with('success', $websiteTemplate->is_active ? 'Template activated and visible to tenants.' : 'Template hidden from tenants.');
        }

        return back()->with('success', 'Template updated.');
    }

    public function destroyTemplate(WebsiteTemplate $websiteTemplate): RedirectResponse
    {
        if ($websiteTemplate->tenants()->exists()) {
            return back()->with('error', 'Templates assigned to tenants cannot be deleted. Deactivate it instead.');
        }

        $websiteTemplate->delete();

        return back()->with('success', 'Template deleted.');
    }

    public function marketingSettings(): Response
    {
        return Inertia::render('SuperAdmin/MarketingSettings', [
            'settings' => $this->settingsForGroup('marketing'),
        ]);
    }

    public function updateMarketingSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:160'],
            'hero_subtitle' => ['required', 'string', 'max:300'],
            'primary_cta' => ['required', 'string', 'max:60'],
            'secondary_cta' => ['required', 'string', 'max:60'],
            'footer_note' => ['nullable', 'string', 'max:300'],
        ]);

        foreach ($validated as $key => $value) {
            PlatformSetting::updateOrCreate(
                ['group' => 'marketing', 'key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Marketing website settings updated.');
    }

    public function platformSettings(): Response
    {
        return Inertia::render('SuperAdmin/PlatformSettings', [
            'settings' => $this->settingsForGroup('platform'),
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    private function settingsForGroup(string $group): array
    {
        return PlatformSetting::query()
            ->where('group', $group)
            ->pluck('value', 'key')
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePlan(Request $request, ?Plan $plan = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'slug' => ['nullable', 'string', 'max:80', 'alpha_dash', Rule::unique('plans', 'slug')->ignore($plan?->id)],
            'description' => ['required', 'string', 'max:300'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'features_text' => ['required', 'string'],
            'is_featured' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: $this->uniquePlanSlug($validated['name'], $plan),
            'description' => $validated['description'],
            'monthly_price' => $validated['monthly_price'],
            'yearly_price' => $validated['yearly_price'],
            'features' => collect(preg_split('/\r\n|\r|\n/', $validated['features_text']))
                ->map(fn ($feature) => trim($feature))
                ->filter()
                ->values()
                ->all(),
            'is_featured' => (bool) ($validated['is_featured'] ?? false),
            'sort_order' => $validated['sort_order'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateTemplate(Request $request, ?WebsiteTemplate $template = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', Rule::unique('website_templates', 'slug')->ignore($template?->id)],
            'component' => ['required', Rule::in(['Tenant/FirstTemplate', 'Tenant/SecondTemplate', 'Tenant/ThirdTemplate', 'Tenant/FourthTemplate', 'Tenant/FifthTemplate', 'Tenant/SixthTemplate', 'Tenant/SeventhTemplate', 'Tenant/EighthTemplate', 'Tenant/NinthTemplate', 'Tenant/TenthTemplate', 'Tenant/EleventhTemplate', 'Tenant/TwelfthTemplate'])],
            'description' => ['nullable', 'string', 'max:300'],
            'preview_image_path' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        return [
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: $this->uniqueTemplateSlug($validated['name'], $template),
            'component' => $validated['component'],
            'description' => $validated['description'] ?? null,
            'preview_image_path' => $validated['preview_image_path'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $validated['sort_order'],
        ];
    }

    private function uniqueTenantSlug(string $businessName): string
    {
        $base = Str::slug($businessName) ?: 'spa';
        $slug = $base;
        $counter = 2;

        while (Tenant::query()->where('slug', $slug)->orWhere('subdomain', $slug)->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function uniquePlanSlug(string $name, ?Plan $plan = null): string
    {
        $base = Str::slug($name) ?: 'plan';
        $slug = $base;
        $counter = 2;

        while (Plan::query()
            ->where('slug', $slug)
            ->when($plan, fn ($query) => $query->whereKeyNot($plan->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    private function uniqueTemplateSlug(string $name, ?WebsiteTemplate $template = null): string
    {
        $base = Str::slug($name) ?: 'template';
        $slug = $base;
        $counter = 2;

        while (WebsiteTemplate::query()
            ->where('slug', $slug)
            ->when($template, fn ($query) => $query->whereKeyNot($template->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
