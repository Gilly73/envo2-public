<?php

namespace App\Repository;

use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Collection;

class EloquentCustomerRepository extends EloquentBaseRepository implements CustomerRepositoryInterface
{
    public function __construct(Customer $customer)
    {
        parent::__construct($customer);
    }
    
    public function findByEmail(string $email): Collection
    {
        return $this->model->where('email', $email)->get();
    }
 
}
