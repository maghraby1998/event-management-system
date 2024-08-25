<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Models\UserEmailVerificationTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;




class AuthService
{
    public static function register($request)
    {
        $userAlreadyExists = User::where("email", $request->email)->exists();

        if ($userAlreadyExists) {
            throw new \Exception("this user already exists");
        }

        $user = User::create($request->all());

        AuthService::sendVerificationEmail($user);

        return response()->json(['message' => "An email has been sent to {$user->email}", 'user' => $user]);

    }

    public static function sendVerificationEmail($user)
    {

        $userEmailToken = UserEmailVerificationTokens::where("user_id", $user->id)->first();

        if ($userEmailToken) {
            return;
        }

        $token = rand(0, 1000000000);

        UserEmailVerificationTokens::create([
            "user_id" => $user->id,
            "token" => $token
        ]);

        Mail::to($user->email)->send(new VerifyEmail($user, $token));
    }

    public static function verifyEmail($userId, $token)
    {
        $userEmailToken = UserEmailVerificationTokens::where("user_id", $userId)->first();

        if (!$userEmailToken) {
            // should through an exception here
        }

        if ($userEmailToken->token !== $token) {
            // token are not matched 
        }

        $user = User::find($userId);

        $user->email_verified_at = now();

        $user->save();

        $userEmailToken->delete();
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

        if ($user->email_verified_at == null) {
            return response()->json(['status' => 'failed', 'message' => "this email is not verified"]);
        }

        $token = $user->createToken($user->id)->plainTextToken;

        return response()->json(['status' => 'success', 'message' => 'user has logged in successfully', 'user' => $user, 'access_token' => $token]);

    }


}
