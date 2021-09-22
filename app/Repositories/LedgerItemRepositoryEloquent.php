<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerItemRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerItem;

class LedgerItemRepositoryEloquent extends UtilEloquent implements LedgerItemRepositoryInterface
{
    protected $model;
    protected $perPage = 10;

	public function __construct(LedgerItem $model)
	{
        $this->model = $model;
	}

    public function delete(int $id)
    {
        //
    }

    public function update(array $data, int $id)
    {
        //
    }

    public function store(array $data)
    {
        //
    }

}
