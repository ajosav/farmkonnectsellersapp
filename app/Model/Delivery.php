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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}