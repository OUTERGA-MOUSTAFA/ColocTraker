<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // admin pour les statistique et bannir/debannis user
        Gate::define('view-dashboard', function ($user) {
            return $user->isAdmin();
        });

        // owner gerer colocation, invéter Members
        Gate::define('manage-colocation', function ($user) {
            return $user->isAdmin() || $user->isOwner();
        });

        // Member
        Gate::define('details', function ($user) {
            return $user->isAdmin() || $user->isOwner() || $user->isMember();
        });
    }
}
