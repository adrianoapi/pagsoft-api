<?php

namespace App\Repositories;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\TaskGroup;

class TaskRepositoryEloquent extends UtilEloquent implements TaskRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(TaskGroup $model)
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
