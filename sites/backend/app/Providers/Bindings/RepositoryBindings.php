<?php

namespace App\Providers\Bindings;

use Illuminate\Support\ServiceProvider;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Repository\EloquentCustomerRepository;
use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Repository\EloquentOrderRepository;
use App\Repository\Interfaces\QuoteRepositoryInterface;
use App\Repository\EloquentQuoteRepository;
use App\Repository\Interfaces\PaymentRepositoryInterface;
use App\Repository\EloquentPaymentRepository;

class RepositoryBindings extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CustomerRepositoryInterface::class, EloquentCustomerRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
        $this->app->bind(QuoteRepositoryInterface::class, EloquentQuoteRepository::class);
    }
}