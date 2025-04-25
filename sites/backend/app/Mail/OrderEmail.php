<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;


use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\EmailHeader\CustomVariableHeader;
use PSpell\Config;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\UnstructuredHeader;
//https://mailtrap.io/blog/send-email-in-laravel/#Send-email-in-Laravel-using-API

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;


    public array $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        //$fromAddress = config('mail.from.address');
        //$fromName = config('mail.from.name');

        return new Envelope(
            from: new Address($this->data['fromAddress'], $this->data['fromName']),
            subject: 'Order Confirmation',
            using: [
                      function (Email $email) {
                          // Headers
                          $email->getHeaders()
                              ->addTextHeader('X-Message-Source', $this->data['domain'])
                              ->add(new UnstructuredHeader('X-Mailer', $this->data['client'].' PHP Client'))
                          ;

                          // Custom Variables
                          $email->getHeaders()
                              ->add(new CustomVariableHeader('user_id', '45982'))
                              ->add(new CustomVariableHeader('batch_id', 'PSJ-12'))
                          ;

                          // Category (should be only one)
                          $email->getHeaders()
                              ->add(new CategoryHeader('Order Confirmation Email'))
                          ;
                      },
                  ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order-email',
            text: 'emails.order-email-text',
            with: [
                'name' => $this->data['name'],
                'orderId' => $this->data['orderId'],
                'fromAddress' => $this->data['fromAddress']
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [
    //        Attachment::fromPath('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg')
    //             ->as('logo.svg')
    //             ->withMime('image/svg+xml'),
    //     ];
    // }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            'custom-message-id@example.com',
            ['previous-message@example.com'],
            [
                'X-Custom-Header' => 'Custom Value',
            ],
        );
    }
}
