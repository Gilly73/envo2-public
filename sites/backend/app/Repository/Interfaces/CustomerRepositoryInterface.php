<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Collection;
interface CustomerRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email):Collection ;
}