<?php

namespace App\Factory;

use App\Models\Couch\CouchType as Model;
use App\Exceptions\CouchException;

class Couch extends BaseCouchComponent
{

    public function __construct(int $id)
    {
        $model = $this->findComponent($id);

        if ($model) {
            $this->name = $model->name;
            $this->cost = 0.00;
        }else {
            throw new CouchException(class_basename(static::class) . " not found : {$id}", 400);
        }
    }

    protected function findComponent(int $id, ?int $couchTypeId = null)
    {
        return Model::find($id);
    }
}