<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\WebsiteTemplate;
use Inertia\Inertia;
use Inertia\Response;

class TenantWebsiteController extends Controller
{
    public function preview(Tenant $tenant): Response
    {
        abort_unless($tenant->status === 'active', 404);

        return $this->render(
            $tenant,
            route('tenant.preview.booking', $tenant),
            route('tenant.preview.actions', $tenant)
        );
    }

    public function showBySubdomain(string $tenantSubdomain): Response
    {
        $tenant = Tenant::query()
            ->where('subdomain', $tenantSubdomain)
            ->where('status', 'active')
            ->firstOrFail();

        return $this->render(
            $tenant,
            route('tenant.website.booking', ['tenantSubdomain' => $tenantSubdomain]),
            route('tenant.website.actions', ['tenantSubdomain' => $tenantSubdomain])
        );
    }

    private function render(Tenant $tenant, string $bookingEndpoint, string $actionEndpoint): Response
    {
        $template = $this->activeTemplateFor($tenant);

        return Inertia::render($template->component, [
            'template' => [
                'id' => $template->id,
                'name' => $template->name,
                'slug' => $template->slug,
            ],
            'booking_endpoint' => $bookingEndpoint,
            'action_endpoint' => $actionEndpoint,
            'tenant' => [
                'business_name' => $tenant->business_name,
                'subdomain' => $tenant->subdomain,
                'logo_path' => $tenant->logo_path,
                'phone' => $tenant->phone,
                'whatsapp_number' => $tenant->whatsapp_number,
                'address' => $tenant->address,
                'google_maps_url' => $tenant->google_maps_url,
                'facebook_url' => $tenant->facebook_url,
                'instagram_url' => $tenant->instagram_url,
                'snapchat_url' => $tenant->snapchat_url,
                'youtube_url' => $tenant->youtube_url,
                'tiktok_url' => $tenant->tiktok_url,
                'x_url' => $tenant->x_url,
                'hero_title' => $tenant->hero_title,
                'hero_subtitle' => $tenant->hero_subtitle,
                'hero_image_path' => $tenant->hero_image_path,
                'about_content' => $tenant->about_content,
                'footer_content' => $tenant->footer_content,
                'show_whatsapp_button' => $tenant->show_whatsapp_button,
                'show_call_button' => $tenant->show_call_button,
                'show_location_button' => $tenant->show_location_button,
                'currency_code' => $tenant->currency_code ?: 'USD',
            ],
            'services' => $tenant->services()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest()
                ->get(['id', 'name', 'price', 'description', 'image_path']),
            'packages' => $tenant->packages()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest()
                ->get(['id', 'name', 'included_description', 'original_price', 'discounted_price', 'image_path']),
            'staff' => $tenant->staffMembers()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest()
                ->get(['id', 'name', 'role', 'bio', 'photo_path']),
            'gallery' => $tenant->galleryImages()
                ->orderBy('sort_order')
                ->latest()
                ->get(['id', 'title', 'alt_text', 'image_path']),
        ]);
    }

    private function activeTemplateFor(Tenant $tenant): WebsiteTemplate
    {
        $selected = $tenant->website_template_id
            ? WebsiteTemplate::query()->find($tenant->website_template_id)
            : null;

        if ($selected?->is_active) {
            return $selected;
        }

        return WebsiteTemplate::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first()
            ?? new WebsiteTemplate([
                'name' => 'First Template',
                'slug' => 'first-template',
                'component' => 'Tenant/FirstTemplate',
                'is_active' => true,
            ]);
    }
}
