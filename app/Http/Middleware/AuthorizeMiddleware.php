<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class AuthorizeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $authType): Response
    {
        $user = $request['user'];

        if (!$user) {
            throw new AuthenticationException("User not logged in.");
        }

        switch ($authType) {
            case 'admin':
                $role = $user['role'];
                if (!$role || $role != 'admin') {
                    return response()->errorResponse('Action Forbidden', 'fail', 403);
                }
                return $next($request);

            case 'author_edit':
                $book = $request['book'];

                if (!$book) {
                    throw new ResourceNotFoundException("Book doesn't exist.");
                }

                if ($book['user_id'] != $user['id']) {
                    return response()->errorResponse('Action Forbidden', 'fail', 403);
                }
            case 'author_write':
                if ($user['role'] != 'author') {
                    return response()->errorResponse('Action Forbidden', 'fail', 403);
                }
            default:
                return response()->errorResponse();
        }
        return $next($request);
    }
}
