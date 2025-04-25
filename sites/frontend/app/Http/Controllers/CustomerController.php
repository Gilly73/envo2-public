<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use App\Http\Requests\StoreCustomerRequest;
use Inertia\Inertia;

class CustomerController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function store(StoreCustomerRequest $request)
    {
        //$validatedData = $request->validated();
        $validatedData = $request->all();
        $endpoint = $this->apiClient->baseurl . '/customer/create';
        $response = $this->apiClient->post($endpoint, $validatedData);

        if ($response->status() === 201) {
            if (request()->header('X-Inertia') || request()->expectsJson()) {
                info('axios details success');
                $customer = $response->json()['customer'] ?? [];
                return response()->json([
                    'customer' => $customer,
                    'redirect' => route('customer.checkout')
                ]);
            }
            info('inertia checkout');
            return Inertia::location(route('customer.checkout'));
        }
        //Error
        if (request()->header('X-Inertia') || request()->expectsJson()) {
            info('axios details error');
            return response()->json([
                'error' => $response->json()
            ]);
        }
        return redirect()->route('customer.details')->with('error', 'Details Failed');
    
    }

}