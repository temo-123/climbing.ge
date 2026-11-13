<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

// the user models live in App\Models\User — the old App\Models\… imports made every new social sign-up crash (500)
use App\Models\User\user_notification;
use App\Models\User\User_role;
use App\Models\User\Role;
use App\Models\Social_account;
use App\Models\User;

class SocialController extends Controller
{
    public function status()
    {
        return response()->json([
            'google' => $this->isProviderConfigured('google'),
            'facebook' => $this->isProviderConfigured('facebook'),
            // the app's browser-redirect flow (?mobile=1 → mobile_callback → mobile/exchange) is
            // available — the app only shows its Google / Facebook buttons when this is true
            'mobile' => true,
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
        if ($request->boolean('mobile')) {
            return $this->mobile_redirect($provider, $request);
        }

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

        $return_url = self::safe_return_url(self::decode_state($request->query('state')));

        return $this->login_or_register(
            $provider,
            (string) $social_user->getId(),
            $social_user->getEmail(),
            $social_user->getName(),
            $social_user->getAvatar(),
            ['return_url' => $return_url]
        );
    }

    /**
     * Mobile app, browser-redirect flow (docs/AUTH.md#mobile-browser-login):
     *   1. GET  /api/login/{provider}?mobile=1&nonce=<app secret>  → {url}  (open in the in-app browser)
     *   2. provider → GET /api/login/{provider}/mobile_callback → 302 climbinggeorgia://oauth-callback?code=…
     *   3. POST /api/login/mobile/exchange {code, nonce[, birthday]} → same JSON as POST /{provider}/mobile
     * The nonce never leaves the app except in steps 1 and 3 (stored only as a hash), so a
     * deep link caught by another app that registered the same scheme is useless without it.
     * No token is minted until step 3.
     */
    private function mobile_redirect(string $provider, Request $request)
    {
        if (!in_array($provider, ['google', 'facebook'], true)) {
            return response()->json(['message' => 'Unknown provider.'], 404);
        }

        $nonce = $request->query('nonce');
        if (!is_string($nonce) || !preg_match('/^[A-Za-z0-9_.~-]{16,128}$/', $nonce)) {
            return response()->json(['message' => 'Invalid nonce.'], 422);
        }

        $state = Str::random(40);
        Cache::put('social_m_state:' . hash('sha256', $state), [
            'provider' => $provider,
            'nonce'    => hash('sha256', $nonce),
        ], now()->addMinutes(15));

        try {
            $url = \Socialite::driver($provider)->stateless()
                ->redirectUrl(self::mobile_redirect_uri($provider))
                ->with(['state' => $state])
                ->redirect()->getTargetUrl();
            return response()->json(['url' => $url]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Could not initiate social login.'], 500);
        }
    }

    /** Provider → here (in the app's browser). Always answers with a deep link back to the app. */
    public function mobile_callback($provider, Request $request)
    {
        $state = (string) $request->query('state', '');
        $pending = $state !== '' ? Cache::pull('social_m_state:' . hash('sha256', $state)) : null;

        if (!$pending || ($pending['provider'] ?? null) !== $provider) {
            return self::app_redirect(['error' => 'invalid_state']);
        }
        if ($request->filled('error') || !$request->filled('code')) {
            return self::app_redirect(['error' => 'cancelled']);   // user closed / denied the consent screen
        }

        try {
            $social_user = \Socialite::driver($provider)->stateless()
                ->redirectUrl(self::mobile_redirect_uri($provider))
                ->user();
        } catch (\Throwable $e) {
            report($e);
            return self::app_redirect(['error' => 'provider_failed']);
        }

        if (!$social_user->getEmail()) {
            return self::app_redirect(['error' => 'no_email']);
        }

        $code = Str::random(48);
        Cache::put('social_m_code:' . hash('sha256', $code), [
            'provider' => $provider,
            'nonce'    => $pending['nonce'],
            'profile'  => [
                'id'     => (string) $social_user->getId(),
                'email'  => $social_user->getEmail(),
                'name'   => $social_user->getName(),
                'avatar' => $social_user->getAvatar(),
            ],
        ], now()->addMinutes(5));

        return self::app_redirect(['code' => $code, 'provider' => $provider]);
    }

    /** App → here with the one-time code from the deep link + its own nonce. */
    public function mobile_exchange(Request $request)
    {
        $request->validate([
            'code'     => 'required|string|max:200',
            'nonce'    => 'required|string|max:200',
            'birthday' => 'nullable|date|before:today|after:1900-01-01',
        ]);

        // pulled before the nonce check: one wrong guess burns the code
        $pending = Cache::pull('social_m_code:' . hash('sha256', $request->input('code')));
        if (!$pending) {
            return response()->json(['message' => 'This sign-in has expired. Please try again.'], 410);
        }
        if (!hash_equals($pending['nonce'], hash('sha256', $request->input('nonce')))) {
            return response()->json(['message' => 'This sign-in was started on another device. Please try again.'], 403);
        }

        $p = $pending['profile'];
        return $this->login_or_register(
            $pending['provider'], $p['id'], $p['email'], $p['name'], $p['avatar'], [], $request->input('birthday')
        );
    }

    /** services.{provider}.mobile_redirect, else the web redirect's host + /api/login/{provider}/mobile_callback */
    private static function mobile_redirect_uri(string $provider): string
    {
        $configured = trim((string) config("services.$provider.mobile_redirect"));
        if ($configured !== '') return $configured;

        $web = parse_url((string) config("services.$provider.redirect")) ?: [];
        $base = !empty($web['host'])
            ? ($web['scheme'] ?? 'https') . '://' . $web['host'] . (isset($web['port']) ? ':' . $web['port'] : '')
            : rtrim((string) config('app.url'), '/');

        return $base . '/api/login/' . $provider . '/mobile_callback';
    }

    private static function app_redirect(array $params)
    {
        $scheme = (string) config('services.mobile_app.scheme', 'climbinggeorgia');
        return redirect()->away($scheme . '://oauth-callback?' . http_build_query($params));
    }

    /**
     * Mobile app sign-in: the app signs in with the native Google / Facebook SDK and sends
     * the provider's token here (no browser redirect). The token is checked with the
     * provider AND must have been issued to one of OUR apps — otherwise a token a user
     * gave to some other app could be replayed here to log in as them.
     *   google:   { id_token }      — `aud` ∈ services.google.mobile_client_ids (+ web client id)
     *   facebook: { access_token }  — debug_token app_id = services.facebook.client_id
     * Optional `birthday` (YYYY-MM-DD) is stored for a NEW account (the age limit needs it).
     * Answers like callback(): {status:'login', token, is_underage} or
     * {status:'registratione', new_user_email, setup_key, is_underage}.
     * Docs: docs/AUTH.md#mobile-social-login
     */
    public function mobile($provider, Request $request)
    {
        if (!in_array($provider, ['google', 'facebook'], true)) {
            return response()->json(['message' => 'Unknown provider.'], 404);
        }

        $request->validate([
            'id_token'     => $provider === 'google' ? 'required|string|max:8000' : 'nullable',
            'access_token' => $provider === 'facebook' ? 'required|string|max:8000' : 'nullable',
            'birthday'     => 'nullable|date|before:today|after:1900-01-01',
        ]);

        $profile = $provider === 'google'
            ? self::verify_google_id_token($request->input('id_token'))
            : self::verify_facebook_access_token($request->input('access_token'));

        if (!$profile) {
            return response()->json(['message' => 'Social login failed: the token is invalid or was not issued for climbing.ge.'], 401);
        }

        return $this->login_or_register(
            $provider,
            $profile['id'],
            $profile['email'],
            $profile['name'],
            $profile['avatar'],
            [],
            $request->input('birthday')
        );
    }

    /** Google ID token → profile, or null. Checked by Google's tokeninfo endpoint. */
    private static function verify_google_id_token(string $id_token): ?array
    {
        $allowed = array_filter(array_merge(
            (array) config('services.google.mobile_client_ids', []),
            [config('services.google.client_id')]
        ));
        if (!$allowed) return null;

        try {
            $res = Http::timeout(8)->get('https://oauth2.googleapis.com/tokeninfo', ['id_token' => $id_token]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
        if (!$res->ok()) return null;

        $c = $res->json();
        if (!in_array($c['aud'] ?? null, $allowed, true)) return null;
        if (!in_array($c['iss'] ?? null, ['accounts.google.com', 'https://accounts.google.com'], true)) return null;
        if ((int) ($c['exp'] ?? 0) < time()) return null;
        if (!in_array($c['email_verified'] ?? null, [true, 'true'], true) || empty($c['email']) || empty($c['sub'])) return null;

        return [
            'id'     => (string) $c['sub'],
            'email'  => $c['email'],
            'name'   => $c['name'] ?? trim(($c['given_name'] ?? '') . ' ' . ($c['family_name'] ?? '')),
            'avatar' => $c['picture'] ?? null,
        ];
    }

    /** Facebook user access token → profile, or null. The token must belong to our app. */
    private static function verify_facebook_access_token(string $access_token): ?array
    {
        $app_id = (string) config('services.facebook.client_id');
        $secret = (string) config('services.facebook.client_secret');
        if ($app_id === '' || $secret === '') return null;

        try {
            $debug = Http::timeout(8)->get('https://graph.facebook.com/debug_token', [
                'input_token'  => $access_token,
                'access_token' => $app_id . '|' . $secret,
            ]);
            $d = $debug->json('data') ?? [];
            if (!$debug->ok() || empty($d['is_valid']) || (string) ($d['app_id'] ?? '') !== $app_id || empty($d['user_id'])) {
                return null;
            }

            $me = Http::timeout(8)->get('https://graph.facebook.com/me', [
                'fields'       => 'id,name,email,picture.type(large)',
                'access_token' => $access_token,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
        if (!$me->ok() || (string) $me->json('id') !== (string) $d['user_id']) return null;

        return [
            'id'     => (string) $me->json('id'),
            'email'  => $me->json('email'),   // null when the user didn't share it → 422 below
            'name'   => $me->json('name'),
            'avatar' => $me->json('picture.data.url'),
        ];
    }

    /**
     * Shared by the web callback and the mobile endpoint: existing user → login,
     * unknown e-mail → new account + one-time setup_key for the password step.
     */
    private function login_or_register(string $provider, string $provider_user_id, ?string $email, ?string $full_name, ?string $avatar, array $extra = [], ?string $birthday = null)
    {
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

            // The provider has confirmed this address, so it counts as verified
            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
            }

            // Remember the link for accounts that were created with e-mail + password and
            // log in with Google / Facebook later — before, only brand-new social sign-ups
            // got a social_accounts row, so the dashboard counters stayed at 0.
            Social_account::firstOrCreate(
                ['user_id' => $user->id, 'provider' => $provider],
                ['provider_user_id' => $provider_user_id]
            );

            $token = $user->createToken('socialToken')->plainTextToken;
            self::start_shared_session($user);

            return response()->json(array_merge(
                ['status' => 'login', 'token' => $token, 'is_underage' => $user->isUnderage()],
                $extra
            ));
        }

        // New user — split full name into name / surname
        $nameParts = explode(' ', trim((string) $full_name), 2);

        // One transaction: if any step fails (e.g. the old INT provider_user_id overflow on
        // Google ids) no half-created user is left behind without role / social link.
        $new_user = DB::transaction(function () use ($nameParts, $email, $avatar, $birthday, $provider, $provider_user_id) {
            $new_user = User::create([
                'name'              => $nameParts[0] ?? '',
                'surname'           => $nameParts[1] ?? '',
                'email'             => $email,
                'password'          => Hash::make(Str::random(32)),
            ]);

            // Not in $fillable — direct update. email_verified_at used to be passed to create(),
            // which silently dropped it: new social accounts were "unverified" and gated.
            $fill = ['email_verified_at' => now()];   // Google/Facebook already verified the email
            if ($avatar) $fill['image'] = $avatar;
            if ($birthday) $fill['birthday'] = $birthday;
            $new_user->forceFill($fill)->save();

            $socialAccount = new Social_account();
            $socialAccount['provider']          = $provider;
            $socialAccount['provider_user_id']  = $provider_user_id;
            $socialAccount['user_id']           = $new_user->id;
            $socialAccount->save();

            $this->createUserPermissionsAndNotifications($new_user->id);

            return $new_user;
        });

        // the provider already confirmed the address → welcome mail now (queued, never blocks sign-up)
        try {
            $new_user->notify(new \App\Notifications\WelcomeEmailNotification());
        } catch (\Throwable $e) {
            report($e);
        }

        // One-time key: only the browser / app that finished this sign-up may set the password
        // (create_password used to accept any e-mail — an account-takeover hole).
        $setup_key = Str::random(48);
        Cache::put('social_pw:' . hash('sha256', $setup_key), $new_user->id, now()->addHour());

        return response()->json(array_merge([
            'status'         => 'registratione',
            'new_user_email' => $email,
            'setup_key'      => $setup_key,
            'is_underage'    => $new_user->isUnderage(),
        ], $extra));
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
