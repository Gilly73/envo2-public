<?php

namespace App\Repository;

use App\Repository\Interfaces\OrderRepositoryInterface;
use App\Models\Order;
class EloquentOrderRepository extends EloquentBaseRepository implements OrderRepositoryInterface
{
    public function __construct(Order $order)
    {
        parent::__construct($order);
    }


}
