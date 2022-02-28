<?php

namespace App\Repositories;

use App\Repositories\Contracts\TaskGroupRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\TaskGroup;

class TaskGroupRepositoryEloquent extends UtilEloquent implements TaskGroupRepositoryInterface
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
