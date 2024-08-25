<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginValidator;
use App\Http\Requests\UserRegisterationValidator;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/auth/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="auth register",
     *      description="new account",
     * @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *                  example={"name": "John Doe", "email": "john.doe@example.com", "password": "1234567891"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     */
    public function register(UserRegisterationValidator $request)
    {
        return AuthService::register($request);
    }

    /**
     * register.
     *
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="auth login",
     *      description="new account",
     * @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  type="object",
     *                  @OA\Property(property="name", type="string"),
     *                  @OA\Property(property="email", type="string"),
     *                  example={"name": "John Doe", "email": "john.doe@example.com"}
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User not found"
     *      )
     * )
     */
    public function login(UserLoginValidator $request)
    {
        return AuthService::login($request);
    }

    public function verifyEmail(Request $request)
    {
        return AuthService::verifyEmail($request->userId, $request->token);
    }

}
