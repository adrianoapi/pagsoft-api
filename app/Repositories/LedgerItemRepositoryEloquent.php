<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerItemRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerItem;

class LedgerItemRepositoryEloquent extends UtilEloquent implements LedgerItemRepositoryInterface
{
    protected $model;
    protected $perPage = 10;

	public function __construct(LedgerItem $model)
	{
        $this->model = $model;
	}
    public function store(array $data)
    {
        if($this->checkAuthority($data['ledger_entry_id']))
        {
            try{
                $model = $this->model;
                $model->ledger_entry_id = $data['ledger_entry_id'];
                $model->description     = $data['description'    ];
                $model->quantity        = $data['quantity'       ];
                $model->price           = $data['price'          ];
                $model->total_price     = $data['total_price'    ];
                $model->save();

                return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }
        }
        else{
            return response()->json(["message" => "Record 'ledger_entry_id' Not Found!"], 404);
        }

    }

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->exists() && $this->checkAuthority($data['ledger_entry_id']))
        {
            $model = $this->model::findOrFail($id);
            try{
                $model->ledger_entry_id = $data['ledger_entry_id'];
                $model->description     = $data['description'    ];
                $model->quantity        = $data['quantity'       ];
                $model->price           = $data['price'          ];
                $model->total_price     = $data['total_price'    ];
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
        //
    }

    public function checkAuthority(int $ledger_entry_id)
    {
        return \App\LedgerEntry::where('id', $ledger_entry_id)->where('user_id', auth('api')->user()->id)->exists();
    }
}
