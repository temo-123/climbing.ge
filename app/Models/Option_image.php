<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option_image extends Model
{
    public $table = 'option_images';

    protected $fillable = [
        'image',
        "product_id"
    ];


    public function option()
    {
        return $this->hasMany(Product_option::class, 'id');
    }
}
