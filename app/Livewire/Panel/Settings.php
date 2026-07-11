<?php

namespace App\Livewire\Panel;

use App\Models\Tenant;
use App\Support\TenantPermissions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    private const CURRENCIES = ['USD', 'AED', 'SAR', 'QAR', 'KWD', 'BHD', 'OMR', 'EGP', 'EUR', 'GBP'];

    public string $activeSection = 'identity';

    public string $business_name = '';

    public string $name = '';

    public string $email = '';

    public $logo = null;

    public string $phone = '';

    public string $whatsapp_number = '';

    public string $address = '';

    public string $google_maps_url = '';

    public string $facebook_url = '';

    public string $instagram_url = '';

    public string $snapchat_url = '';

    public string $youtube_url = '';

    public string $tiktok_url = '';

    public string $x_url = '';

    public string $hero_title = '';

    public string $hero_subtitle = '';

    public $hero_image = null;

    public string $about_content = '';

    public string $footer_content = '';

    public string $currency_code = 'USD';

    public bool $show_whatsapp_button = true;

    public bool $show_call_button = true;

    public bool $show_location_button = true;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'settings.update'), 403);

        $user = auth()->user();
        $tenant = $this->tenant();

        $this->business_name = (string) $tenant->business_name;
        $this->name = (string) $user?->name;
        $this->email = (string) $user?->email;
        $this->phone = (string) $tenant->phone;
        $this->whatsapp_number = (string) $tenant->whatsapp_number;
        $this->address = (string) $tenant->address;
        $this->google_maps_url = (string) $tenant->google_maps_url;
        $this->facebook_url = (string) $tenant->facebook_url;
        $this->instagram_url = (string) $tenant->instagram_url;
        $this->snapchat_url = (string) $tenant->snapchat_url;
        $this->youtube_url = (string) $tenant->youtube_url;
        $this->tiktok_url = (string) $tenant->tiktok_url;
        $this->x_url = (string) $tenant->x_url;
        $this->hero_title = (string) $tenant->hero_title;
        $this->hero_subtitle = (string) $tenant->hero_subtitle;
        $this->about_content = (string) $tenant->about_content;
        $this->footer_content = (string) $tenant->footer_content;
        $this->currency_code = $tenant->currency_code ?: 'USD';
        $this->show_whatsapp_button = (bool) $tenant->show_whatsapp_button;
        $this->show_call_button = (bool) $tenant->show_call_button;
        $this->show_location_button = (bool) $tenant->show_location_button;
    }

    public function render(): View
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'settings.update'), 403);

        return view('livewire.panel.settings', [
            'tenant' => $this->tenant(),
            'currencies' => self::CURRENCIES,
        ])->layout('components.layouts.panel', [
            'title' => 'Settings',
        ]);
    }

    public function showSection(string $section): void
    {
        if (in_array($section, ['identity', 'website', 'contact', 'actions', 'security'], true)) {
            $this->activeSection = $section;
        }
    }

    public function saveIdentity(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'settings.update'), 403);

        $user = auth()->user();
        abort_unless($user, 403);

        $validated = $this->validate([
            'business_name' => ['required', 'string', 'max:120'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $tenantData = ['business_name' => $validated['business_name']];

        if ($this->logo) {
            $this->deleteUpload($this->tenant()->logo_path);
            $tenantData['logo_path'] = $this->logo->store($this->tenantUploadDirectory('logos'), 'public_uploads');
        }

        $this->tenant()->update($tenantData);
        $this->logo = null;

        session()->flash('success', 'Business settings updated.');
    }

    public function saveWebsite(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'settings.update'), 403);

        $validated = $this->validate($this->websiteRules());
        unset($validated['hero_image']);

        if ($this->hero_image) {
            $this->deleteUpload($this->tenant()->hero_image_path);
            $validated['hero_image_path'] = $this->hero_image->store($this->tenantUploadDirectory('website'), 'public_uploads');
        }

        $this->tenant()->update($validated);
        $this->hero_image = null;

        session()->flash('success', 'Website settings updated.');
    }

    public function savePassword(): void
    {
        abort_unless(TenantPermissions::can(auth()->user(), 'settings.update'), 403);

        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()?->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('success', 'Password updated.');
    }

    private function websiteRules(): array
    {
        return [
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
            'hero_title' => ['nullable', 'string', 'max:140'],
            'hero_subtitle' => ['nullable', 'string', 'max:280'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=3000,max_height=3000'],
            'about_content' => ['nullable', 'string', 'max:4000'],
            'footer_content' => ['nullable', 'string', 'max:2000'],
            'currency_code' => ['required', Rule::in(self::CURRENCIES)],
            'show_whatsapp_button' => ['boolean'],
            'show_call_button' => ['boolean'],
            'show_location_button' => ['boolean'],
        ];
    }

    private function tenant(): Tenant
    {
        $tenant = auth()->user()?->tenant()->first();

        abort_unless($tenant, 403);

        return $tenant;
    }

    private function tenantUploadDirectory(string $directory): string
    {
        return 'tenants/'.$this->tenant()->id.'/'.$directory;
    }

    private function deleteUpload(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http://') && ! str_starts_with($path, 'https://')) {
            Storage::disk('public_uploads')->delete($path);
        }
    }
}
