<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public $table = 'events';


	public function us_event()
	{
		return $this->hasOne(Locale_event::class, 'id', 'us_event_id');
	}

	public function ka_event()
	{
		return $this->hasOne(Locale_event::class, 'id', 'ka_event_id');
	}

	public function ru_event()
	{
		return $this->hasOne(Locale_event::class, 'id', 'ru_event_id');
	}
}
