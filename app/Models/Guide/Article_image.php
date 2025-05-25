<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_image extends Model
{
    use HasFactory;

	public function article()
	{
        return $this->hasOne(Article::class, 'id', 'article_id');
	}
}
