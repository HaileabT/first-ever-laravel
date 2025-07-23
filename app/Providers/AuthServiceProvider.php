<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public static $authCookieName;
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->register(\Tymon\JWTAuth\Providers\LaravelServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AuthServiceProvider::$authCookieName = config('auth.auth_cookie_name');
    }
}
