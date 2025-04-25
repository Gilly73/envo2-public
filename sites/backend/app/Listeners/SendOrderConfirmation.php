<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Interfaces\EmailServiceInterface;
use App\Events\OrderCreated;
use App\Service\CustomerService;
use App\Service\QuoteService;

class SendOrderConfirmation implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 10;

    /**
     * The email service instance.
     *
     * @var EmailServiceInterface
     */

    protected EmailServiceInterface $emailService;
    protected CustomerService $customerService;
    protected QuoteService $quoteService;

    public function __construct(EmailServiceInterface $emailService, CustomerService $customerService, QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
        $this->emailService = $emailService;
        $this->customerService = $customerService;
    }

    /**
     * Send order email to the customer.
     */
    public function handle(OrderCreated $event): void
    {
        $customerId = $event->order->customer_id;
        $orderId = $event->order->id;
        $quoteId = $event->order->quote_id;
        $customer = $this->customerService->getCustomer($customerId);
        if (!$customer) {
            // Handle the case where the customer is not found
            return;
        }
        $quote = $this->quoteService->getQuote($quoteId);
        if (!$quote) {
            // Handle the case where the quote is not found
            return;
        }

        $data = [
            'orderId' => $orderId,
            'name' => $customer->first_name . ' ' . $customer->last_name,
            'details'   => [
                'title' => $quote->description,
                'discount_code' => $quote->discount_code,
                'discount' => $quote->discount,
                'country' => $quote->country,
                'tax' => $quote->tax,
                'total_cost'   => $quote->total_cost
            ]
        ];

        $this->emailService->send($customer->email, '', '', $data);
    }
}
