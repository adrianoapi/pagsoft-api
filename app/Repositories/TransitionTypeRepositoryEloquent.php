<?php

namespace App\Repositories;

use App\Repositories\Contracts\TransitionTypeRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\TransitionType;

class TransitionTypeRepositoryEloquent extends UtilEloquent implements TransitionTypeRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(TransitionType $model)
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
