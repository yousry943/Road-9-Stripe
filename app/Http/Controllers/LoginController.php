<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
use App\Traits\ApiResponseTrait;

class LoginController extends Controller
{
    use ApiResponseTrait;

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:8',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $e->errors(),
            ], 422);
        }
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user(); // Retrieve the authenticated user object
        $token = JWTAuth::fromUser($user); // Generate JWT token using the user object
        return $this->apiResponseData($token, 'Success', 200);
    }
    public function logout(Request $request)
    {

        auth()->logout();
        $massage = 'You have successfully logged out.';
        return $this->apiResponseData($massage, 'Success', 200);
    }
}
