<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'image_path',
        'title',
        'alt_text',
        'sort_order',
    ];
}
