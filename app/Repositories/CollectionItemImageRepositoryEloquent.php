<?php

namespace App\Repositories;

use App\Repositories\Contracts\CollectionItemImageRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\CollectionItemImage;

class CollectionItemImageRepositoryEloquent extends UtilEloquent implements CollectionItemImageRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(CollectionItemImage $model)
	{
        $this->model = $model;
	}

    public function store(array $data)
    {
        if($this->checkAuthority($data['collection_id']))
        {
            try{
                $model = $this->model;
                $model->collection_item_id = $data['collection_item_id'];
                $model->image              = $data['image'             ];
                $model->size               = $data['size'              ];
                $model->type               = $data['type'              ];
                $model->save();

                return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }
        }
        else{
            return response()->json(["message" => "Record 'collection_id' Not Found!"], 404);
        }

    }

    public function delete(int $id)
    {
        #Primeiro pega o item image
        $model = $this->model::findOrFail($id);
        if(!empty($model))
        {
            if($this->checkAuthority($model->collectionItem->collection->id))
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
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
        
    }

    public function checkAuthority(int $collection_id)
    {
        return \App\Collection::where('id', $collection_id)->where('user_id', auth('api')->user()->id)->exists();
    }
}
