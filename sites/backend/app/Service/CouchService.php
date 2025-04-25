<?php

namespace App\Service;

use App\Factory\CouchBuilder;
use App\Factory\Style;
use App\Factory\Fabric;
use App\Factory\Leg;
use App\Factory\Seater;
use App\DTO\CouchDTO;
use App\Service\CostAdjustmentService;

class CouchService
{
    private CouchBuilder $couch;

    public function buildCouch(CouchDTO $options): CouchBuilder
    {
        $this->couch = new CouchBuilder($options->couchtype);

        if (isset($options->styletype)) {
            $style = new Style($options->styletype,$options->couchtype);
            $this->couch->addComponent($style);
        }

        if (isset($options->fabrictype)) {
            $fabric = new Fabric($options->fabrictype,$options->couchtype);
            $this->couch->addComponent($fabric);
        }

        if (isset($options->legtype)) {
            $leg = new Leg($options->legtype,$options->couchtype);
            $this->couch->addComponent($leg);
        }

        if (isset($options->seatertype)) {
            $seater = new Seater($options->seatertype,$options->couchtype);
            $this->couch->addComponent($seater);
        }

        return $this->couch;
    }

    public function getComponents(): array
    {
        return array_map(function ($component) {
            return [
                'class' => class_basename($component),
                'description' => $component->getDescription(),
                'cost' => $component->getCost()
            ];
        }, $this->couch->getComponents());
    }

    public function getDescription(couchDTO $options): string
    {
        $couch = $this->buildCouch($options);
        return $couch->getDescription();
    }

    public function getCost(CouchDTO $options): array
    {
        $couch = $this->buildCouch($options);
        
        // Get cost from CouchBuilder
        $baseCost = $couch->getCost();

        //CostAdjustmentService factory method gets strategies dynamically
        $pricingService = CostAdjustmentService::createWithStrategies(
            $options->discount ?? null,
            $options->country ?? null
        );

        return $pricingService->calculateCost($baseCost);
    }
}
