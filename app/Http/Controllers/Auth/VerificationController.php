<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

use App\Notifications\WelcomeEmailNotification;
use Notification;

use Carbon\Carbon;

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\User\user_notification;
use App\Models\User\User_role;
use App\Models\User\Role;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     */
    // public function verify(Request $request, User $user)
    public function verify(Request $request)
    {
        // if (!URL::hasValidSignature($request)) {
        //     return response([
        //         'Your request is field! Maybe email older than 10 minutes.'
        //     ], 400);
        // }
        // else{

            $user = User::where('id', '=', $request->user_id)->first();
            
            if ($user->hasVerifiedEmail()) {
                return 'Your email olredy verificated!';
            }
            else{
                $user->markEmailAsVerified();
                event(new Verified($user));
        
                $this -> create_user_permissions_and_notificationes($request->user_id);

                Notification::route('mail', $user['email'])->notify(new WelcomeEmailNotification());

                return 'Verification socsessful!';
            }
        // }
    }


    private function create_user_permissions_and_notificationes(int $user_id)
    {
        if(user_notification::where('user_id', '=', $user_id)->count() == 0){
            $new_notification_item =  new user_notification();

            $new_notification_item['user_id'] = $user_id;

            $new_notification_item['add_new_gym'] = 1;
            $new_notification_item['news'] = 1;
            $new_notification_item['add_new_ice_spot'] = 1;
            $new_notification_item['add_new_outdoor_spot'] = 1;
            $new_notification_item['add_new_product'] = 1;
            $new_notification_item['add_new_sector'] = 1;
            $new_notification_item['add_new_service'] = 1;
            $new_notification_item['add_new_techtip'] = 1;
            $new_notification_item['favorite_film'] = 1;
            $new_notification_item['favorite_outdoor'] = 1;
            $new_notification_item['favorite_product'] = 1;
            $new_notification_item['interested_event'] = 1;

            $new_notification_item -> save();
        }

        if(User_role::where('user_id', '=', $user_id)->count() == 0){
            $new_permission_item =  new User_role();

            $new_permission_item['user_id'] = $user_id;
            $new_permission_item['role_id'] = Role::where('slug', '=', 'user')->first()->id; // ID 1 in role tab is a user

            $new_permission_item -> save();
        }
    }
}
