<?php

namespace App\Repositories;

use App\Repositories\Contracts\FixedCostRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\FixedCost;

class FixedCostRepositoryEloquent extends UtilEloquent implements FixedCostRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(FixedCost $model)
	{
        $this->model = $model;
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
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->ledger_group_id    = $data['ledger_group_id'   ];
                $model->transition_type_id = $data['transition_type_id'];
                $model->description        = $data['description'       ];
                $model->entry_date         = $data['entry_date'        ];
                $model->amount             = $data['amount'            ];
                $model->installments       = $data['installments'      ];
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
            $model->ledger_group_id    = $data['ledger_group_id'   ];
            $model->transition_type_id = $data['transition_type_id'];
            $model->description        = $data['description'       ];
            $model->entry_date         = $data['entry_date'        ];
            $model->amount             = $data['amount'            ];
            $model->installments       = $data['installments'      ];
            $model->user_id            = auth('api')->user()->id;
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
