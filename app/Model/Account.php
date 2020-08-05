<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes, HasUUID;
    //
    protected $table = 'bank_accounts';

    protected $guarded = [];
    //

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'uuid');
    }
}