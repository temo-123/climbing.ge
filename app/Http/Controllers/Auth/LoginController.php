<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\LoginEmailNotification;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle encrypted password login for API
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        // Decrypt the encrypted password
        $encryptedPassword = $request->password;
        $privateKeyPath = storage_path('framework/private.key');
        
        if (!file_exists($privateKeyPath)) {
            \Log::error('Private key file not found: ' . $privateKeyPath);
            return response()->json(['message' => 'Server configuration error'], 500);
        }

        // Check file readability
        if (!is_readable($privateKeyPath)) {
            \Log::error('Private key file is not readable: ' . $privateKeyPath);
            return response()->json(['message' => 'Server configuration error'], 500);
        }

        $privateKey = @file_get_contents($privateKeyPath);
        
        if ($privateKey === false) {
            \Log::error('Failed to read private key file');
            return response()->json(['message' => 'Server configuration error'], 500);
        }
        
        $decryptedPassword = null;
        $success = openssl_private_decrypt(
            base64_decode($encryptedPassword),
            $decryptedPassword,
            $privateKey
        );

        if (!$success || $decryptedPassword === null) {
            return response()->json(['message' => 'Invalid encrypted password'], 400);
        }

        // Attempt login with decrypted password
        $credentials = [
            'email' => $request->email,
            'password' => $decryptedPassword
        ];

        if (auth()->attempt($credentials, $request->remember)) {
            $user = auth()->user();
            $user->tokens()->where('name', 'auth_token')->delete();
            $token = $user->createToken('authToken')->plainTextToken;
            
            return response()->json([
                'token' => $token,
                'user' => $user,
                'message' => 'Login successful'
            ]);
        }

        return response()->json(['message' => 'auth.failed'], 422);
    }
}
