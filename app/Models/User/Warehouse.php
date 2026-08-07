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
        'general',
        'is_sale_point'
    ];

    // Without these, the raw DB tinyint(1) values (0/1) serialize to JSON as
    // plain numbers, and Vue's checkbox v-model (which loosely compares
    // against boolean `true`) never shows them as checked even when true —
    // the edit form silently failed to reflect either flag's real state.
    protected $casts = [
        'general' => 'boolean',
        'is_sale_point' => 'boolean',
    ];

    public function productOptions() {
        return $this->belongsToMany(Product_option::class, 'warehouses_product_options')->withPivot('quantity');
    }
}
