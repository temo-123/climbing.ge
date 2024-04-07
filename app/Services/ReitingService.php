<?php

namespace App\Services;

use Auth;

class ReitingService
{
    public static function colculate_stars($revs){
        if($revs->count() > 0){
            $total = 0;
            foreach ($revs as $rev) {
                $total = $total + $rev->stars;
            }
            $total = $total / $revs->count();

            return [
                "stars" => $total,
                "count" => $revs->count(),
            ];
        }
        else{
            return [
                "stars" => 0,
                "count" => 0,
            ];
        }
    }
    
}
