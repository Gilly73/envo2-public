<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Closure;
use Illuminate\Support\Facades\Log;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //'customer/create'
    ];

    /**
     * Log CSRF requests for debugging.
     */
    public function handle($request, Closure $next)
    {
        // Log::info('✅ VerifyCsrfToken middleware is running for a request.');
        // Log::info('Incoming Headers:', $request->headers->all());
        // Log::info('Incoming Cookies:', $request->cookies->all());

        // Log::info('✅ VerifyCsrfToken middleware is running');

        // // Log the CSRF token from the request headers
        // Log::info('Incoming XSRF-TOKEN Header:', [$request->header('X-XSRF-TOKEN')]);

        // // Log the CSRF token from cookies
        // Log::info('Incoming XSRF-TOKEN Cookie:', [$request->cookie('XSRF-TOKEN')]);

        // // Log the CSRF token stored in Laravel's session
        // Log::info('Stored CSRF Token in Session:', [session('_token')]);

        // Log::info('Laravel Session ID:', [$request->session()->getId()]);
        // Log::info('Incoming Session Cookie:', [$request->cookie('laravel_session')]);

        // Log::info('Incoming Origin Header:', [$request->header('origin')]);
        // Log::info('Incoming Referer Header:', [$request->header('referer')]);
        


        return parent::handle($request, $next);
    }
}
