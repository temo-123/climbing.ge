<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General_info extends Model
{
    use HasFactory;

    public $table = "general_infos";

    protected $fillable = [
        'title_us',
		'title_ka',
		'title_ru',
		'text_ru',
		'text_ka',
		'text_us',
	];
}
