<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_bisnes extends Model
{
    public $table = 'locale_bisneses';

    protected $fillable = [
		'id',
		'title',
		'short_description',
		'text',
	];
    
    use HasFactory;
}
