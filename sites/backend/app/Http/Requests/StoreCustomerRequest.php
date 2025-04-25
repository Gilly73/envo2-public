<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'firstName' => 'required|string|max:255',
            'surname'   => 'required|string|max:255',
            'dob'       => 'required|date_format:Y-m-d',
            'email'     => 'required|string|email:dns|max:255',
            'mobile'    => 'required|string|max:20',
            'address1'  => 'required|string|max:255',
            'address2'  => 'nullable|string|max:255', // Optional field
            'city'      => 'required|string|max:255',
            'county'    => 'required|string|max:255',
            'postcode'  => 'required|string|max:20',
            'country'   => 'required|string|max:255',
        ];
    }
}
