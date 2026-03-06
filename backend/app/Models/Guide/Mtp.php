<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mtp extends Model
{
    public $table = 'mtps';

    protected $fillable = [
        "sector_id",
        "title",
        "text",
        "last_carabin",
        "height",
    ];

    public function pitchs()
    {
        return $this->hasMany(Mtp_pitch::class);
    }
}
