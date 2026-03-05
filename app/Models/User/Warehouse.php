<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop\Product_option;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add all column names from your warehouses table, for example:
        'name',
        'general'
    ];

    public function productOptions() {
        return $this->belongsToMany(Product_option::class, 'warehouses_product_options')->withPivot('quantity');
    }
}
