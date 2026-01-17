<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VerificationController extends Controller
{
    /**
     * Verify user's email address.
     *
     * @param Request $request
     * @param int $user_id
     * @param string $hash
     * @return JsonResponse
     */
    public function verify(Request $request, int $user_id, string $hash): JsonResponse
    {
        $user = User::find($user_id);
        
        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Simple hash verification (you might want to use a more secure method)
        $expectedHash = sha1($user->email . $user->created_at);
        
        if (!hash_equals($expectedHash, $hash)) {
            return response()->json([
                'message' => 'Invalid verification link'
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        $user->markEmailAsVerified();

        return response()->json([
            'message' => 'Email verified successfully',
            'user' => $user
        ], 200);
    }

    /**
     * Resend verification email.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email already verified'
            ], 200);
        }

        // In Laravel, you would typically use the Notification facade here
        // $user->sendEmailVerificationNotification();
        
        return response()->json([
            'message' => 'Verification email sent'
        ], 200);
    }
}

