<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class QuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            // 'couchtype' => 'required|in:indoor,outdoor',
            // 'fabrictype' => 'required|in:velvet,leather',
            // 'legtype' => 'required|in:wood,metal',
            // 'seatertype' => 'required|in:4,2',
            // 'discount' => 'nullable|string',
            // 'country'   => 'required|in:UK,US',
        ];
    }

    public function messages()
    {
        return [
            // 'couchtype' => 'couch type required',
            // 'fabrictype'   => 'fabric type required',
            // 'legtype'       => 'leg type required',
            // 'seatertype'     => 'number of seater required',
            // 'country'   => 'country required',
        ];
    }
}
