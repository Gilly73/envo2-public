<?php

namespace App\Http\Controllers;

use App\Services\ApiClient;
use Inertia\Inertia;

class CouchController extends Controller
{

    protected $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function getQptions()
    {
        info('getQptions');
        $endpoint = 'https://product/options';
        $response = $this->apiClient->get($endpoint);
        info($response->status());
        if ($response->status() === 200) {
            if (request()->header('X-Inertia') || request()->expectsJson()) {
                return response()->json([
                    'options' => $response->json(),
                    'redirect' => route('home')
                ]);
            }
            return Inertia::location(route('home'));
        }

        //Error
        if (request()->header('X-Inertia') || request()->expectsJson()) {
            info('getQptions error');
            return response()->json([
                'error' => $response->json()
            ]);
        }

    }
}
