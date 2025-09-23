<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        // Add all column names from your warehouses table, for example:
        'name',
        'general'
    ];

    public function productOptions() {
        return $this->belongsToMany(ProductOption::class, 'product_option_warehouse');
    }
}
