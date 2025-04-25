<?php

namespace App\Factory;

use App\Models\Couch\Style as Model;
use App\Exceptions\CouchException;

class Style extends BaseCouchComponent
{
    public function __construct(int $id, int $couchTypeId) 
    {
        $model = $this->findComponent($id,$couchTypeId);

        if ($model) {
            $this->name = $model->name;
            $this->cost = $model->base_price;
        } else {
            throw new CouchException(class_basename(static::class)." not found : {$id}", 400);
        }
    }

    protected function findComponent(int $id, ?int $couchTypeId = null)
    {
        return Model::where(['id' => $id, 'couch_type_id' => $couchTypeId])->first();
    }
    //return parent::getCost() * 1.2;
}