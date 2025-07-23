<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('errorResponse', function ($message = "Something went wrong.", $status = 'error', $code = 500, $extra = []) {
            return response()->json(array_merge(['status' => $status, 'message' => $message], $extra), $code);
        });
    }
}
