<?php

namespace App\Providers;

use App\Models\Colocation;
use App\Models\Invitation;
use App\models\User;
use App\Policies\ColocationPolicy;
use App\Policies\InvitationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {
            $colocation = null;

            if (session('current_colocation_id')) {
                $colocation = Colocation::find(session('current_colocation_id'));
            }

            $view->with('colocation', $colocation);
        });
    }
}
