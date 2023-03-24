<?php

namespace App\Repositories;

use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Collection;
use App\CollectionSharing;

class CollectionRepositoryEloquent extends UtilEloquent implements CollectionRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Collection $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if(
            $model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists()
            ||
            CollectionSharing::where('collection_id', $id)->where('user_id', auth('api')->user()->id)->exists()
        ){
            $model = $this->model::findOrFail($id);
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
            $model->title            = $data['title'           ];
            $model->description      = $data['description'     ];
            $model->show_id          = $data['show_id'         ];
            $model->show_image       = $data['show_image'      ];
            $model->show_title       = $data['show_title'      ];
            $model->show_description = $data['show_description'];
            $model->show_release     = $data['show_release'    ];
            $model->order            = $data['order'           ];
            $model->layout           = $data['layout'          ];
            $model->user_id          = auth('api')->user()->id;
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $model = $this->model;
        
        $model = $model::select('collections.*')
        ->leftJoin('collection_sharings', function($join) {
            $join->on('collections.id', '=','collection_sharings.collection_id');
          })
          ->where('collection_sharings.user_id', auth('api')->user()->id)
          ->orWhere('collections.user_id', auth('api')->user()->id);

        if(!empty($criteria)){
            foreach ($criteria as $condition => $value) {
                $model = $model->where("collections.".$condition,'like','%'.$value.'%');
            }
        }

        if(!empty($orderBy)){
            foreach ($orderBy as $order => $value) {
                $model = $model->orderBy("collections.".$order, $value);
            }
        }

        if(!empty($limit))
        {
            if($limit > 0 && $limit <= 20){
                $this->perPage =  (int)$limit;
            }
        }

        if (!empty($offset)) {
            $model = $model->skip((int)$offset);
        }

        $model = $model->paginate($this->perPage);

        $model->appends(request()->input())->links();

        return response()->json($model, 200);
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
