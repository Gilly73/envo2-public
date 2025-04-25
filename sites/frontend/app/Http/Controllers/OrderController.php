<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function process(Request $request)
    {
        info('process');
        $validatedData = $request->all();
        $endpoint = $this->apiClient->baseurl . '/order/process';
        $response = $this->apiClient->post($endpoint, $validatedData);

        info($response->status());
        if ($response->status() === 200) {
            if (request()->header('X-Inertia') || request()->expectsJson()) {
                $order = $response->json();
                return response()->json([
                    'order' => $order,
                    'redirect' => route('customer.confirmation')
                ]);
            }
            return Inertia::location(route('customer.confirmation'));
        }

        if (request()->header('X-Inertia') || request()->expectsJson()) {
            info('axios order error');
            return response()->json([
                'error' => $response->json()
            ]);
        }
        return redirect()->route('customer.confirmation')->with('error', ' Order Failed');
    }
}
