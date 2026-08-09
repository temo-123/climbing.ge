<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingStep extends Model
{
    protected $guarded = [];

    protected $table = 'training_steps';
    public $timestamps = false;

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
