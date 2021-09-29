<?php

namespace App\Repositories;

use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Collection;

class CollectionRepositoryEloquent extends UtilEloquent implements CollectionRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Collection $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {

    }

    public function update(array $data, int $id)
    {

    }

    public function store(array $data)
    {

    }
}
