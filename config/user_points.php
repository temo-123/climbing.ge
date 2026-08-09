<?php

// Points awarded to a user for each kind of contribution. Read via
// config('user_points.<type>') in App\Services\UserPointsService.
return [
    'route_review' => env('POINTS_ROUTE_REVIEW', 5),
    'mtp_review'   => env('POINTS_MTP_REVIEW', 5),
    'ascent'       => env('POINTS_ASCENT', 10),
    'comment'      => env('POINTS_COMMENT', 2),
];
