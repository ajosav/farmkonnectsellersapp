<?php

namespace App\Model;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Model\Unit;

class Order extends Model
{
    use HasUUID, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'uuid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'uuid');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function check_delivery($uuid)
    {
        return  $state = Delivery::where('order_id', $uuid)->where(function ($query) {
            $query->where('status', 1)->orWhere('status', 2)->orWhere('status', 3)->orWhere('status', 4);
        })->exists();
    }
}