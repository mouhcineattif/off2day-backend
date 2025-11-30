<?php
namespace App\Modules\Employees\Models;

use App\Core\BaseModel;
use App\Modules\Companies\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeProfile extends BaseModel
{
    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'company_id',
        'employee_number',
        'job_title',
        'department_id',
        'manager_id',
        'hire_date',
        'work_schedule',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'work_schedule' => 'array',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Companies\Models\Department::class, 'department_id');
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(self::class, 'manager_id');
    }

    public function directReports(): HasMany
    {
        return $this->hasMany(self::class, 'manager_id');
    }
}
