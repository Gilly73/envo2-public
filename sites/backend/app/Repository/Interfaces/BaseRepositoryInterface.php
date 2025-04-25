<?php

namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface BaseRepositoryInterface
{
    public function all(): Collection;
    public function find($id) : model;
    public function create(array $data): model;
    public function update($id, array $data): ?model;
    public function updateOrCreate(array $search, array $data): ?model;
    public function delete($id) : bool;
}
