<?php

namespace App\Repositories;

use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Event;

class EventRepositoryEloquent extends UtilEloquent implements EventRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Event $model)
	{
        $this->model = $model;
	}

    public function index($condition, $orderBy, $limit)
    {
        return $this->findBy($condition, $orderBy, $limit);
    }

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $model->user_id    = auth('api')->user()->id;
            $model->name       = $data['name'      ];
            $model->location   = $data['location'  ];
            $model->start_date = $data['start_date'];
            $model->end_date   = $data['end_date'  ];
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }

    }
}
