<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

/**
 * One switch of a reservation's guide in charge (history, TourGuideService::assign).
 * reason: manual (admin picked a guide) | handover (tour handed to another guide) |
 *         guide_removed (the guide left the tour)
 */
class Tour_reservation_guide_change extends Model
{
    protected $table = 'tour_reservation_guide_changes';

    protected $fillable = ['reservation_id', 'from_user_id', 'to_user_id', 'changed_by', 'reason', 'note'];
}
