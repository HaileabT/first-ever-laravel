<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string',
                'role' => 'in:author,reader,admin',
            ]);

            $user = User::create($validated);
            $token = Auth::fromUser($user);
            return response()->json($validated)->cookie('auth-jwt-token', $token, 60 * 24, '/api/register', false, false, false, 'Strict');
        } catch (ValidationException $e) {
            Log::error($e);

            return response()->errorResponse('Bad register request' . $e->getMessage(), 'fail', 400);
        } catch (Exception $e) {
            return response()->errorResponse();
            Log::error("Something went wrong: " . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::find($id);

        return $user;
    }

    public function update(Request $req, User $user)
    {
        $user->update($req->only([
            "username",
            "passowrd",
            "role"
        ]));

        return $user;
    }

    public function destroy(String $id)
    {
        Log::info($id);
        try {
            User::destroy($id);
        } catch (Exception $e) {
            Log::error($e);
        }

        Log::info($id);

        return response()->noContent();
    }
}
