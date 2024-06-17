<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginValidator;
use App\Http\Requests\UserRegisterationValidator;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(UserRegisterationValidator $request)
    {
        return AuthService::register($request);
    }

    public function login(UserLoginValidator $request) {
        return AuthService::login($request);
    }
}
