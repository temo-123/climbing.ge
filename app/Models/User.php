<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    // use HasFactory;
    use HasApiTokens, HasFactory, Notifiable;

    // public function getAuthIdentifierName(){}
    // public function getAuthIdentifier(){}
    // public function getAuthPassword(){}
    // public function getRememberToken(){}
    // public function setRememberToken($value){}
    // public function getRememberTokenName(){}

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'surname', 
        'email',
        'phone_number',
        'country',
        'city',

        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * @param string|array $roles
    */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) || abort(401, 'This action is unauthorized. (parmisions error)');
        }
        return $this->hasRole($roles) || abort(401, 'This action is unauthorized. (parmisions error)');
    }
    /**
    * Check multiple roles
    * @param array $roles
    */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }



    public function role()
    {
    //   return $this->belongsToMany(Role::class, 'user_role');
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    public function permissions()
    {
    //   return $this->belongsToMany(Permission::class, 'user_permissions');
      return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    public function comments()
    {
      return $this->belongsToMany(Comment::class, 'article_comment_user', 'user_id', 'comment_id');
    }

    public function favorite_products()
    {
		  return $this->hasMany(Favorite_product::class, 'user_id');
    }

    public function favorite_outdoors()
    {
		  return $this->hasMany(Favorite_outdoor_area::class, 'user_id');
    }

    public function favorite_films()
    {
		  return $this->hasMany(Favorite_film::class, 'user_id');
    }

    public function interested_events()
    {
		  return $this->hasMany(Interested_event::class, 'user_id');
    }

    public function adreses()
    {
		  return $this->hasMany(User_adreses::class, 'user_id');
    }

    public function orders()
    {
		  return $this->hasMany(Order::class, 'user_id');
    }

    public function notification_list()
    {
		  return $this->hasOne(user_notification::class, 'user_id');
    }

    public function social_acount()
    {
        return $this->hasOne(Social_account::class, 'user_id');
    }
}
