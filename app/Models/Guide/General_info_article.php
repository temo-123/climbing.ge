<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class General_info_article extends Model
{
    use HasFactory;

    // public $table = 'article_general_infos';
    public $table = 'general_info_article';

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

	public function article()
	{
		return $this->hasOne(Article::class, 'id');
	}
}
