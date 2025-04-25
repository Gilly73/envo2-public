<?php

namespace App\Factory;

use App\Interfaces\CouchComponent;

class CouchBuilder implements CouchComponent
{
    private array $components = [];
    private Couch $couch; // The base couch

    public function __construct(int $couchTypeId)
    {
        $this->couch =  new Couch($couchTypeId);
    }

    public function addComponent(CouchComponent $component)
    {
        $this->components[] = $component;
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function getCost(): float
    {
        $cost = $this->couch->getCost(); // Start with the base couch cost
        foreach ($this->components as $component) {
            $cost += $component->getCost();
        }
        return $cost;
    }

    public function getDescription(): string
    {
        $description = $this->couch->getDescription();
        foreach ($this->components as $component) {
            $description .= ", " . $component->getDescription();
        }
        return $description;
    }
}