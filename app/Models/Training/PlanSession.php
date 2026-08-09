<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class PlanSession extends Model
{
    protected $guarded = [];

    protected $table = 'plan_sessions';
    public $timestamps = false;

    public function plan()
    {
        return $this->belongsTo(TrainingPlan::class, 'plan_id');
    }

    public function planSessionTrainings()
    {
        return $this->hasMany(PlanSessionTraining::class, 'plan_session_id')->orderBy('sort_order');
    }
}
