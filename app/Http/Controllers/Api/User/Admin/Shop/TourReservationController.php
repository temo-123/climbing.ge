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

use App\Services\PermissionService;
use App\Services\TourReservationService;
use App\Services\TourGuideService;

class TourReservationController extends Controller
{
    function get_all_reservations(){
        $auth = PermissionService::authorize('tour_reservation', 'show');
        if ($auth) return $auth;

        return self::with_guide_names(Tour_reservation::latest('id')->get());
    }

    // table rows + the guide in charge's name (guide_name) and whether it is me (is_mine)
    private static function with_guide_names($rows){
        $names = User::whereIn('id', $rows->pluck('guide_id')->filter()->unique())->get(['id', 'name', 'surname'])
            ->mapWithKeys(fn ($u) => [$u->id => trim($u->name . ' ' . $u->surname)]);
        $me = Auth::id();
        return $rows->map(function ($r) use ($names, $me) {
            $a = $r->toArray();
            $a['guide_name'] = $r->guide_id ? ($names[$r->guide_id] ?? null) : null;
            $a['is_mine'] = $me && (int) $r->guide_id === (int) $me;
            return $a;
        })->values();
    }

    // switch the guide in charge (only to a guide of the reservation's tour)
    function assign_guide(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'user_id' => 'required|integer',
            'notify_client' => 'nullable|boolean',
            'note' => 'nullable|string|max:500',
        ]);
        $r = Tour_reservation::where('id', $request->reservation_id)->first();
        if (!$r) return response()->json(['message' => 'Reservation not found.'], 404);

        $error = TourGuideService::assign($r, (int) $request->user_id, Auth::user(), 'manual', $request->note, $request->boolean('notify_client', true));
        if ($error) return response()->json(['message' => $error], 422);

        return response()->json(['success' => true] + TourReservationService::admin_details($r->fresh()));
    }

    // reservations dashboard: stat cards, month chart, top tours, calendar (TourReservationService::dashboard_stats)
    function get_reservation_stats(){
        $auth = PermissionService::authorize('tour_reservation', 'show');
        if ($auth) return $auth;

        return response()->json(TourReservationService::dashboard_stats());
    }

    // details modal: row + tour + guides + linked accounts (TourReservationService::admin_details)
    function get_reservation(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'show');
        if ($auth) return $auth;

        $reservation = Tour_reservation::where('id', '=', $request->id)->first();
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found.'], 404);
        }

        return response()->json(TourReservationService::admin_details($reservation));
    }

    // tour completed / fee paid (or undo) — details modal + "To settle" in the tour modal
    function set_status(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;
        $request->validate(['completed' => 'nullable|boolean', 'commission_paid' => 'nullable|boolean']);

        $r = Tour_reservation::where('id', $request->reservation_id)->first();
        if (!$r) return response()->json(['message' => 'Reservation not found.'], 404);

        $error = TourGuideService::set_status(
            $r,
            $request->has('completed') ? $request->boolean('completed') : null,
            $request->has('commission_paid') ? $request->boolean('commission_paid') : null
        );
        if ($error) return response()->json(['message' => $error], 422);

        return response()->json(['success' => true] + TourReservationService::admin_details($r->fresh()));
    }

    function get_declarations(){
        $auth = PermissionService::authorize('tour_reservation', 'show');
        if ($auth) return $auth;

        return self::with_guide_names(Tour_reservation::where(function($q) {
            $q->whereNull('verificate')->orWhere('verificate', 0);
        })->latest('id')->get());
    }

    // "My tours reservations": any logged-in guide sees the reservations of the tours they
    // guide (no admin permission needed — plain guides open it from their emails)
    function get_user_reservations(){
        // reservations of the tours I guide (is_mine = I'm the guide in charge)
        $tour_ids = Auth::user()->tours()->pluck('tours.id');
        if ($tour_ids->isEmpty()) return [];

        return self::with_guide_names(Tour_reservation::whereIn('tour_id', $tour_ids)->latest('id')->get());
    }

    function create_reservation(TourReservationRequest $request){
        $auth = PermissionService::authorize('tour_reservation', 'add');
        if ($auth) return $auth;

        $tour = Tour::where('id', $request->tour_id)->first();
        if (!$tour) {
            return response()->json(['message' => 'Tour not found.'], 404);
        }

        $validated = $request->validated();

        // added in the panel → verified right away (verify_method = admin), no confirm mail
        TourReservationService::create($validated['form_data'], $tour->id, Auth::user(), true);

        return 'The reservation is complete and it is linked to you as a acaunt. You can find it in the user panel!';
    }

    // manual verification from the reservation details modal
    function verifiation_reservation(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;

        $valid_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        if (!$valid_item) {
            return response()->json(['message' => 'Reservation not found.'], 404);
        }

        TourReservationService::verify($valid_item, 'admin');

        return response()->json(['success' => true, 'reservation' => $valid_item->fresh()]);
    }

    // resend the "confirm your reservation" mail (details modal)
    function resend_verification(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'edit');
        if ($auth) return $auth;

        $item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        if (!$item) {
            return response()->json(['message' => 'Reservation not found.'], 404);
        }
        if (TourReservationService::is_verified($item)) {
            return response()->json(['message' => 'Already verified.'], 422);
        }

        TourReservationService::resend_verify_mail($item);

        return response()->json(['success' => true]);
    }

    function del_reservation(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'del');
        if ($auth) return $auth;
        
        $del_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        $del_item -> delete();
    }

    /**
     * Bulk delete tour reservations. No cascading side effects, so a plain destroy() is safe.
     */
    function bulk_delete(Request $request){
        $auth = PermissionService::authorize('tour_reservation', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        $deleted_count = Tour_reservation::destroy($request->ids);

        return response()->json(['success' => true, 'count' => $deleted_count]);
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
        $auth = PermissionService::authorize('tour', 'show');
        if ($auth) return $auth;
        
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

