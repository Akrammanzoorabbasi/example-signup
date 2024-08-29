<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        // \Log::info('Password reset link status:', ['status' => $status]);
        
        // Check the status and return appropriate response
        switch ($status) {
            case Password::RESET_LINK_SENT:
                return response()->json(['message' => 'Password reset link sent'], 200);
            case Password::INVALID_USER:
                return response()->json(['message' => 'No account found with that email address'], 404);
            default:
                return response()->json(['message' => 'Unable to send reset link'], 400);
        }
    }
}
