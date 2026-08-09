<?php

namespace App\Models\Training;

use Illuminate\Database\Eloquent\Model;

class PlanSessionTraining extends Model
{
    protected $guarded = [];

    protected $table = 'plan_session_trainings';
    public $timestamps = false;

    public function session()
    {
        return $this->belongsTo(PlanSession::class, 'plan_session_id');
    }

    public function training()
    {
        return $this->belongsTo(Training::class, 'training_id');
    }
}
