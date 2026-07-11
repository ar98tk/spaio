<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class TenantWebsiteActionEvent extends Model
{
    use BelongsToTenant;

    public const TYPES = ['whatsapp', 'call', 'location'];

    protected $fillable = [
        'tenant_id',
        'action_type',
        'context',
        'label',
        'target_url',
        'ip_address',
        'user_agent',
    ];
}
