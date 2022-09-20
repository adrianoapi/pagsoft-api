<?php

namespace App\Repositories;

use App\Repositories\Contracts\ActionRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Action;

class ActionRepositoryEloquent extends UtilEloquent implements ActionRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Action $model)
	{
        $this->model = $model;
	}
}
