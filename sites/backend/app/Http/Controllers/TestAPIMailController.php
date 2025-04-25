<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use App\Interfaces\EmailServiceInterface;

class TestAPIMailController extends Controller
{
    /**
     * Sends a test email via srvice API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestEmail(EmailServiceInterface $emailService)
    {
        try {
            $emailService->send('seeta.gill@gmail.com','','',[]);
        
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
