<?php

namespace App\Http\Middleware;

use App\Providers\AuthServiceProvider;
use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->cookie(AuthServiceProvider::$authTokenName);
            $user = JWTAuth::setToken($token)->authenticate();
            Log::info($user);


            if (!$user) {
                return response()->errorResponse('User does not exist.', 'fail', 401);
            }
            $request->merge(['user' => $user]);
            return $next($request);
        } catch (TokenInvalidException | TokenExpiredException $e) {
            return response()->errorResponse('User not logged in.', 'fail', 401);
        } catch (Exception $e) {
            return response()->errorResponse();
        }
    }
}
