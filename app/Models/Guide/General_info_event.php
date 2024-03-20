<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General_info_event extends Model
{
    use HasFactory;

    public $table = 'general_info_event';

    protected $fillable = [
        'info_id',
        'article_id',
        'block',
        'block_action',
	];

	public function general_info()
	{
		return $this->hasOne(General_info::class, 'info_id');
	}

	public function event()
	{
		return $this->hasOne(Event::class, 'id');
	}
}
