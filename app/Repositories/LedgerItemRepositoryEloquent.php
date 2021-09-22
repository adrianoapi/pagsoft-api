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

    public function delete(int $id)
    {
        //
    }

    public function update(array $data, int $id)
    {
        //
    }


}
