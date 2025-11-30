<?php

namespace App\Providers;

use App\Modules\Invites\Repositories\Eloquent\EloquentInviteRepository;
use App\Modules\Invites\Repositories\InviteRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InvitesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            InviteRepositoryInterface::class,
            EloquentInviteRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
