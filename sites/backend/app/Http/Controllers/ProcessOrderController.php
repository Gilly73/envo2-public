<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Service\OrderService;
use App\DTO\PaymentIntentDTO;
use App\DTO\OrderDTO;
use App\Exceptions\OrderException;
use App\Events\OrderCreated;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Http\Resources\OrderResource;

class ProcessOrderController extends Controller
{

    protected $orderService;
    protected $orderDto;
    protected $currencies;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        $this->orderDto;
    }

    public function process(OrderRequest $request)
    {
        try {
            //abort(500, 'Order 500');
            $order = $this->processOrder($request);

            $orderData = new OrderResource([
                'order' => $order,
                'quote' => $request->input('quote', []),
            ]);
    
            return response()->json($orderData)->setStatusCode(200, 'Order processed');
        } catch (OrderException $e) {
            info('OrderException');
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        } catch (\Throwable $e) {
            info('Throwable');
            //return response()->json(['errors' => $e->getMessage()], 500);
            throw new \Exception($e); //500
        }
    }

    protected function processOrder($request)
    {
        $payment = $request->input('payment.paymentIntent');
        $customerId = $request->input('customer.id');
        $quoteId = $request->input('quote.quoteId');

        $savedPayment = $this->savePayment($customerId, $payment);
        if ($payment['status'] !== PaymentStatus::SUCCEEDED->value) {
            throw new OrderException('Payment failed', 422);
        }

        $savedOrder = $this->saveOrder($quoteId, $customerId, $savedPayment);

        $orderDto = $this->getOrderDto();
        event(new OrderCreated($savedOrder));
        return $orderDto;
    }

    protected function savePayment($customerId, $payment)
    {
        $paymentDto = $this->createPaymentDto($customerId, $payment);
        return $this->orderService->savePayment($paymentDto->toArray());
    }

    protected function saveOrder($quoteId, $customerId, $savedPayment)
    {
        $this->orderDto = $this->createOrderDto($quoteId, $customerId, $savedPayment);
        $savedOrder = $this->orderService->saveOrder($this->orderDto->toArray());
        if (!$savedOrder) {
            throw new OrderException('Order not saved', 422);
        }
        $this->orderDto = $this->orderDto->withId($savedOrder->id);
        return $savedOrder;
    }

    protected function createPaymentDto($customerId, $payment)
    {
        $data = [
            'customer_id' => $customerId,
            'payment_intent_id' => $payment['id'] ?? '',
            'amount' => $payment['amount'],
            'currency' => $payment['currency'],
            'payment_method' => $payment['payment_method'],
            'status' => ($payment['status'] == 'succeeded') ? PaymentStatus::SUCCEEDED->value : PaymentStatus::FAILED->value,
        ];
        return PaymentIntentDTO::fromArray($data);
    }

    protected function createOrderDto($quoteId, $customerId, $savedPayment)
    {
        $data = [
            'quote_id' => $quoteId,
            'customer_id' => $customerId,
            'payment_id' => $savedPayment->id,
            'amount' => amount_divided_by_100($savedPayment->amount),
            'currency' => $savedPayment->currency,
            'status' => OrderStatus::PENDING->value
        ];
        return OrderDTO::fromArray($data);
    }

    public function getOrderDto()
    {
        return $this->orderDto;
    }
}
