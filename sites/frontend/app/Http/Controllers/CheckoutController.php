<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use App\Http\Requests\CheckoutRequest;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function checkout(CheckoutRequest $request)
    {

        $validatedData = $request->all();
   
        $endpoint = $this->apiClient->baseurl . '/payment/create-intent';
        $response = $this->apiClient->post($endpoint, $validatedData);
        info($response->status());
        if ($response->status() === 200) {
            if (request()->header('X-Inertia') || request()->expectsJson()) {
                return response()->json([
                    'clientSecret' => $response->json()['clientSecret'],
                    //'redirect' => route('customer.checkout')
                ]);
            }
            return Inertia::location(route('customer.checkout'));
        }
        //Error
        if (request()->header('X-Inertia') || request()->expectsJson()) {
            info('axios checkout error');
            return response()->json([
                'error' => $response->json()
            ]);
        }
        return redirect()->route('customer.checkout')->with('error', ' Checkout Failed');
    }
}
