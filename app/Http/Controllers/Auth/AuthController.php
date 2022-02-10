<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        //access token when registering for the first time
        $token = $user->createToken('access_token')->plainTextToken;
        return response()->json([
            'message' => 'user created successfully',
            'user' => $user,
            'token' => $token
        ]);
    }
    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'please check your email and password',
                'status' => 401
            ]);
        } else {
            $token = $user->createToken('access_token')->plainTextToken;
            return response()->json([
                'message' => 'logged in successfully',
                'user' => $user,
                'token' => $token,
                'status' => 201
            ]);
        }
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message' => 'logged out succefully',
        ]);
    }
}
