<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\Filesystem;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerModuleProviders();
    }

    protected function registerModuleProviders()
    {
        $fs = new Filesystem();
        $modulesPath = app_path('Modules');

        if (! $fs->exists($modulesPath)) {
            return;
        }

        $dirs = $fs->directories($modulesPath);
        foreach ($dirs as $dir) {
            $provider = $dir . DIRECTORY_SEPARATOR . 'Providers' . DIRECTORY_SEPARATOR . basename($dir) . 'ServiceProvider.php';
            if ($fs->exists($provider)) {
                // build FQCN
                $moduleName = basename($dir);
                $class = "App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
                if (class_exists($class)) {
                    $this->app->register($class);
                }
            }
        }
    }
}
