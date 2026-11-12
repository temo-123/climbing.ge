<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\user_notification;
use App\Models\User_role;
use App\Models\Role;
use App\Models\Social_account;
use App\Models\User;

class SocialController extends Controller
{
    public function status()
    {
        return response()->json([
            'google' => $this->isProviderConfigured('google'),
            'facebook' => $this->isProviderConfigured('facebook'),
        ]);
    }

    private function isProviderConfigured(string $provider): bool
    {
        foreach (['client_id', 'client_secret', 'redirect'] as $key) {
            $value = trim((string) config("services.$provider.$key"));

            if ($value === '' || $value === '...') {
                return false;
            }
        }

        return true;
    }

    /**
     * ?return=<url> — the page the user clicked "Log in with …" on. It travels through
     * the provider in the OAuth `state` param and comes back to the callback page,
     * which sends the user back there. Only climbing.ge (sub)domain URLs are kept.
     */
    public function redirect($provider, Request $request)
    {
        try {
            $driver = \Socialite::driver($provider)->stateless();
            $return = self::safe_return_url($request->query('return'));
            if ($return) {
                $driver->with(['state' => rtrim(strtr(base64_encode($return), '+/', '-_'), '=')]);
            }
            $url = $driver->redirect()->getTargetUrl();
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

        $return_url = self::safe_return_url(self::decode_state($request->query('state')));

        $user = User::where('email', $email)->first();

        if ($user) {
            // Existing user — check ban before allowing login
            if ($user->isBanned()) {
                return response()->json([
                    'message'   => 'Your account has been banned.',
                    'is_banned' => true,
                ], 403);
            }

            // The provider has confirmed this address, so it counts as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            $token = $user->createToken('socialToken')->plainTextToken;
            self::start_shared_session($user);

            return response()->json(['status' => 'login', 'token' => $token, 'return_url' => $return_url]);
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

        // One-time key: only the browser that finished this sign-up may set the password
        // (create_password used to accept any e-mail — an account-takeover hole).
        $setup_key = Str::random(48);
        Cache::put('social_pw:' . hash('sha256', $setup_key), $new_user->id, now()->addHour());

        return response()->json([
            'status'         => 'registratione',
            'new_user_email' => $email,
            'setup_key'      => $setup_key,
            'return_url'     => $return_url,
        ]);
    }

    private function createUserPermissionsAndNotifications(int $user_id): void
    {
        if (user_notification::where('user_id', $user_id)->count() === 0) {
            // notification_type is a single JSON column (not individual
            // add_new_gym/news/etc. columns — those were dropped by the
            // 2025_10_25_173638 migration). Setting them as direct properties
            // bypasses mass-assignment protection and would throw an "unknown
            // column" SQL error on save() instead of silently no-op'ing.
            $notif = new user_notification();
            $notif->user_id = $user_id;
            $notif->notification_type = [
                'add_new_gym' => true,
                'news' => true,
                'add_new_ice_spot' => true,
                'add_new_outdoor_spot' => true,
                'add_new_product' => true,
                'add_new_sector' => true,
                'add_new_service' => true,
                'add_new_techtip' => true,
                'favorite_film' => true,
                'favorite_outdoor' => true,
                'favorite_product' => true,
                'interested_event' => true,
            ];
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

        // the key from callback() must belong to this e-mail's freshly created social account
        $setup_key = (string) $request->input('setup_key');
        $cache_key = 'social_pw:' . hash('sha256', $setup_key);
        $user_id = $setup_key !== '' ? Cache::get($cache_key) : null;
        $user = $user_id ? User::find($user_id) : null;
        if (!$user || strcasecmp($user->email, (string) $request->route('email')) !== 0) {
            return response()->json(['message' => 'This link has expired. Please sign in with Google / Facebook again.'], 403);
        }
        Cache::forget($cache_key);

        $user->update(['password' => Hash::make($request->data['password'])]);

        $token = $user->createToken('socialToken')->plainTextToken;
        self::start_shared_session($user);
        return response()->json(['message' => 'Password created successfully', 'token' => $token]);
    }

    /**
     * Log the user into the web session too. The session cookie is shared by every
     * subdomain (SESSION_DOMAIN), so the page the user came from can recover a token
     * with GET /api/token — same as the e-mail/password login (auth()->attempt()).
     */
    private static function start_shared_session(User $user): void
    {
        try {
            if (request()->hasSession()) {
                Auth::guard('web')->login($user, true);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private static function decode_state($state): ?string
    {
        if (!is_string($state) || $state === '' || strlen($state) > 4000) return null;
        $decoded = base64_decode(strtr($state, '-_', '+/'), true);
        return $decoded === false ? null : $decoded;
    }

    /** Only http(s) URLs on climbing.ge or one of its subdomains; anything else → null. */
    private static function safe_return_url($url): ?string
    {
        if (!is_string($url) || $url === '' || strlen($url) > 2000) return null;
        $parts = parse_url($url);
        if (!$parts || !in_array($parts['scheme'] ?? '', ['http', 'https'], true) || empty($parts['host'])) return null;
        if (isset($parts['user']) || isset($parts['pass'])) return null;

        $host = strtolower($parts['host']);
        $site = strtolower((string) config('app.site_url', 'climbing.ge'));
        if ($host !== $site && !str_ends_with($host, '.' . $site)) return null;

        return $url;
    }
}
