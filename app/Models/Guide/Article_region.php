<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_region extends Model
{
    use HasFactory;

    public $table = 'article_region';

    protected $fillable = [
        'region_id',
        'article_id',
	];

	public function region()
	{
		return $this->hasOne(Region::class, 'id');
	}

	public function article()
	{
		return $this->hasOne(Article::class, 'id');
	}
}
