<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    //
    use HasUUID;

    protected $guarded = [];

    public function order($order_id)
    {
        return Order::where('uuid', $order_id)->first();
    }
}