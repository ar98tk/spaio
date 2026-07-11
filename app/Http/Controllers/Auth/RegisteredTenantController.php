<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Models\WebsiteTemplate;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredTenantController extends Controller
{
    private const IMAGE_RULES = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'];

    public function create(): Response
    {
        return Inertia::render('Auth/Register', [
            'templates' => WebsiteTemplate::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'description', 'preview_image_path']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'logo' => self::IMAGE_RULES,
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'whatsapp_number' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'google_maps_url' => ['nullable', 'url', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'snapchat_url' => ['nullable', 'url', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'tiktok_url' => ['nullable', 'url', 'max:500'],
            'x_url' => ['nullable', 'url', 'max:500'],
            'website_template_id' => ['nullable', 'exists:website_templates,id'],
            'currency_code' => ['nullable', Rule::in(['USD', 'AED', 'SAR', 'QAR', 'KWD', 'BHD', 'OMR', 'EGP', 'EUR', 'GBP'])],
            'show_whatsapp_button' => ['nullable'],
            'show_call_button' => ['nullable'],
            'show_location_button' => ['nullable'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        [$tenant, $user] = DB::transaction(function () use ($request, $validated) {
            $slug = $this->uniqueTenantSlug($validated['business_name']);
            $plan = Plan::query()->where('slug', 'starter')->first();
            $template = isset($validated['website_template_id'])
                ? WebsiteTemplate::query()->where('is_active', true)->find($validated['website_template_id'])
                : null;

            $template ??= WebsiteTemplate::query()->where('is_active', true)->orderBy('sort_order')->first();

            $tenantData = [
                'business_name' => $validated['business_name'],
                'slug' => $slug,
                'subdomain' => $slug,
                'status' => 'active',
                'phone' => $validated['phone'] ?? null,
                'whatsapp_number' => $validated['whatsapp_number'] ?? null,
                'address' => $validated['address'] ?? null,
                'google_maps_url' => $validated['google_maps_url'] ?? null,
                'facebook_url' => $validated['facebook_url'] ?? null,
                'instagram_url' => $validated['instagram_url'] ?? null,
                'snapchat_url' => $validated['snapchat_url'] ?? null,
                'youtube_url' => $validated['youtube_url'] ?? null,
                'tiktok_url' => $validated['tiktok_url'] ?? null,
                'x_url' => $validated['x_url'] ?? null,
                'website_template_id' => $template?->id,
                'currency_code' => $validated['currency_code'] ?? 'USD',
                'show_whatsapp_button' => $request->boolean('show_whatsapp_button', true),
                'show_call_button' => $request->boolean('show_call_button', true),
                'show_location_button' => $request->boolean('show_location_button', true),
            ];

            if ($request->hasFile('logo')) {
                $tenantData['logo_path'] = $request->file('logo')->store('logos', 'public_uploads');
            }

            $tenant = Tenant::create($tenantData);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'owner',
            ]);

            $tenant->update(['owner_user_id' => $user->id]);

            if ($plan) {
                Subscription::create([
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'status' => 'trialing',
                    'billing_cycle' => 'monthly',
                    'trial_ends_at' => now()->addDays(10),
                    'current_period_ends_at' => now()->addDays(10),
                ]);
            }

            return [$tenant, $user];
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('panel.index')->with('success', "Welcome to Spaio. {$tenant->business_name} is ready.");
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
}
