<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuoteRequest extends FormRequest
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
            'couchtype' => 'required|integer|exists:couch_types,id',
            'styletype' => 'required|integer|exists:styles,id',
            'fabrictype' => 'required|integer|exists:fabrics,id',
            'legtype' => 'required|integer|exists:legs,id',
            'seatertype' => 'required|integer|exists:seaters,id',
            'discount' => 'nullable|string',
            'country' => 'required|in:UK,US',
        ];
    }
}
