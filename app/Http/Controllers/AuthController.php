<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\AuthServiceProvider;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string',
                'role' => 'in:author,reader,admin',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $user = User::create($validated);
            $token = Auth::fromUser($user);
            return response()->json($user)->cookie(AuthServiceProvider::$authCookieName, $token, 60 * 60 * 24, '/api', false, true, true);
        } catch (ValidationException $e) {
            Log::error($e);

            return response()->errorResponse('Bad registerion data' . $e->getMessage(), 'fail', 400);
        } catch (Exception $e) {
            return response()->errorResponse();
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            Log::info($validated);

            $user = User::where('username', $validated['username'])->first();
            $token  = Auth::attempt($validated);
            Log::info($user);

            if (!$user) {
                throw new Exception("Username or password invalid.");
            }
            return response()->json(['user' => $user])->cookie(AuthServiceProvider::$authCookieName, $token, 60 * 24, '/api', false, false, true);;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function logout(Request $request)
    {
        return response(["status" => "successful"], 200)->withCookie(Cookie::forget(AuthServiceProvider::$authCookieName, '/api', null));
    }

    public function me(Request $request)
    {
        return response(["user" => $request['user']], 200);
    }
}
