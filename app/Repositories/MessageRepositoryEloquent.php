<?php

namespace App\Repositories;

use App\Repositories\Contracts\MessageRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Message;

class MessageRepositoryEloquent extends UtilEloquent implements MessageRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Message $model)
	{
        $this->model = $model;
	}
}
