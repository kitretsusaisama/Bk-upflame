<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(string $id)
    {
        return $this->model->find($id);
    }

    public function findAll(int $limit = 20)
    {
        return $this->model->paginate($limit);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data)
    {
        $record = $this->findById($id);
        if ($record) {
            $record->update($data);
            return $record;
        }
        return null;
    }

    public function delete(string $id): bool
    {
        $record = $this->findById($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }

    public function findWhere(array $criteria, int $limit = null)
    {
        $query = $this->model->where($criteria);
        return $limit ? $query->paginate($limit) : $query->get();
    }
}
