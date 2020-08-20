<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Order;

class Product extends Model
{
    use HasUUID, SoftDeletes;
    protected $guarded = [];

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by', 'uuid');
    }

    public function unit()
    {
        return $this->hasOne(Unit::class, 'id', 'unit_id');
    }

    public function saleUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'sale_unit_id');
    }

    public function purchaseUnit()
    {
        return $this->hasOne(Unit::class, 'id', 'purchase_unit_id');
    }

    public function product_image($path)
    {
        return Storage::url($path);
    }

    public function carbonParseDate($date)
    {
        return Carbon::parse($date);
    }

    public function order()
    {
        return $this->hasMany(Order::class, 'order_id', 'uuid');
    }

    public function getImageAttribute($image)
    {
        return explode(',', $image);
    }

    public function calcProductPrice($base_unit_operation_value, $operator, $operational_value, $price, $quantity)
    {
        if ($operator == '*') {

            $total_quantity = (float) ($base_unit_operation_value * $operational_value) * $quantity;
            $price_of_product = $total_quantity * $price;
        } else if ($operator == '/') {

            $total_quantity = (float) ($base_unit_operation_value / $operational_value) * $quantity;
            $price_of_product = $total_quantity * $price;
        }

        $response = [
            'total_quantity' => $total_quantity,
            'price_of_product' => $price_of_product
        ];

        return $response;
    }
}