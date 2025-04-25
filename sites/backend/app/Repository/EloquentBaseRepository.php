<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repository\Interfaces\BaseRepositoryInterface;

class EloquentBaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find($id): model
    {
        return $this->model->find($id);
    }

    public function create(array $data): model
    {
        return $this->model->create($data);
    }

    public function update($id, array $data): ?model
    {
        $record = $this->find($id);
        if (!$record) {
            return null;
        }

        $record->update($data);
        return $record;
    }

    public function delete($id) : bool
    {
        return (bool) $this->model->destroy($id);
    }

    public function updateOrCreate($search,$update) : ?model
    {
        $record = $this->model->where($search)->first();
        if ($record) {
            $record->update($update);
            return $record;
        } else {
            return $this->create($update);
        }
        //return $this->model->updateOrCreate($search, $update);
        
    }
}
