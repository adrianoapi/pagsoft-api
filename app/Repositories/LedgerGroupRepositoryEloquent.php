<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerGroupRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerGroup;

class LedgerGroupRepositoryEloquent extends UtilEloquent implements LedgerGroupRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(LedgerGroup $model)
	{
        $this->model = $model;
	}

    public function getAll()
    {
        $model = $this->model::all();
        if(!empty($model))
        {
            return response()->json($model);
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }
}
