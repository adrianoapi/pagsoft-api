<?php

namespace App\Repositories;

use App\Repositories\Contracts\DiagramRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Diagram;

class DiagramRepositoryEloquent extends UtilEloquent implements DiagramRepositoryInterface
{
	protected $model;
    protected $perPage = 100;

	public function __construct(Diagram $model)
	{
        $this->model = $model;
	}

    public function index($condition, $orderBy, $limit)
    {
        return $this->findBy($condition, $orderBy, $limit);
    }

    public function getDiagramById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            $model = $this->model::findOrFail($id);
            $data  = [
                'diagram' => $model->attributesToArray(),
                'items'   => $this->factoreStructure($model->items),
            ];

            return response()->json($data);
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $model->user_id  = auth('api')->user()->id;
            $model->title    = $data['title'   ];
            $model->start    = $data['start'   ];
            $model->end      = $data['end'     ];
            $model->editable = $data['editable'];
            $model->all_day  = $data['all_day' ];
            $model->location = $data['location'];

            $model->backgroundColor  = $data['backgroundColor' ];
            
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
                $model->editable = $data['editable'];
                $model->all_day  = $data['all_day' ];
                $model->location = $data['location'];

                $model->backgroundColor  = $data['backgroundColor' ];

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

    public function delete(int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->delete();

                return response()->json(["message" => "Record Deleted"], 202);
            }
            catch(\Exception $e)
            {
                return response()->json(["message" => $e->getMessage()], 500);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }
}
