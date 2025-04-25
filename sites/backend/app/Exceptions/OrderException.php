<?php

namespace App\Exceptions;

use Exception;
use Throwable;
//use Illuminate\Contracts\Support\Responsable;
//use Symfony\Component\HttpFoundation\Response;

class OrderException extends Exception //implements Responsable
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    //send to sentry if exeception is thrown
    public function render($request)
    {
        $statusCode = $this->getCode() ?: 400;
        if ($request->wantsJson()) {
            return response()->json(['error' => $this->getMessage()], $statusCode);
        }

        return response()->view('errors.{$statusCode}', [], $statusCode);
    }

    //you don’t need a catch—Laravel will call toResponse() and send a 422 for you.
    // public function toResponse($request): Response
    // {
    //     return response()->json(
    //         ['errors' => $this->getMessage()],
    //         $this->getCode() ?: 400
    //     );
    // }
    
}