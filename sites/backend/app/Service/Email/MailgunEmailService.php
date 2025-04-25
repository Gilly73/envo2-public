<?php

namespace App\Service\Email;

use App\Interfaces\EmailServiceInterface;
use Mailgun\Mailgun;
use Exception;
use Sentry;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MailgunEmailService implements EmailServiceInterface
{
    protected Mailgun $mailgun;
    protected string $domain;

    public function __construct()
    {

        // $this->domain = config('services.mailgun.domain');
        // $secret = config('services.mailgun.secret');

        // if (!$this->domain || !$secret) {
        //     throw new Exception('Mailgun domain or secret is not configured.');
        // }

        // // Initialize the Mailgun client using the API key
        // $this->mailgun = Mailgun::create($secret);
    }
    
    /**
     * send function
     *
     * @param string $to
     * @param string $subject
     * @param string $view
     * @param array $data
     * @return void
     */
    public function send(string $to, string $subject, string $view, array $data): void
    {
        try {

            // Render the blade view to a string.
            // $htmlContent = view($view, $data)->render();
            // $textContent = strip_tags($htmlContent); //plain text version
            // $fromAddress = config('services.mailgun.from') ?? config('mail.from.address');


            // $params = [
            //     'from'    => $fromAddress,
            //     'to'      => $to,
            //     'subject' => $subject,
            //     'text'    => $textContent,
            //     'html'    => $htmlContent,
            //     'h:X-Mailer' => 'Mailgun PHP Client', //mailgun specific header
            // ];
      
            //$response = $this->mailgun->messages()->send($this->domain, $params);
            //Sentry\captureMessage ('Mailgun send response: ' . json_encode($response));

            $params = [
                'name' => $data['name'],
                'orderId' => $data['orderId'],
                'items'       => $data['details'] ?? [],
                'client' => 'Mailgun',
                'domain' => 'frontend.com',
                'fromAddress' => 'no-reply@frontend.com',
                'fromName' => 'Customer Services',
            ];

            // Generate the PDF
            $pdf = PDF::loadView('pdfs.order-invoice', $params);

            // Inject the PDF binary into the Mailable
            $mailable = (new OrderEmail($params))
                ->attachData(
                    $pdf->output(),
                    "invoice-{$params['orderId']}.pdf",
                    ['mime' => 'application/pdf']
                );

            $response = Mail::mailer('mailgun')->to($to)->send($mailable);
        } catch (Exception $e) {
            info('Mailgun send error: ' . $e->getMessage());
            throw $e;
        }
    }
}
