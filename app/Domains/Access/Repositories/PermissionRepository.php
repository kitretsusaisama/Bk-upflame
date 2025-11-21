<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Models\Permission;
use App\Support\BaseRepository;

class PermissionRepository extends BaseRepository
{
    public function __construct(Permission $model)
    {
        parent::__construct($model);
    }

    public function findByName($name)
    {
        return $this->model->where('name', $name)->first();
    }

    public function findByResource($resource, $limit = 20)
    {
        return $this->model->where('resource', $resource)->paginate($limit);
    }

    public function findByAction($action, $limit = 20)
    {
        return $this->model->where('action', $action)->paginate($limit);
    }
}
