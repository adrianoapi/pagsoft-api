<?php

namespace App\Repositories;

use App\Repositories\Contracts\CronJobRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\CronJob;
use App\Client;

class CronJobRepositoryEloquent extends UtilEloquent implements CronJobRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(CronJob $model)
	{
        $this->model = $model;
	} 

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if(!empty($limit)){
            $this->perPage = $limit;
        }

        try{
            $result = $this->model::whereHas('Client', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

            return response()->json($result, 200);
        }
        catch(\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function findById(int $id, array $relations = [])
    {
        if(
            $this->model::whereHas('Client', function($q){
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

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if(
            $this->model::whereHas('Client', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
        {
            try{
                $model = $this->model::findOrFail($id);
                foreach($data as $key => $value):
                    $model->$key = $value;
                endforeach;
                $model->save();

                return response()->json(['message' => 'Update Successful!', 'data' => $model], 200);
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

    public function store(array $data)
    {
        if(Client::where('id', $data['client_id'])->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model;
                foreach($data as $key => $value):
                    $model->$key = $value;
                endforeach;
                $model->save();
    
                return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }

        }else{
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function updateStatus(int $id, bool $flag)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->status = $flag;
                $model->save();

                return response()->json(['message' => 'Update Successful!', 'data' => $model], 200);
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
        if(
            $this->model::whereHas('Client', function($q){
                $q->where('client_id', auth('api')->user()->id);
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
