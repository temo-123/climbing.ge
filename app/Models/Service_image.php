<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service_image extends Model
{
    public $table = 'service_images';

    protected $fillable = [
        'image',
        "service_id"
    ];

    public function service()
    {
		return $this->hasMany(Service::class,);
    }
}
