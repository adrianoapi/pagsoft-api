<?php

namespace App\Repositories;

use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Task;

class TaskRepositoryEloquent extends UtilEloquent implements TaskRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Task $model)
	{
        $this->model = $model;
	}

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if(!empty($limit)){
            $this->perPage = $limit;
        }

        try{
            $result = $this->model::whereHas('TaskGroup', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('title', 'like', '%' . $criteria['title'] . '%')
            ->where('status', $criteria['status'])
            #->where('level', $criteria['level'])
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

            return response()->json($result, 200);
        }
        catch(\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findById($id)
    {
        if(
            $this->model::whereHas('TaskGroup', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
        {
            try{
                $result = $this->model::findOrFail($id);
                return response()->json($result, 200);
            }
            catch(\Exception $e)
            {
                return response()->json(["message" => $e->getMessage()]);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
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

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $model->task_group_id = $data['task_group_id'];
            $model->title         = $data['title'        ];
            $model->content       = $data['content'      ];
            $model->status        = $data['status'       ];
            $model->level         = $data['level'        ];
            $model->order         = $data['order'        ];
            $model->archived      = $data['archived'     ];
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function delete(int $id)
    {
        if(
            $this->model::whereHas('TaskGroup', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->delete();

                return response()->json(["message" => "Record Deleted"], 202);
            }
            catch(\Exception $e)
            {
                return response()->json(["message" => $e->getMessage()]);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }
}
