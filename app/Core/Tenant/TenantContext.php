<?php
namespace App\Core\Tenant;

use App\Modules\Companies\Models\Company;

class TenantContext
{
    protected static ?Company $tenant = null;

    public static function setTenant(?Company $company): void
    {
        static::$tenant = $company;
    }

    public static function getTenant(): ?Company
    {
        return static::$tenant;
    }

    public static function id(): ?string
    {
        return static::$tenant?->id;
    }
}
