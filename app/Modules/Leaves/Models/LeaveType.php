<?php
namespace App\Modules\Leaves\Models;

use App\Core\BaseModel;
use App\Modules\Companies\Models\Company;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveType extends BaseModel
{
    protected $table = 'leave_types';

    protected $fillable = [
        'company_id',
        'name',
        'code',
        'payable',
        'max_days_per_year',
        'carry_over_allowed',
        'requires_document',
        'requires_approval_level',
    ];

    protected $casts = [
        'payable' => 'boolean',
        'carry_over_allowed' => 'boolean',
        'requires_document' => 'boolean',
        'max_days_per_year' => 'integer',
        'requires_approval_level' => 'integer',
    ];

    // Relationships

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
