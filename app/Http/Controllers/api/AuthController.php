<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use App\Http\Resources\UserResource;
use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use PasswordValidationRules;

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'access-token'  => $user->createToken('access-token')->plainTextToken
        ];
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'password' => Hash::make($request['password']),
        ]);

        return [
            'access-token' =>  $user->createToken('access-token')->plainTextToken,
            'user' => new UserResource($user)
        ];
    }

    public function logout()
    {
        // Revoke all tokens...
        // Auth()->user()->tokens()->delete();

        // Revoke the token that was used to authenticate the current request...
        Auth::user()->currentAccessToken()->delete();
    }
}
