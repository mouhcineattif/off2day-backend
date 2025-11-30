<?php
namespace App\Modules\Companies\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Companies\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Repositories\Eloquent\EloquentCompanyRepository;

class CompaniesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
    }

    public function boot()
    {
        // load routes if present
        $routes = __DIR__ . '/../Routes/api.php';
        if (file_exists($routes)) {
            $this->loadRoutesFrom($routes);
        }

        // Optional: publish config/views/lang here
    }
}
