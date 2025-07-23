<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public static $authTokenName;
    /**
     * Register services.
     */
    public function register(): void
    {
        // $this->app->register(\Tymon\JWTAuth\Providers\LaravelServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        AuthServiceProvider::$authTokenName = config('auth.auth_token_name', "auth_token");
    }
}
