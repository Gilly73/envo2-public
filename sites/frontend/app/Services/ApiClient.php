<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    protected $token;
    protected $defaultHeaders;
    public $baseurl;

    public function __construct()
    {
        // Configure these via your .env and config/services.php files
        $this->token = config('services.backend.token', 'default-token');
        $this->defaultHeaders = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json',
        ];
        $this->baseurl = config('services.backend.base_url');
    }

    /**
     * Make a POST request to the specified endpoint.
     *
     * @param string $endpoint
     * @param array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $endpoint, array $data)
    {
        $response = Http::withHeaders($this->defaultHeaders)->post($endpoint, $data);

        // Optionally log the response for debugging
        $data = [
            'endpoint'      => $endpoint,
            'request_data'  => $data,
            'response_body' => $response->body(), //$response->getBody()
            'status'        => $response->status(),
        ];
        info('ApiClient POST Request');

        info(print_r($data, true));

        return $response;
    }

    public function get(string $endpoint)
    {
        $response = Http::withHeaders($this->defaultHeaders)->get($endpoint);

        // Optionally log the response for debugging
        $data = [
            'endpoint'      => $endpoint,
            'request_data'  => [],
            'response_body' => $response->body(), //$response->getBody()
            'status'        => $response->status(),
        ];
        info('ApiClient GET Request');

        return $response;
    }
}
