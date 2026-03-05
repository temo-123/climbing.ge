<?php

namespace App\Http\Controllers\Api\Shop;

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

class UserTourReservationController extends Controller
{    
    function create_reservation(TourReservationRequest $request){
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
    
}
