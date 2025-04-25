<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CustomerException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // You can add custom logic here, like logging or custom error responses
    public function render($request)
    {
        // Example: Return a JSON response
        if ($request->wantsJson()) {
            return response()->json(['error' => $this->getMessage()], 404);
        }

        // Default: Let Laravel handle the exception rendering (usually a 404 page)
        // Default: Return a 404 response
        return response()->view('errors.404', [], 404);
    }
    
}