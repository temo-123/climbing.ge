<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $guarded = [];

    protected $table = 'trainings';

    public $incrementing = false;
    protected $keyType = 'string';

    public function steps()
    {
        return $this->hasMany(TrainingStep::class, 'training_id')->orderBy('step_order');
    }

    public function translations()
    {
        return $this->hasMany(TrainingTranslation::class, 'training_id');
    }
}
