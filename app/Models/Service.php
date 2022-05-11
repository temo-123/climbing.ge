<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $table = 'services';

    protected $fillable = [
        'url_title',
    ];

	public function us_service()
	{
		return $this->hasOhe(Us_service::class);
	}

	public function ka_service()
	{
		return $this->hasOhe(Ka_service::class);
	}

	public function ru_service()
	{
		return $this->hasOhe(Ru_service::class);
	}
}
