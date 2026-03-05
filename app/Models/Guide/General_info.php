<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General_info extends Model
{
    use HasFactory;

    public $table = "general_infos";

    protected $fillable = [
        'title',
		'text_ka',
		'text_us',
		'is_show',
	];


	public function article()
	{
        return $this->belongsToMany(Article::class, 'general_info_article', 'info_id', 'article_id');
	}

	public function event()
	{
        return $this->belongsToMany(Event::class, 'general_info_article', 'info_id', 'event_id');
	}
}
