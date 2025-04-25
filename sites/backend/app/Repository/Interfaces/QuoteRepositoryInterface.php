<?php

namespace App\Repository\Interfaces;
use Illuminate\Database\Eloquent\Model;

interface QuoteRepositoryInterface extends BaseRepositoryInterface
{
    public function updateQuoteWithCustomer($quoteId, $customerId): ?model;
}