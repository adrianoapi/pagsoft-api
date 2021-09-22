<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerEntry;

class LedgerEntryRepositoryEloquent extends UtilEloquent implements LedgerEntryRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(LedgerEntry $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if($model::where('id', $id)->exists())
        {
            $model = $this->model::findOrFail($id);
            $data  = [
                'collection'     => $model->attributesToArray(),
                'ledgerGroup'    => $model->ledgerGroup->attributesToArray(),
                'transitionType' => $model->transitionType->attributesToArray(),
                'ledgerItems'    => $this->factoreStructure($model->ledgerItems),
            ];

            return response()->json($data);
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function delete(int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->exists())
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
        if($model::where('id', $id)->exists())
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
