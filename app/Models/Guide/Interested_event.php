<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Interested_event extends Model
{
    public $table = 'interested_events';

    // protected $fillable = [
    //     "user_id",
    //     "event_id",
    // ];

    public function event()
    {
		return $this->hasOne(Event::class, 'id', 'event_id');
    }
}
