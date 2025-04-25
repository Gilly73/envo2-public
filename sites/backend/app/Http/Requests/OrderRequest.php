<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'quote.quoteId' => 'required|integer|exists:quotes,id',
            'customer.id' => 'required|integer|exists:customers,id',
            'quote.totalCost' => 'required|decimal:2',
            'payment.paymentIntent.currency' => 'required|string|in:gbp,usd',
            'payment.paymentIntent.status' => 'required',
        ];
    }
}
