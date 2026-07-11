<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'slug',
        'subdomain',
        'logo_path',
        'phone',
        'whatsapp_number',
        'address',
        'google_maps_url',
        'facebook_url',
        'instagram_url',
        'snapchat_url',
        'youtube_url',
        'tiktok_url',
        'x_url',
        'hero_title',
        'hero_subtitle',
        'hero_image_path',
        'about_content',
        'footer_content',
        'show_whatsapp_button',
        'show_call_button',
        'show_location_button',
        'website_template_id',
        'currency_code',
        'status',
        'owner_user_id',
    ];

    protected function casts(): array
    {
        return [
            'show_whatsapp_button' => 'boolean',
            'show_call_button' => 'boolean',
            'show_location_button' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function websiteTemplate(): BelongsTo
    {
        return $this->belongsTo(WebsiteTemplate::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }

    public function staffMembers(): HasMany
    {
        return $this->hasMany(StaffMember::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function websiteActionEvents(): HasMany
    {
        return $this->hasMany(TenantWebsiteActionEvent::class);
    }
}
