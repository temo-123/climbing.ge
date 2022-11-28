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
    ];

	public function article()
	{
        return $this->belongsToMany(Article::class, 'gallery_image_article', 'image_id', 'article_id');
	}

	public function category()
	{
		return $this->belongsTo(Gallery_category::class, 'category_id', 'id');
	}
}
