<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Blog\Post;
use App\Models\Social_account;
use App\Services\AccountDeletionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    /**
     * Get the authenticated user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
        }

        // Log out the web (session) guard — this removes the auth identity from
        // the session so any subdomain sharing the parent-domain session cookie
        // immediately loses authentication.
        try {
            \Illuminate\Support\Facades\Auth::guard('web')->logout();
        } catch (\Throwable) {}

        // Belt-and-suspenders: also flush + invalidate the session directly.
        if ($request->hasSession()) {
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated',
                'user' => null
            ], 401);
        }

        // Check if user is banned
        if ($user->isBanned()) {
            return response()->json([
                'alert' => [
                    'type' => 'error',
                    'title' => 'Account Banned',
                    'message' => 'Your account has been banned.',
                    'icon' => 'ban'
                ],
                'user' => null,
                'is_banned' => true,
                'message' => 'Your account has been banned.',
            ], 403);
        }

        $allPermissions = $user->getAllPermissions()->map(fn($p) => [
            'action'  => $p->action,
            'subject' => $p->subject,
        ])->values();

        return response()->json(array_merge($user->toArray(), [
            'casl_permissions' => $allPermissions,
        ]));
    }

    /**
     * Self-service account deletion (Google Play User Data policy).
     * Used by the user page /delete-account and the mobile app.
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        $user = $request->user();

        // Social signups get a random password they never see — the token alone proves identity
        $requires_password = !Social_account::where('user_id', $user->id)->exists();

        $request->validate([
            'confirm'  => 'required|in:DELETE',
            'password' => $requires_password ? 'required|string' : 'nullable',
        ]);

        if ($requires_password && !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => ['password' => ['The password is incorrect.']],
            ], 422);
        }

        // posts.user_id is NOT NULL — staff who authored blog posts are deleted by an admin
        if (Post::where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'This account owns blog posts. Please contact support to delete it.'], 409);
        }

        AccountDeletionService::delete($user);

        return response()->json(['message' => 'Account deleted']);
    }
}
