<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponses;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiLoginRequest;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Permissions\V1\Abilities;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only(['email', 'password']))) {
            return ApiResponses::error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);

        return ApiResponses::ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API Token for ' . $user->email,
                    Abilities::getAbilities($user),
                    now()->addWeek()
                )->plainTextToken,
            ]
        );
    }

    public function register()
    {
        return ApiResponses::ok('register');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return ApiResponses::ok('');
    }
}
