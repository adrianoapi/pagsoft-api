<?php

namespace App\Repositories;

use App\Repositories\Contracts\PasswordRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Password;

class PasswordRepositoryEloquent extends UtilEloquent implements PasswordRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(Password $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
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
                $model->title = $data['title'];
                $model->login = $data['login'];
                $model->pass  = $data['pass' ];
                $model->url   = $data['url'  ];
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
            $model->title   = $data['title'];
            $model->login   = $data['login'];
            $model->pass    = $data['pass' ];
            $model->url     = $data['url'  ];
            $model->user_id = auth('api')->user()->id;
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
