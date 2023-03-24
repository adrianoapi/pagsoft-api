<?php

namespace App\Repositories;

use App\Repositories\Contracts\CollectionSharingRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\CollectionSharing;
use App\Collection;

class CollectionSharingRepositoryEloquent extends UtilEloquent implements CollectionSharingRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(CollectionSharing $model)
	{
        $this->model = $model;
	}

    public function store(int $id, array $data)
    {
        # Checa se a coleção pertence ao usuário
        if(Collection::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model;
                $model->collection_id = $id;
                $model->user_id       = $data['user_id'];
                $model->save();
    
                return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
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
        $model = $this->model::findOrFail($id);
        if($model->collection->user_id == auth('api')->user()->id)
        {
            try{
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
