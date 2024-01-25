<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required | email | unique:users",
            "password" => "required | confirmed"
        ]);

        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        return response()->json([
            "message" => "User registered succesfully"
        ],200);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required | email",
            "password" => "required"
        ]);

        $user = User::where("email", $request->email)->first();

        if(!empty($user)) {

            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken("loginToken")->plainTextToken;

                return response()->json([
                    "message" => "Login succesful",
                    "token" => $token
                ],200);
            }

            return response()->json([
                "message" => "Passwords didn't match"
            ],404);
        }

        return response()->json([
            "message" => "Invalid login details"
        ],404);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "user logged out"
        ],404);
    }
}
