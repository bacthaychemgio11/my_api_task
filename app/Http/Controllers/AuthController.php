<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     description="Register",
     *     tags={"auth"},
     * @OA\RequestBody(
     *    required=false,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"name","email","password"},
     *       @OA\Property(property="name", type="string", example="user1"),
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *     response=200,
     *     description="Register successfully",
     *     @OA\JsonContent(
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *        @OA\Property(property="token", type="string", example="1|qweertyuasdkasdhx"),
     *     )
     *  ),
     * @OA\Response(
     *     response=402, 
     *     description="Invalid credentials"
     *  ),
     * @OA\Response(
     *     response=500, 
     *     description="Internal Server Error"
     *  ),
     * )
     */
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('my-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     description="Login",
     *     tags={"auth"},
     * @OA\RequestBody(
     *    required=false,
     *    description="Log in",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="123456"),
     *    ),
     * ),
     * @OA\Response(
     *     response=201,
     *     description="Log in successfully",
     *     @OA\JsonContent(
     *        @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *        @OA\Property(property="token", type="string", example="1|qweertyuasdkasdhx"),
     *     )
     *  ),
     * @OA\Response(
     *     response=402, 
     *     description="Invalid credentials"
     *  ),
     * @OA\Response(
     *     response=500, 
     *     description="Internal Server Error"
     *  ),
     * )
     */
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user) {
            return response(['message' => 'Wrong email or password.'], 401);
        } else {
            if (!Hash::check($fields['password'], $user->password)) {
                return response(['message' => 'Wrong email or password.'], 401);
            }
        }

        $token = $user->createToken('my-token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     description="Logout",
     *     tags={"auth"},
     *     security={ {"bearer": {} }},
     * @OA\Response(
     *     response=200,
     *     description="Logout successfully",
     *     @OA\JsonContent(
     *        @OA\Property(property="message", type="string", example="Log out successfully!'"),
     *     )
     *  ),
     * @OA\Response(
     *     response=500, 
     *     description="Internal Server Error"
     *  ),
     * @OA\Response(
     *    response=401,
     *    description="Returns when user is not authenticated",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Unauthenciated"),
     *    )
     *  )
     * )
     */
    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Log out successfully!'
        ];
    }
}
