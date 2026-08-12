<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class TrainingPlan extends Model
{
    protected $guarded = [];

    protected $table = 'training_plans';

    public function sessions()
    {
        return $this->hasMany(PlanSession::class, 'plan_id')->orderBy('day_index');
    }

    public function translations()
    {
        return $this->hasMany(PlanTranslation::class, 'plan_id');
    }
}
