<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthService
{
    public static function register($request)
    {
        $userAlreadyExists = User::where("email", $request->email)->exists();

        if ($userAlreadyExists) {
            throw new \Exception("this user already exists");
        }



        $user = User::create($request->all());

        return response()->json(['message' => 'Data submitted successfully', 'user' => $user]);

    }

    public static function login($request)
    {
        $user = User::where("email", "=", $request->email)->first();

        if (!$user) {
            return response()->json(['status' => 'failed', 'message' => "wrong credentials"]);
        }

        $passwordMatches = Hash::check($request->password, $user->password);

        if (!$passwordMatches) {
            return response()->json(['status' => 'failed', 'message' => "wrong credentials"]);
        }

        $token = $user->createToken($user->id)->plainTextToken;


        return response()->json(['status' => 'success', 'message' => 'user has logged in successfully', 'user' => $user, 'access_token' => $token]);

    }
}
