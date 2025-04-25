<?php

namespace App\Interfaces;

interface EmailServiceInterface
{
    /**
     * Send an email using the provider.
     *
     * @param  string $to      Recipient email
     * @param  string $subject Email subject
     * @param  string $view    View template for the email content
     * @param  array  $data    Data for the view
     * @return void
     */
    public function send(string $to, string $subject, string $view, array $data): void;
}
