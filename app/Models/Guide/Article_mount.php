<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article_mount extends Model
{
    use HasFactory;

    public $table = 'article_mount';

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
