<?php

namespace App\Model;

use App\User;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasUUID, SoftDeletes;

    public function created_by() {
        return $this->belongsTo(User::class, 'uuid', 'created_by');
    }

    protected $guarded = [];

    public function unit() {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }
}
