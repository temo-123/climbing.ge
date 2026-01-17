<?php

namespace App\Models\Sanctum;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

// class PersonalAccessToken extends Model
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'abilities' => 'json',
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // Remove expires_at attribute before saving to avoid column not found error
        if (isset($this->attributes['expires_at'])) {
            unset($this->attributes['expires_at']);
        }
        
        // Also remove from the original attributes if set
        if (isset($this->original['expires_at'])) {
            unset($this->original['expires_at']);
        }

        $changes = $this->getDirty();
        // Check for 2 changed values because one is always the updated_at column
        if (! array_key_exists('last_used_at', $changes) || count($changes) > 2) {
            return parent::save($options);
        }
        return false;
    }
    
    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Block expires_at from being set
        if ($key === 'expires_at') {
            return $this;
        }
        
        return parent::setAttribute($key, $value);
    }
}
