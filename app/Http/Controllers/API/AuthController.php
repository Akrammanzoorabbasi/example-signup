<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hashing the password
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Authentication Fails',
                'errors' => $validateUser->errors()->all()
            ], 404);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([
                'status' => true,
                'message' => 'User Logged in Successfully',
                'token' => $authUser->createToken("API Token")->plainTextToken,
                'token_type' => 'bearer'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password do not match.',
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user(); // Get the authenticated user

        // Revoke all tokens issued by the user
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully!',
        ], 200);
    }
}
