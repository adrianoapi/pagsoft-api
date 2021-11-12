<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerItemRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerItem;

class LedgerItemRepositoryEloquent extends UtilEloquent implements LedgerItemRepositoryInterface
{
	public function __construct(LedgerItem $model)
	{
        $this->model = $model;
	}

    public function index($condition, $orderBy, $limit)
    {
        if(!empty($limit)){
            $this->perPage = $limit;
        }

        try{
            $result = $this->model::whereHas('LedgerEntry', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('description', 'like', '%' . $condition['description'] . '%')
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

            return response()->json($result, 200);
        }
        catch(\Exception $e)
        {
            return response()->json(["message" => $e->getMessage()], 500);
        }
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
        if($this->model::where('id', $id)->exists() && $this->checkAuthority($data['ledger_entry_id']))
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
        if(
            $this->model::whereHas('ledgerEntry', function($q){
                $q->where('user_id', auth('api')->user()->id);
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

    public function checkAuthority(int $ledger_entry_id)
    {
        return \App\LedgerEntry::where('id', $ledger_entry_id)->where('user_id', auth('api')->user()->id)->exists();
    }
}
