<?php

namespace App;

use Exception;
use Illuminate\Database\Eloquent\Model;
use BinaryCabin\LaravelUUID\Traits\HasUUID;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmManagerProfile extends Model
{
    use HasUUID, SoftDeletes;
    protected $guarded = [];


    public static function createProfile($request) {
        $commodities_planted = implode(',', $request->commodities_planted);
        try {
            self::updateOrCreate(['user_uuid' => $request->user()->uuid], [
                'state'=> $request->state,
                'commodities_planted' => $commodities_planted,
                'farm_size' => $request->farm_size,
                'lg'=> $request->lg,
                'location'=> $request->location,
                'contact_person' => $request->contact_person_name,
                'c_person_address'=> $request->contact_address,
                'c_person_email'=> $request->contact_email_address,
                'c_person_phone'=> $request->contact_phone_number,
                'c_person_others'=> $request->other_info,
            ]);
        } catch(Exception $e) {
            throw new Exception('Error Creating User Profile');
        }
    }
}
