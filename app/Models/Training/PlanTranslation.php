<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class PlanTranslation extends Model
{
    protected $guarded = [];

    protected $table = 'plan_translations';
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo(TrainingPlan::class, 'plan_id');
    }
}
