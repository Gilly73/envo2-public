<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CostAdjustment extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cost.adjustment';
    }
}
