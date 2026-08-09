<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingTranslation extends Model
{
    protected $guarded = [];

    protected $table = 'training_translations';
    public $timestamps = false;

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
