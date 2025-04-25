<?php

namespace App\Http\Controllers;

use App\Service\CustomerService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\StoreCustomerRequest;
use App\DTO\CustomerDTO;
use App\Exceptions\CustomerException;
use App\Service\QuoteService;

class CustomerController extends Controller
{
    protected CustomerService $customerService;
    protected QuoteService $quoteService;


    public function __construct(CustomerService $customerService, QuoteService $quoteService)
    {
        $this->customerService = $customerService;
        $this->quoteService = $quoteService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customerService->getAllCustomers();
        return response()->json([
            'data' => $customers
        ], 200);
    }

    /**
     * Store customer data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customerDto = CustomerDTO::fromArray($request->validated())->toArray();
        $quoteId = $request->input('quoteId');
        try {
            $customer = $this->customerService->updateOrCreateCustomer($customerDto['email'],$customerDto);
            if ($quoteId) {
                $this->quoteService->updateQuoteWithCustomer($quoteId, $customer->id);
            }
            return response()->json(['message' => 'customer', 'customer' => $customer], 201);
        } catch (CustomerException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }
}
