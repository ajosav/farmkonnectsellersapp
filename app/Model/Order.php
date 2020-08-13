<?php

namespace App\Model;

use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasUUID, SoftDeletes;

    protected $fillable = ['quantity_ordered', 'total_price'];


    public function user()
    {
        return $this->belongsTo(User::class, 'uuid', 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'uuid');
    }
}