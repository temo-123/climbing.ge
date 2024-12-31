<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

use App\Models\User\Role;
use App\Models\User\Permission;
use App\Models\User\User_adreses;
use App\Models\User\user_notification;
// use App\Models\User\Social_account;
use App\Models\User\User_site;

use App\Models\Guide\Comment;
use App\Models\Guide\Sport_route_review;
use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Interested_event;

use App\Models\Shop\Product_feedback;
use App\Models\Shop\Favorite_product;
use App\Models\Shop\Order;
use App\Models\Shop\Product;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_reservation;

use App\Models\Films\Favorite_film;

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
      return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }



    public function adreses()
    {
		  return $this->hasMany(User_adreses::class, 'user_id');
    }
    public function sites()
    {
		  return $this->hasMany(User_site::class, 'user_id');
    }
    public function notification_list()
    {
		  return $this->hasOne(user_notification::class, 'user_id');
    }
    // public function social_acount()
    // {
    //     return $this->hasOne(Social_account::class, 'user_id');
    // }


    /*
    *   User cooments & rewiew
    */
    public function article_comments()
    {
      return $this->belongsToMany(Comment::class, 'article_comment_user', 'user_id', 'comment_id');
    }
	// public function sport_route_reviews()
	// {
	// 	return $this->belongsToMany(Sport_route_review::class, 'sport_route_review_user', 'user_id', 'review_id');
	// }
	// public function product_feedbacks()
	// {
    //     return $this->belongsToMany(Product_feedback::class, 'product_feedback_user', 'user_id', 'feedback_id');
	// }
	public function sport_route_reviews()
	{
		return $this->hasMany(Sport_route_review::class, 'user_id');
	}
	public function product_feedbacks()
	{
        // return $this->hasMany(Product_feedback::class, 'user_id');
        return $this->belongsToMany(Product_feedback::class, 'user_product_feedbacks', 'user_id', 'feedback_id');

	}


    /*
    *   User favorites
    */
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


    /*
    *   User product and orders
    */
    public function purchases()
    {
        return $this->hasMany(Order::class, 'user_id', 'product_id');
    }
    public function orders()
    {
        return $this->belongsToMany(Product::class, 'user_products', 'user_id', 'product_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'user_products', 'user_id', 'product_id');
    }


    /*
    *   User tour and reservation
    */
    public function tours()
    {
        return $this->belongsToMany(Tour::class, 'user_tours', 'user_id', 'tour_id');
    }
    public function reservation()
    {
        return $this->belongsToMany(Tour_reservation::class, 'tour_reservation_users', 'user_id', 'reservation_id')->latest('id');
    }
}
