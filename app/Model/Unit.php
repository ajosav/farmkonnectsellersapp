<?php

namespace App\Model;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasUUID, SoftDeletes;

    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'unit_id');
    }
}