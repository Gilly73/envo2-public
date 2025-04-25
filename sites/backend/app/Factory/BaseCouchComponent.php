<?php

namespace App\Factory;

use App\Interfaces\CouchComponent;

abstract class BaseCouchComponent implements CouchComponent
{
    protected string $name = 'Unknown';
    protected float $cost = 0.00;

    abstract protected function findComponent(int $id, ?int $couchTypeId = null);

    public function getCost(): float
    {
        return $this->cost;
    }

    public function getDescription(): string
    {
        // This could be further refined if different descriptions are needed
        return class_basename(static::class) . ' = ' . ucfirst($this->name);
    }
}
