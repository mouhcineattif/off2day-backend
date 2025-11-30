<?php
namespace App\Core;

use Illuminate\Database\Eloquent\Model;
use App\Core\Traits\UsesUuid;

abstract class BaseModel extends Model
{
    use UsesUuid;

    // Common behavior, e.g., tenant scoping can be added here
    protected $keyType = 'string';
    public $incrementing = false;
}
