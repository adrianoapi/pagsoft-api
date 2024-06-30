<?php

namespace App\Repositories;

use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\File;
use App\CollectionSharing;

class FileRepositoryEloquent extends UtilEloquent implements FileRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(File $model)
	{
        $this->model = $model;
	}

    public function getFileById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if(
            $model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists()
            ||
            CollectionSharing::where('collection_id', $id)->where('user_id', auth('api')->user()->id)->exists()
        ){
            $model = $this->model::findOrFail($id);
            $model->author = $model->user_id == auth('api')->user()->id;
            $data  = [
                'collection' => $model->attributesToArray(),
                'items'      => $this->factoreStructure($model->items, ['images']),
            ];
            
            return response()->json($data);
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->title            = $data['title'           ];
                $model->description      = $data['description'     ];
                $model->show_id          = $data['show_id'         ];
                $model->show_image       = $data['show_image'      ];
                $model->show_title       = $data['show_title'      ];
                $model->show_description = $data['show_description'];
                $model->show_release     = $data['show_release'    ];
                $model->order            = $data['order'           ];
                $model->layout           = $data['layout'          ];
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
        try{
            $model = $this->model;
            $model->name    = $data['name'];
            $model->type    = $data['type'];
            $model->size    = $data['size'];
            $model->user_id = auth('api')->user()->id;
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function findById(int $id, array $relations = [])
    {
        $model = $this->model;
        if(
            $model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists()
            ||
            CollectionSharing::where('collection_id', $id)->where('user_id', auth('api')->user()->id)->exists()
            )
        {
            try{
                $model = $this->model::findOrFail($id);
        
                if(!empty($model) && !empty($relations))
                {
                    #Injeta os objetos relacionados a matriz
                    foreach($relations as $relation):
                        $model->$relation;
                    endforeach;
                }
                
                $model->author = $model->user_id == auth('api')->user()->id;
                return response()->json($model, 200);
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
