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
            $token = $user->createToken(AuthServiceProvider::$authTokenName)->plainTextToken;
            Log::info($token);
            // return response()->json($user)->cookie(AuthServiceProvider::$authCookieName, $token, 60 * 60 * 24, '/api', false, true, true);
        } catch (ValidationException $e) {
            Log::error($e);

            return response()->errorResponse('Bad registerion data' . $e->getMessage(), 'fail', 402);
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
            Log::info(AuthServiceProvider::$authTokenName);
            $token = $user->createToken(AuthServiceProvider::$authTokenName)->plainTextToken;
            // $token  = Auth::attempt($validated);
            Log::info($token);

            if (!$user) {
                throw new Exception("Username or password invalid.");
            }
            return response()->json(['user' => $user])->cookie(AuthServiceProvider::$authTokenName, $token, 60 * 24, '/api', false, false, true);;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->errorResponse();
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response(["status" => "successful"], 200);
    }

    public function me(Request $request)
    {
        return response(["user" => $request['user']], 200);
    }
}
