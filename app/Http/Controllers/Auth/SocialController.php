<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\user_notification;
use App\Models\User_role;
use App\Models\Role;
use App\Models\Social_account;
use App\Models\User;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        try {
            $url = \Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
            return response()->json(['url' => $url]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Could not initiate social login.'], 500);
        }
    }

    public function callback($provider, Request $request)
    {
        try {
            $social_user = \Socialite::driver($provider)->stateless()->user();
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Social login failed: could not retrieve user from provider.'], 422);
        }

        $email = $social_user->getEmail();
        if (!$email) {
            return response()->json(['message' => 'No email returned from provider. Please allow email access.'], 422);
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            // Existing user — check ban before allowing login
            if ($user->isBanned()) {
                return response()->json([
                    'message'   => 'Your account has been banned.',
                    'is_banned' => true,
                ], 403);
            }

            $token = $user->createToken('socialToken')->plainTextToken;
            return response()->json(['status' => 'login', 'token' => $token]);
        }

        // New user — split full name into name / surname
        $fullName = $social_user->getName() ?? '';
        $nameParts = explode(' ', trim($fullName), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName  = $nameParts[1] ?? '';

        $new_user = User::create([
            'name'              => $firstName,
            'surname'           => $lastName,
            'email'             => $email,
            'email_verified_at' => now(), // Google/Facebook already verified the email
            'password'          => Hash::make(Str::random(32)),
        ]);

        // Save avatar URL separately (not in $fillable, use direct update)
        $avatar = $social_user->getAvatar();
        if ($avatar) {
            $new_user->forceFill(['image' => $avatar])->save();
        }

        $socialAccount = new Social_account();
        $socialAccount['provider']          = $provider;
        $socialAccount['provider_user_id']  = (string) $social_user->getId();
        $socialAccount['user_id']           = $new_user->id;
        $socialAccount->save();

        $this->createUserPermissionsAndNotifications($new_user->id);

        return response()->json([
            'status'         => 'registratione',
            'new_user_email' => $email,
        ]);
    }

    private function createUserPermissionsAndNotifications(int $user_id): void
    {
        if (user_notification::where('user_id', $user_id)->count() === 0) {
            $notif = new user_notification();
            $notif->user_id                = $user_id;
            $notif->add_new_gym            = 1;
            $notif->news                   = 1;
            $notif->add_new_ice_spot       = 1;
            $notif->add_new_outdoor_spot   = 1;
            $notif->add_new_product        = 1;
            $notif->add_new_sector         = 1;
            $notif->add_new_service        = 1;
            $notif->add_new_techtip        = 1;
            $notif->favorite_film          = 1;
            $notif->favorite_outdoor       = 1;
            $notif->favorite_product       = 1;
            $notif->interested_event       = 1;
            $notif->save();
        }

        if (User_role::where('user_id', $user_id)->count() === 0) {
            $role = Role::where('slug', 'user')->first();
            if ($role) {
                $userRole = new User_role();
                $userRole->user_id = $user_id;
                $userRole->role_id = $role->id;
                $userRole->save();
            }
        }
    }

    public function create_password(Request $request)
    {
        $validator = \Validator::make($request->data ?? [], [
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->data['password'])]);

        $token = $user->createToken('socialToken')->plainTextToken;
        return response()->json(['message' => 'Password created successfully', 'token' => $token]);
    }
}
