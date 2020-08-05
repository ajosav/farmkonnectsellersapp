<?php

namespace App;

use App\Model\Product;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasUUID, SoftDeletes, HasRoles;

    // public static function booted() {
    //     static::creating(function ($user) {
    //         app('log')->info('Tosin is creating a new user '.$user->name);
    //     });

    //     static::created(function ($user) {
    //         app('log')->info('Tosin created new user '.$user->name);
    //     });
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'position'
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


    public function farmManagerProfile() {
        return $this->hasOne(FarmManagerProfile::class, 'user_uuid', 'uuid');
    }

    public function logisticCompanyProfile() {
        return $this->hasOne(LogisticCompanyProfile::class, 'user_uuid', 'uuid');
    }

    public function commodityConsumerProfile() {
        return $this->hasOne(CommodityConsumerProfile::class, 'user_uuid', 'uuid');
    }

    public function commodityDistributorProfile() {
        return $this->hasOne(CommodityDistributorProfile::class, 'user_uuid', 'uuid');
    }

    public function commodityRetailerProfile() {
        return $this->hasOne(CommodityRetailerProfile::class, 'user_uuid', 'uuid');
    }

    public function positionName() {
        return $this->hasOne(UserPosition::class, 'id', 'position');
    }

    public function userProducts() {
        return $this->hasMany(Product::class, 'created_by', 'uuid');
    }

    public function order() {
        return $this->hasMany(Order::class, 'user_id', 'uuid');
    }
}
