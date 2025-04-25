<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use App\Http\Requests\QuoteRequest;
use Inertia\Inertia;

class QuoteController extends Controller
{

    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getQuote(QuoteRequest $request)
    {
        info('getQuote');
        //$validatedData = $request->validated();
        $validatedData = $request->all();
        $endpoint = $this->apiClient->baseurl . '/quote/couch';
        $response = $this->apiClient->post($endpoint, $validatedData);
        info($response->status());
        if ($response->status() === 200) {
            if (request()->header('X-Inertia') || request()->expectsJson()) {
                //request is an API call (from Axios)
                return response()->json([
                    'quote' => $response->json(),
                    'redirect' => route('customer.details')
                ]);
            }
            return Inertia::location(route('customer.details'));
        }

        //Error
        if (request()->header('X-Inertia') || request()->expectsJson()) {
            info('getQuote error');
            return response()->json([
                'error' => $response->json()
            ]);
        }

    }
}
