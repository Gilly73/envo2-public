<?php

namespace App\Service;

use App\Repository\Interfaces\QuoteRepositoryInterface;
use App\DTO\CouchDTO;

class QuoteService
{
    protected $quoteRepository;

    public function __construct(QuoteRepositoryInterface $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function save(CouchDTO $data, array $finalCost, string $description, ?int $customerId = null)
    {
        $save = [
            'active' => true,
            'couchtype' => $data->couchtype ?? null,
            'styletype' => $data->styletype ?? null,
            'fabrictype' => $data->fabrictype ?? null,
            'legtype'   => $data->legtype ?? null,
            'seatertype' => $data->seatertype ?? null,
            'discount_code'  => $data->discount ?? null,
            'country'   => $data->country ?? null,
            'description' => $description ?? null,
            'couch_cost'   => isset($finalCost['cost']) ? (float) $finalCost['cost'] : null,
            'discount'    => isset($finalCost['discount']) ? (float) $finalCost['discount'] : null,
            'tax'         => isset($finalCost['tax']) ? (float) $finalCost['tax'] : null,
            'total_cost'   => isset($finalCost['totalCost']) ? (float) $finalCost['totalCost'] : null,
            'customer_id' => $customerId,
        ];

        return $this->quoteRepository->create($save);
    }

    public function updateQuoteWithCustomer($quoteId, $customerId)
    {
        return $this->quoteRepository->updateQuoteWithCustomer($quoteId, $customerId);
    }

    public function getQuote(int $id)
    {
        return $this->quoteRepository->find($id);
    }
}
