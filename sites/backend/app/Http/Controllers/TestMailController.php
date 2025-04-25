<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    /**
     * Sends a test email via the Mailtrap API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendTestEmail()
    {
        try {
            $data = [
                'content' => 'This is a test email sent using Mailtrap via SMTP.',
            ];
    
            Mail::send('emails.test', $data, function ($message) {
                $message->to('example@mailtrap.io', 'Test User')
                        ->subject('Test Email from Laravel Controller');
            });
    
            return response()->json(['status' => 'Email sent successfully!']);
        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}
