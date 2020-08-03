<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProfileValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if(Gate::allows('Farm Manager')) {
            return [
                'farm_size' => 'required|string',
                'location' => 'required|string|max:250',
                'commodities_planted' => 'required',
                'contact_person_name' => 'required|string',
                'contact_phone_number' => 'required',
                'contact_email_address' => 'email',
                'contact_address' => 'string|max:250',
                'other_info' => 'sometimes',
                'lg' => 'required|string',
                'state' => 'required|string'
            ];
        } elseif(Gate::allows('Commodity Distributor') || Gate::allows('Commodity Retailer') || Gate::allows('Commodity Consumer')) {
            return [
                'state'=> 'required|string',
                'lg'=> 'required|string',
                'address'=> 'required|string',
                'name' =>  'required|string|max:150',
                'email' =>  'required|email',
                'phone' =>  'required|max:25',
                'other_info' => 'sometimes'
            ];
        } elseif(Gate::allows('Logistic Company')) {
            return [
                'state'=> 'required|string',
                'lg'=> 'required|string',
                'address'=> 'required|string',
                'name' =>  'required|string|max:150',
                'email' =>  'required|email',
                'phone' =>  'required|max:25',
                'driving_license' =>  'required',
                'other_info' => 'sometimes',
                'registration_no' => 'required',
                'chasis_no' => 'required',
                'license_expiration' => 'required|date:Y/m/d|after:yesterday'
            ];
        }

        throw new Exception('You are not authorized');

    }
}
