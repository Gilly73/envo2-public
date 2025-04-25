<?php

namespace App\Interfaces;

interface CouchComponent
{
    public function getCost(): float;
    public function getDescription(): string;
}