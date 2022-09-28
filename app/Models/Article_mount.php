<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_mount extends Model
{
    use HasFactory;

    protected $fillable = [
        'mount_id',
        'article_id',
	];

	public function mount()
	{
		return $this->hasOne(Mount::class, 'id');
	}

	public function article()
	{
		return $this->hasOne(Article::class, 'id');
	}
}
