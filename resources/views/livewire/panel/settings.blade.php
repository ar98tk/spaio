@php
    $uploadUrl = static function (?string $path): ?string {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : '/uploads/'.$path;
    };

    $tabs = [
        ['key' => 'identity', 'label' => 'Identity', 'icon' => 'ki-shop', 'description' => 'Name, owner, logo'],
        ['key' => 'website', 'label' => 'Website', 'icon' => 'ki-picture', 'description' => 'Hero, about, footer'],
        ['key' => 'contact', 'label' => 'Contact', 'icon' => 'ki-geolocation', 'description' => 'Phone, social, currency'],
        ['key' => 'actions', 'label' => 'Actions', 'icon' => 'ki-mouse-square', 'description' => 'Website buttons'],
        ['key' => 'security', 'label' => 'Security', 'icon' => 'ki-lock-2', 'description' => 'Password'],
    ];
@endphp

<div>
    <div class="card card-flush mb-6">
        <div class="card-body p-7">
            <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between gap-6">
                <div class="d-flex align-items-center gap-5">
                    <div class="symbol symbol-70px">
                        @if ($uploadUrl($tenant->logo_path))
                            <img src="{{ $uploadUrl($tenant->logo_path) }}" alt="{{ $tenant->business_name }}" class="object-fit-contain">
                        @else
                            <span class="symbol-label bg-light-primary">
                                <i class="ki-duotone ki-shop fs-2x text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="badge badge-light-primary mb-2">Business settings</span>
                        <h2 class="fs-2 fw-bold text-gray-900 mb-1">{{ $business_name ?: 'Tenant settings' }}</h2>
                        <div class="text-gray-600">{{ $tenant->subdomain }}.spaio.test</div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-8">
                    <div>
                        <div class="fs-3 fw-bold text-gray-900">{{ $currency_code }}</div>
                        <div class="fs-8 text-gray-500 text-uppercase">Currency</div>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-gray-900">{{ $tenant->logo_path ? 'Custom' : 'Default' }}</div>
                        <div class="fs-8 text-gray-500 text-uppercase">Logo</div>
                    </div>
                    <div>
                        <div class="fs-3 fw-bold text-gray-900">{{ collect([$show_whatsapp_button, $show_call_button, $show_location_button])->filter()->count() }}/3</div>
                        <div class="fs-8 text-gray-500 text-uppercase">Actions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-6">
        @foreach ($tabs as $tab)
            <div class="col-md-6 col-xl">
                <button type="button" wire:click="showSection('{{ $tab['key'] }}')" class="btn w-100 h-100 text-start border {{ $activeSection === $tab['key'] ? 'btn-primary border-primary' : 'btn-light border-gray-300' }} p-4">
                    <div class="d-flex align-items-center gap-3">
                        <span class="symbol symbol-40px">
                            <span class="symbol-label {{ $activeSection === $tab['key'] ? 'bg-white bg-opacity-25' : 'bg-light-primary' }}">
                                <i class="ki-duotone {{ $tab['icon'] }} fs-2 {{ $activeSection === $tab['key'] ? 'text-white' : 'text-primary' }}"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            </span>
                        </span>
                        <span>
                            <span class="d-block fw-bold">{{ $tab['label'] }}</span>
                            <span class="d-block fs-8 {{ $activeSection === $tab['key'] ? 'text-white opacity-75' : 'text-gray-600' }}">{{ $tab['description'] }}</span>
                        </span>
                    </div>
                </button>
            </div>
        @endforeach
    </div>

    @if ($activeSection === 'identity')
        <form wire:submit="saveIdentity">
            <div class="row g-5">
                <div class="col-xl-8">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title fw-bold text-gray-900">Business identity</h3>
                                <div class="text-gray-600 fs-7">Update the business name and owner login details.</div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                <div class="col-12">
                                    <label class="form-label required">Business name</label>
                                    <input type="text" class="form-control" wire:model="business_name" placeholder="Dubai Glow Spa">
                                    @error('business_name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Owner name</label>
                                    <input type="text" class="form-control" wire:model="name" placeholder="Alya Hassan">
                                    @error('name') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label required">Email address</label>
                                    <input type="email" class="form-control" wire:model="email" placeholder="owner@example.com">
                                    @error('email') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveIdentity,logo">
                                <span wire:loading.remove wire:target="saveIdentity">Save identity</span>
                                <span wire:loading wire:target="saveIdentity">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card card-flush h-100">
                        <div class="card-body p-7">
                            <h3 class="fw-bold text-gray-900 mb-5">Logo</h3>
                            <div class="symbol symbol-150px mb-5">
                                @if ($logo)
                                    <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="object-fit-contain">
                                @elseif ($uploadUrl($tenant->logo_path))
                                    <img src="{{ $uploadUrl($tenant->logo_path) }}" alt="Current logo" class="object-fit-contain">
                                @else
                                    <img src="/default.png" alt="Default logo" class="object-fit-contain">
                                @endif
                            </div>
                            <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="logo">
                            <div wire:loading wire:target="logo" class="text-primary fs-7 mt-2">Uploading logo...</div>
                            @error('logo') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            <div class="text-gray-600 fs-7 mt-3">JPG, PNG, or WebP. Maximum 2 MB and 3000px per side.</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    @if ($activeSection === 'website')
        <form wire:submit="saveWebsite">
            <div class="row g-5">
                <div class="col-xl-7">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <div>
                                <h3 class="card-title fw-bold text-gray-900">Website content</h3>
                                <div class="text-gray-600 fs-7">This copy follows the tenant across website templates.</div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                <div class="col-12">
                                    <label class="form-label">Hero title</label>
                                    <input type="text" class="form-control" wire:model="hero_title" placeholder="Luxury wellness, tailored for you">
                                    @error('hero_title') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Hero subtitle</label>
                                    <textarea rows="3" class="form-control" wire:model="hero_subtitle" placeholder="A short promise shown below the website hero title."></textarea>
                                    @error('hero_subtitle') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">About section</label>
                                    <textarea rows="5" class="form-control" wire:model="about_content" placeholder="Describe the spa, atmosphere, experience, and what makes it special."></textarea>
                                    @error('about_content') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Footer content</label>
                                    <textarea rows="3" class="form-control" wire:model="footer_content" placeholder="Short closing text shown in the website footer."></textarea>
                                    @error('footer_content') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveWebsite,hero_image">
                                <span wire:loading.remove wire:target="saveWebsite">Save website content</span>
                                <span wire:loading wire:target="saveWebsite">Saving...</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="card card-flush h-100">
                        <div class="card-body p-7">
                            <h3 class="fw-bold text-gray-900 mb-5">Hero background</h3>
                            <div class="rounded border overflow-hidden bg-light mb-5" style="aspect-ratio: 16 / 9;">
                                @if ($hero_image)
                                    <img src="{{ $hero_image->temporaryUrl() }}" alt="Hero preview" class="w-100 h-100 object-fit-cover">
                                @elseif ($uploadUrl($tenant->hero_image_path))
                                    <img src="{{ $uploadUrl($tenant->hero_image_path) }}" alt="Current hero background" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="d-flex align-items-center justify-content-center h-100">
                                        <i class="ki-duotone ki-picture fs-3x text-primary"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                @endif
                            </div>
                            <input type="file" class="form-control" accept=".jpg,.jpeg,.png,.webp" wire:model="hero_image">
                            <div wire:loading wire:target="hero_image" class="text-primary fs-7 mt-2">Uploading hero image...</div>
                            @error('hero_image') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            <div class="text-gray-600 fs-7 mt-3">Use a wide, high-quality spa image. Maximum 2 MB.</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    @if ($activeSection === 'contact')
        <form wire:submit="saveWebsite">
            <div class="row g-5">
                <div class="col-xl-6">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h3 class="card-title fw-bold text-gray-900">Contact and location</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-5">
                                <div class="col-md-6">
                                    <label class="form-label">Phone number</label>
                                    <input type="text" class="form-control" wire:model="phone" placeholder="+971 50 000 0000">
                                    @error('phone') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">WhatsApp number</label>
                                    <input type="text" class="form-control" wire:model="whatsapp_number" placeholder="+971 50 000 0000">
                                    @error('whatsapp_number') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <input type="text" class="form-control" wire:model="address" placeholder="Business Bay, Dubai, UAE">
                                    @error('address') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Google Maps URL</label>
                                    <input type="url" class="form-control" wire:model="google_maps_url" placeholder="https://maps.google.com/...">
                                    @error('google_maps_url') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label required">Website currency</label>
                                    <select class="form-select" wire:model="currency_code">
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency }}">{{ $currency }}</option>
                                        @endforeach
                                    </select>
                                    @error('currency_code') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                    <div class="text-gray-600 fs-7 mt-2">Used on public prices, reception, bookings, and dashboard summaries.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card card-flush h-100">
                        <div class="card-header">
                            <h3 class="card-title fw-bold text-gray-900">Social media</h3>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row g-4">
                                @foreach ([
                                    'facebook_url' => ['Facebook URL', 'https://facebook.com/your-spa'],
                                    'instagram_url' => ['Instagram URL', 'https://instagram.com/your-spa'],
                                    'snapchat_url' => ['Snapchat URL', 'https://snapchat.com/add/your-spa'],
                                    'youtube_url' => ['YouTube URL', 'https://youtube.com/@your-spa'],
                                    'tiktok_url' => ['TikTok URL', 'https://tiktok.com/@your-spa'],
                                    'x_url' => ['X / Twitter URL', 'https://x.com/your-spa'],
                                ] as $field => [$label, $placeholder])
                                    <div class="col-md-6">
                                        <label class="form-label">{{ $label }}</label>
                                        <input type="url" class="form-control" wire:model="{{ $field }}" placeholder="{{ $placeholder }}">
                                        @error($field) <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveWebsite">Save contact settings</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    @if ($activeSection === 'actions')
        <form wire:submit="saveWebsite">
            <div class="card card-flush">
                <div class="card-header">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900">Card action buttons</h3>
                        <div class="text-gray-600 fs-7">Control which buttons appear on every service and package card.</div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        @foreach ([
                            'show_whatsapp_button' => ['WhatsApp button', 'Shows a WhatsApp action. Requires a WhatsApp number.', 'ki-whatsapp'],
                            'show_call_button' => ['Call button', 'Shows a direct call action. Requires a phone number.', 'ki-phone'],
                            'show_location_button' => ['Location button', 'Shows a map action. Requires a Google Maps URL.', 'ki-geolocation'],
                        ] as $field => [$label, $description, $icon])
                            <div class="col-md-4">
                                <label class="card card-flush border h-100 cursor-pointer">
                                    <div class="card-body p-5">
                                        <div class="d-flex align-items-start justify-content-between gap-4">
                                            <span class="symbol symbol-45px">
                                                <span class="symbol-label bg-light-primary">
                                                    <i class="ki-duotone {{ $icon }} fs-2 text-primary"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                </span>
                                            </span>
                                            <span class="form-check form-switch form-check-custom form-check-solid">
                                                <input class="form-check-input" type="checkbox" wire:model="{{ $field }}">
                                            </span>
                                        </div>
                                        <div class="fw-bold text-gray-900 fs-5 mt-5">{{ $label }}</div>
                                        <div class="text-gray-600 fs-7 mt-1">{{ $description }}</div>
                                    </div>
                                </label>
                                @error($field) <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="saveWebsite">Save action buttons</button>
                </div>
            </div>
        </form>
    @endif

    @if ($activeSection === 'security')
        <form wire:submit="savePassword">
            <div class="card card-flush mw-750px">
                <div class="card-header">
                    <div>
                        <h3 class="card-title fw-bold text-gray-900">Password</h3>
                        <div class="text-gray-600 fs-7">Update the password used to access this dashboard.</div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row g-5">
                        <div class="col-12">
                            <label class="form-label required">Current password</label>
                            <input type="password" class="form-control" wire:model="current_password">
                            @error('current_password') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">New password</label>
                            <input type="password" class="form-control" wire:model="password">
                            @error('password') <div class="text-danger fs-7 mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required">Confirm password</label>
                            <input type="password" class="form-control" wire:model="password_confirmation">
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="savePassword">
                        <span wire:loading.remove wire:target="savePassword">Update password</span>
                        <span wire:loading wire:target="savePassword">Updating...</span>
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
