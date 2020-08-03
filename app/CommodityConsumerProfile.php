<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommodityConsumerProfile extends Model
{
    use HasUUID, SoftDeletes;

    protected $guarded = [];


    public static function createProfile($request) {
        try {
            self::updateOrCreate(['user_uuid' => $request->user()->uuid], [
                'state'=> $request->state,
                'lg'=> $request->lg,
                'location'=> $request->address,
                'address'=> $request->address,
                'name' =>  $request->name,
                'email' =>  $request->email,
                'phone' =>  $request->phone,
                'other_info' => $request->other_info
            ]);
        } catch(Exception $e) {
            throw new Exception('Error Creating User Profile');
        }
    }
}
