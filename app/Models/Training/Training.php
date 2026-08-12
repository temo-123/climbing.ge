<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $guarded = [];

    protected $table = 'trainings';

    public function steps()
    {
        return $this->hasMany(TrainingStep::class, 'training_id')->orderBy('step_order');
    }

    public function translations()
    {
        return $this->hasMany(TrainingTranslation::class, 'training_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Shop\Product::class, 'product_id');
    }
}
