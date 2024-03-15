<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Tour_reservation;

class TourReservationController extends Controller
{
    function get_reservations(){
        return Tour_reservation::get();
    }
    
    function create_reservation(Request $request){
        $new_reservation = new Tour_reservation;
        $new_reservation -> create($request->data);

        // tour werifiate notificatione for reserved user
    }

    function verifiation(Request $request){
        $valid_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        $valid_item['verificate'] = 1;
        $valid_item -> save();

        // tour reservation notificatione for tour menager
    }
                
    function del_reservation(Request $request){
        $del_item = Tour_reservation::where('id', '=', $request->reservation_id)->first();
        $del_item -> delete();
    }
}
