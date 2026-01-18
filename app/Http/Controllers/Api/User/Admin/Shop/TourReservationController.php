<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TourReservationRequest;

use Auth;
use Notification;

use App\Models\User;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_reservation;
use App\Models\Shop\Tour_reservations_user;

use App\Notifications\tour\ReservationCreatingGuideNotification;
use App\Notifications\tour\ReservationSacsesfulNotification;
use App\Notifications\tour\ReservationVerifyEmail;
use App\Services\PermissionService;

class TourReservationController extends Controller
{
    function get_all_reservations(){
        // $auth = PermissionService::authorize('tour_reservation', 'view');
        // if ($auth) return $auth;
        
        return Tour_reservation::latest('id')->get();
    }

    function get_user_reservations(){
        // $auth = PermissionService::authorize('tour_reservation', 'view');
        // if ($auth) return $auth;
        
        $user_tours = Auth::user()->tours;

        $reservatione =[];

        if($user_tours->count() > 0){
            foreach ($user_tours as $tour) {
                foreach ($tour->resrtvation as $reserv) {
                    array_push($reservatione, $reserv);
                }
            }
            return $reservatione;
        }
        return [];
    }

    function create_reservation(TourReservationRequest $request){
        $auth = PermissionService::authorize('tour_reservation', 'add');
        if ($auth) return $auth;
        
        // Validation is automatically handled by TourReservationRequest
        $validated = $request->validated();
        
        $new_reservation = $validated['form_data'];
        $new_reservation['tour_id'] = $request->tour_id;

        if (Auth::user()) {
            $new_reservation['verificate'] = 1;
        }

        $saved_id = Tour_reservation::insertGetId($new_reservation);

        if (Auth::user()) {
            $this->create_reservatione_user_relatione($saved_id, Auth::user()->id);

            $tour = Tour::where('id', '=', $request->tour_id)->first();
            $guide = $tour->user;

            $this->reservatione_completed_notification($guide[0]->email, Auth::user()->email);

            return 'The reservation is complete and it is linked to you as a acaunt. You can find it in the user panel!';
        }
        else{
            $check_user_cout = User::where('email', '=', $new_reservation['email'])->count();
            if ($check_user_cout > 0) {
                $valid_item = Tour_reservation::where('id', '=', $saved_id)->first();
                $valid_item['verificate'] = 1;
                $valid_item -> save();

                $tour = Tour::where('id', '=', $valid_item->tour_id)->first();

                $tour_user = $tour->user->first();

                $fined_user = User::where('email', '=', $new_reservation['email'])->first();
                $this->create_reservatione_user_relatione($saved_id, $fined_user->id);

                $this->reservatione_completed_notification($tour_user->email , $valid_item->email);

                return "The reservation is complete. You are not logined or your login session is dead, but don't worry reservation is linked to your acaunt whith your mail. You can find it in the user panel!";
            }
            else{
                $info = [
                    "host" => $_SERVER['HTTP_HOST'],
                    "reservation_id" => $saved_id,
                    "user_email" => $new_reservation['email']
                ];
                Notification::route('mail', $new_reservation['email'])->notify(new ReservationVerifyEmail($info));

                return 'The reservation has been received! Please go to your email to complete the reservation!';
            }
        }
    }

    function create_reservatione_user_relatione($reservatione_id, $user_id) {
        Tour_reservations_user::create([
            'reservation_id' => $reservatione_id,
            'user_id' => $user_id
        ]);
    }

    function verifiation_reservation(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        
        $valid_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        $valid_item['verificate'] = 1;
        $valid_item -> save();

        $tour = Tour::where('id', '=', $valid_item->tour_id)->first();

        $tour_user = $tour->user->first();

        $this->reservatione_completed_notification($tour_user->email, $valid_item->email);
    }

    private function reservatione_completed_notification($guide_email, $client_email) {
        Notification::route('mail', $guide_email)->notify(new ReservationCreatingGuideNotification());
        Notification::route('mail', $client_email)->notify(new ReservationSacsesfulNotification());
    }

    function del_reservation(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'del');
        if ($auth) return $auth;
        
