<?php

namespace App\Observers;

use App\Models\Order;
use App\Service\OrderService;

class OrderObserver
{

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        //Dispatch an event if to handle additional logic asynchronously
        //event(new OrderCreated($order));
        $this->orderService->updatePaymentWithOrderId($order->payment_id, $order);

    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
