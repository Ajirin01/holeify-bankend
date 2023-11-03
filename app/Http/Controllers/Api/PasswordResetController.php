<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\ResetPassword;
use App\User;
use Mail;

class PasswordResetController extends Controller
{
    // API endpoint to send a password reset email
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // return response()->json($request->all());

        $response = Password::sendResetLink($request->only('email'), function($message) {
            // return response()->json($message);
            // $message->subject('Password Reset Request');
        });

        if ($response == Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Password reset email sent'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password reset email not sent'
            ], 500);
        }
    }

    // API endpoint to validate a password reset token
    public function find($token)
    {
        $reset = DB::table('password_resets')->where('token', $token)->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_resets')->where('email', $reset->email)->delete();

            return response()->json([
                'success' => false,
                'message' => 'Token expired'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Token validated'
        ], 200);
    }

    // API endpoint to reset a user's password
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $reset = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$reset) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 404);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ], 200);
    }
}
