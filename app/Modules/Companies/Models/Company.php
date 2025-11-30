<?php
namespace App\Modules\Companies\Models;

use App\Core\BaseModel;

class Company extends BaseModel
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'domain',
        'timezone',
        'locale',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
