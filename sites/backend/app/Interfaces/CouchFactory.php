<?php

namespace App\Interfaces;

interface CouchFactory
{
    public function createCouch(string $couchtype): CouchComponent;
}