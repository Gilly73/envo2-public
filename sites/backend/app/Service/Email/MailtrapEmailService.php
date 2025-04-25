<?php

namespace App\Service\Email;


use App\Interfaces\EmailServiceInterface;
use Illuminate\Support\Facades\Http;
use Exception;
use Sentry;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class MailtrapEmailService implements EmailServiceInterface
{
    protected string $url;
    protected string $mailtrapApiKey;
    protected string $mailtrapInboxId;
    protected string $mailtrapHost;

    public function __construct() {}

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
            $params = [
                'name' => $data['name'],
                'orderId' => $data['orderId'],
                'items'       => $data['details'] ?? [],
                'client' => 'Mailtrap',
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

            $response = Mail::mailer('mailtrap')->to($to)->send($mailable);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
