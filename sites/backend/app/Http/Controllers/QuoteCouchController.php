<?php

namespace App\Http\Controllers;

use App\Service\CouchService;
use Illuminate\Validation\ValidationException;
use App\DTO\CouchDTO;
use App\Http\Requests\QuoteRequest;
use App\Exceptions\CouchException;
use App\Service\QuoteService;

class QuoteCouchController extends Controller
{
    protected CouchService $couchService;
    protected QuoteService $quoteService;

    public function __construct(CouchService $couchService, QuoteService $quoteService)
    {
        $this->quoteService = $quoteService;
        $this->couchService = $couchService;
    }

    public function getQuote(QuoteRequest $request)
    {
        try {
            $couchDTO = CouchDTO::fromArray($request->validated());
            $finalCost = $this->couchService->getCost($couchDTO);
            $description = $this->couchService->getDescription($couchDTO);
            $savedQuote =  $this->quoteService->save($couchDTO,$finalCost,$description);

            return response()->json([
                'description' => $description,
                'couchCost' => $finalCost['cost'],
                'discount'  => $finalCost['discount'],
                'tax'       => $finalCost['tax'],
                'totalCost' => $finalCost['totalCost'],
                'quoteId'   => $savedQuote->id
            ]);

        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (CouchException $e) {
            return response()->json(['errors' => $e->getMessage()], $e->getCode());
        } catch (\Throwable $e) {
            throw new \Exception($e); //500
        }
    }
}
