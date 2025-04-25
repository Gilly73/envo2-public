<?php

namespace App\Http\Controllers;

use App\Interfaces\EmailServiceInterface;

class OldTestAPIMailController extends Controller
{
    /**
     * Sends a test email via the Mailtrap API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestEmail(EmailServiceInterface $emailService)
    {
        try {
            $to = 'seeta.gill@gmail.com';
            $subject = 'testing email service - mailtrap';
            $view = 'emails.order-email';
            $data = [
                'name' => 'Seeta Gill',
                'order_id' => 12345,
            ];
            $emailService->send($to, $subject, $view, $data);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