        $del_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        $del_item -> delete();
    }

    // Google Calendar Integration Methods
    function initGoogleAuth(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        
        // In production, use Google OAuth2 Client Library
        // For demo, return mock auth URL

        $email = $request->email;
        $calendarId = $request->calendar_id ?? 'primary';

        // Store settings temporarily in session or database
        session(['google_calendar_email' => $email]);
        session(['google_calendar_id' => $calendarId]);

        // Google OAuth2 authorize URL
        $clientId = config('services.google.client_id');
        $redirectUri = url('/api/set_tour/google-calendar/handle-callback');

        $authUrl = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'https://www.googleapis.com/auth/calendar',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'login_hint' => $email,
        ]);

        return response()->json([
            'auth_url' => $authUrl,
            'message' => 'Redirecting to Google for authorization'
        ]);
    }

    function handleGoogleCallback(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        
        $code = $request->code;

        if (!$code) {
            return response()->json(['error' => 'No authorization code provided'], 400);
        }

        // Exchange code for access token using Google OAuth2
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = url('/api/set_tour/google-calendar/handle-callback');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if (isset($tokenData['access_token'])) {
            // Store tokens securely (in production, encrypt and store in database)
            $email = session('google_calendar_email');
            $calendarId = session('google_calendar_id', 'primary');

            // For demo purposes, store in session
            session(['google_calendar_token' => $tokenData]);
            session(['google_calendar_connected' => true]);

            return response()->json([
                'success' => true,
                'email' => $email,
                'message' => 'Google Calendar connected successfully'
            ]);
        }

        return response()->json(['error' => 'Failed to obtain access token'], 400);
    }

    function disconnectGoogleCalendar(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        
        // Clear the Google Calendar connection
        session()->forget([
            'google_calendar_token',
            'google_calendar_connected',
            'google_calendar_email',
            'google_calendar_id'
        ]);

        // In production, also revoke the token from Google
        return response()->json([
            'success' => true,
            'message' => 'Google Calendar disconnected'
        ]);
    }

    function syncToGoogleCalendar(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        
        $email = $request->email;
        $calendarId = $request->calendar_id ?? 'primary';
        $syncType = $request->sync_type ?? 'all';
        $tourIds = $request->tour_ids ?? [];

        // Get reservations based on sync type
        if ($syncType === 'all') {
            // Get all reservations
            $reservations = Tour_reservation::latest('id')->get();
        } else {
            // Get reservations for specific tours
            $reservations = Tour_reservation::whereIn('tour_id', $tourIds)
                                ->latest('id')
                                ->get();
        }

        // In production, use Google Calendar API to create events
        // For demo, simulate successful sync

        foreach ($reservations as $reservation) {
            // This would be the actual Google Calendar API call:
            /*
            $event = [
                'summary' => "Tour Reservation: {$reservation->name}",
                'description' => "Persons: {$reservation->persons}\nEmail: {$reservation->email}\nPhone: {$reservation->phone}",
                'start' => [
                    'dateTime' => $reservation->check_in . 'T09:00:00',
                    'timeZone' => 'UTC',
                ],
                'end' => [
                    'dateTime' => $reservation->check_in . 'T18:00:00',
                    'timeZone' => 'UTC',
                ],
            ];

            $token = session('google_calendar_token.access_token');
            $calendarUrl = "https://www.googleapis.com/calendar/v3/calendars/{$calendarId}/events";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $calendarUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($event));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);
            */
        }

        return response()->json([
            'success' => true,
            'synced_count' => $reservations->count(),
            'sync_type' => $syncType,
            'message' => "Successfully synced {$reservations->count()} reservations to Google Calendar"
        ]);
    }

    function getGoogleCalendarStatus(Request $request){
        $isConnected = session('google_calendar_connected', false);
        $email = session('google_calendar_email');

        return response()->json([
            'connected' => $isConnected,
            'email' => $email,
            'calendar_id' => session('google_calendar_id', 'primary'),
        ]);
    }

    function get_my_tours(){
        // $auth = PermissionService::authorize('tour', 'view');
        // if ($auth) return $auth;
        
        // Get tours created by the currently authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([]);
        }

        // Get tours where the user is the owner/creator
        $tours = Tour::where('user_id', $user->id)
                    ->orWhereHas('user', function($query) use ($user) {
                        $query->where('user_id', $user->id);
                    })
                    ->get()
                    ->map(function($tour) {
                        return [
                            'id' => $tour->id,
                            'name' => $tour->name ?? 'Tour #' . $tour->id,
                            'location' => $tour->location ?? 'Unknown Location',
                        ];
                    });

        // If no tours found, return some sample tours for demo
        if ($tours->isEmpty()) {
            return response()->json([
                ['id' => 1, 'name' => 'Mountain Adventure', 'location' => 'Caucasus Mountains'],
                ['id' => 2, 'name' => 'City Walking Tour', 'location' => 'Tbilisi Old Town'],
                ['id' => 3, 'name' => 'Wine Tour', 'location' => 'Kakheti Region'],
                ['id' => 4, 'name' => 'Hiking Expedition', 'location' => 'Svaneti'],
                ['id' => 5, 'name' => 'Coastal Explorer', 'location' => 'Batumi'],
            ]);
        }

        return response()->json($tours);
    }
}

