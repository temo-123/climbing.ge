<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery_image extends Model
{
    use HasFactory;

    public $table = 'gallery_images';

    protected $fillable = [
        'image_type',
        'title',
        'text',
        'link',
        'published',

        'category_id',
        'article_id',
    ];

	public function article()
	{
		return $this->hasOhe(Article::class);
	}

	public function category()
	{
		return $this->hasOhe(galery_image_category::class);
	}
}
