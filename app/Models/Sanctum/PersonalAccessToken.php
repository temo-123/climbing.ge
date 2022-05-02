<?php

namespace App\Models\Sanctum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

// class PersonalAccessToken extends Model
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    public function save(array $options = [])
    {
        $changes = $this->getDirty();
        // Check for 2 changed values because one is always the updated_at column
        if (! array_key_exists('last_used_at', $changes) || count($changes) > 2) {
            parent::save();
        }
        return false;
    }
    
}
