<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RestPasswordController extends Controller
{
    public function reset(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Reset the password
            $status = Password::reset(
                $request->only('email', 'password', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->save();
                }
            );

            // Return response based on the status
            switch ($status) {
                case Password::PASSWORD_RESET:
                    return response()->json(['message' => 'Password has been reset'], 200);
                case Password::INVALID_TOKEN:
                    return response()->json(['message' => 'Invalid token'], 400);
                case Password::INVALID_USER:
                    return response()->json(['message' => 'Invalid email address'], 404);
                default:
                    return response()->json(['message' => 'Failed to reset password'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred'], 500);
        }
    }
}
