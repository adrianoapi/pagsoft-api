<?php

namespace App\Repositories;

use App\Repositories\Contracts\CollectionRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Collection;

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
}
