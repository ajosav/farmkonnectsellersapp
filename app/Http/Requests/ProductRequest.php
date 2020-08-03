<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('Farm Manager');
    }

    public function prepareForValidation() {
        // $start_date = $this->get('startDate');
        // $end_date = $this->get('finishDate');
        // $this->merge(['startDate' => Carbon::parse($start_date)->toDateString()]);
        // $this->merge(['finishDate' => Carbon::parse($end_date)->toDateString()]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'required',
            'name' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'unit_id' => 'required',
            'sale_unit_id' => 'required',
            'purchase_unit_id' => 'required',
            'image' => 'required',
            'startDate' => 'required|date',
            'finishDate' => 'required|date|after:yesterday',
        ];
    }

    public function messages() {
        return [
            'unit_id.required' => 'Unit filed is required',
            'sale_unit_id.required' => 'Sale Unit is required',
            'purchase_unit_id.required' => 'Purchase Unit is required'
        ];
    }
}
