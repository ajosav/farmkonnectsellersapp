<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Carbon\Carbon;
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
    public function saleUnit() {
        return $this->hasOne(Unit::class, 'id', 'sale_unit_id');
    }
    public function purchaseUnit() {
        return $this->hasOne(Unit::class, 'id', 'purchase_unit_id');
    }

    public function productImage($path, $imageName) {
        return Storage::url($path.$imageName);
    }

    public function carbonParseDate($date) {
        return Carbon::parse($date);
    }
}
