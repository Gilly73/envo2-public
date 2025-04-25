<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class CouchException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['error' => $this->getMessage()], 404);
        }

        return response()->view('errors.404', [], 404);
    }
    
}