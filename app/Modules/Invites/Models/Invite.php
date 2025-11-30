<?php
namespace App\Modules\Invites\Models;

use App\Core\BaseModel;
use App\Modules\Companies\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Invite extends BaseModel
{
    protected $table = 'invitations';

    protected $fillable = [
        'company_id',
        'email',
        'token_hash',
        'role',
        'expires_at',
        'redeemed',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'redeemed' => 'boolean',
    ];

    // Helpers
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
