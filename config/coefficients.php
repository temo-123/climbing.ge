<?php

// Default values for every coefficient the app reads through
// App\Services\CoefficientService. A row with the same slug in the
// `coefficients` table (Site Options -> Coefficients in the admin dashboard)
// overrides the value here; these defaults only apply while no row exists.
return [
    // Climbing wall price calculator (shop) — prices in $.
    'wall_square_price'              => 120,  // wall surface, per m²
    'wall_discipline_bouldering'     => 0.85, // price multiplier
    'wall_discipline_sport_climbing' => 1.0,  // price multiplier
    'wall_structure_indoor'          => 1.0,  // price multiplier
    'wall_structure_outdoor'         => 1.15, // price multiplier
    'wall_structure_standfree_indoor'  => 1.1, // price multiplier
    'wall_structure_standfree_outdoor' => 1.3, // price multiplier
    'wall_mat_price'                 => 80,   // safety mat, per m² (per m³ for bouldering mats)
    'wall_protection_anchor_price'   => 50,   // per bolted anchor
    'wall_protection_rope_price'     => 10,   // per meter of rope
    'wall_hold_price'                => 10,   // per hold
    'wall_foundation_price'          => 300,  // per linear meter of wall width
    'wall_foundation_depth_ratio'    => 0.12, // footing depth as a fraction of wall height
    'wall_roof_price'                => 15,   // per m²
    'wall_vat_percent'               => 18,   // %

    // Shop delivery period, in business days.
    'delivery_online_order_min_days'      => 2,
    'delivery_online_order_max_days'      => 4,
    'delivery_produced_by_order_min_days' => 5,
    'delivery_produced_by_order_max_days' => 9,

    // Climber profile points awarded per contribution (see User::pointsTotal()).
    'points_route_review' => env('POINTS_ROUTE_REVIEW', 5),
    'points_mtp_review'   => env('POINTS_MTP_REVIEW', 5),
    'points_ascent'       => env('POINTS_ASCENT', 10),
    'points_comment'      => env('POINTS_COMMENT', 2),
];
