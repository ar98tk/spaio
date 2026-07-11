<?php

namespace App\Http\Middleware;

use App\Support\TenantPermissions;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'tenant_id' => $user->tenant_id,
                    'permissions' => $user->permissions ?? [],
                ] : null,
                'tenant' => $user?->tenant ? [
                    'id' => $user->tenant->id,
                    'business_name' => $user->tenant->business_name,
                    'slug' => $user->tenant->slug,
                    'subdomain' => $user->tenant->subdomain,
                    'logo_path' => $user->tenant->logo_path,
                    'phone' => $user->tenant->phone,
                    'whatsapp_number' => $user->tenant->whatsapp_number,
                    'address' => $user->tenant->address,
                    'google_maps_url' => $user->tenant->google_maps_url,
                    'facebook_url' => $user->tenant->facebook_url,
                    'instagram_url' => $user->tenant->instagram_url,
                    'snapchat_url' => $user->tenant->snapchat_url,
                    'youtube_url' => $user->tenant->youtube_url,
                    'tiktok_url' => $user->tenant->tiktok_url,
                    'x_url' => $user->tenant->x_url,
                    'hero_title' => $user->tenant->hero_title,
                    'hero_subtitle' => $user->tenant->hero_subtitle,
                    'hero_image_path' => $user->tenant->hero_image_path,
                    'about_content' => $user->tenant->about_content,
                    'footer_content' => $user->tenant->footer_content,
                    'show_whatsapp_button' => $user->tenant->show_whatsapp_button,
                    'show_call_button' => $user->tenant->show_call_button,
                    'show_location_button' => $user->tenant->show_location_button,
                    'website_template_id' => $user->tenant->website_template_id,
                    'currency_code' => $user->tenant->currency_code,
                    'website_template' => $user->tenant->websiteTemplate ? [
                        'id' => $user->tenant->websiteTemplate->id,
                        'name' => $user->tenant->websiteTemplate->name,
                        'slug' => $user->tenant->websiteTemplate->slug,
                    ] : null,
                    'status' => $user->tenant->status,
                ] : null,
                'access' => TenantPermissions::map($user),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}
