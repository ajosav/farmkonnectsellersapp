<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{

    protected $guarded = [];

    //
    public function user()
    {
        return $this->belongsTo('App\User', 'uuid', 'user_id');
    }
}