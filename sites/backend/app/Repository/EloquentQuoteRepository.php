<?php

namespace App\Repository;

use App\Repository\Interfaces\QuoteRepositoryInterface;
use App\Models\Quote;

class EloquentQuoteRepository extends EloquentBaseRepository implements QuoteRepositoryInterface
{
    public function __construct(Quote $quote)
    {
        parent::__construct($quote);
    }

    public function updateQuoteWithCustomer($quoteId, $customerId): ?quote
    {
        $quote = $this->model->find($quoteId);

        if (!$quote) {
            return null; 
        }

        $quote->customer_id = $customerId;
        $quote->save();
        return $quote;
    }
}
