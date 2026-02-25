<?php

namespace App\Providers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\models\User;
use App\Policies\ColocationPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\UserPolicy;
// use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

protected $policies = [
    Colocation::class => ColocationPolicy::class,
    Invitation::class => InvitationPolicy::class,
    User::class => UserPolicy::class,
];


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
        // Gate::define('view-dashboard', function ($user) {
        //     return $user->isAdmin();
        // });

        // // owner gerer colocation, invéter Members
        // Gate::define('manage-colocation', function ($user) {
        //     return $user->isAdmin() || $user->isOwner();
        // });

        // // Member
        // Gate::define('details', function ($user) {
        //     return $user->isAdmin() || $user->isOwner() || $user->isMember();
        // });
    }
}
