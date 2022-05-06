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
            $model->user_id  = auth('api')->user()->id;
            $model->title    = $data['title'   ];
            $model->start    = $data['start'   ];
            $model->end      = $data['end'     ];
            $model->all_day  = $data['all_day' ];
            $model->location = $data['location'];
            $model->save();

            return response()->json(["id" => $model->id, "message" => "Created Successful!", "body" => $model->getAttributes()], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->title    = $data['title'   ];
                $model->start    = $data['start'   ];
                $model->end      = $data['end'     ];
                $model->all_day  = $data['all_day' ];
                $model->location = $data['location'];
                $model->save();

                return response()->json(['message' => 'Update Successful!', "body" => $model->getAttributes()], 200);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }
}
