<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        view()->composer('layouts.master', function ($view) {
            $view->with('setting', Setting::first());
        });

        view()->composer('layouts.auth', function ($view) {
            $view->with('setting', Setting::first());
        });

        view()->composer('auth.login', function ($view) {
            $view->with('setting', Setting::first());
        });
    }


    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define('master', function (User $user) {
            return $user->role == "Administrator" || $user->role == "Supervisor";
        });

        Gate::define('setting', function (User $user) {
            return $user->role == "Administrator";
        });
    }
}
