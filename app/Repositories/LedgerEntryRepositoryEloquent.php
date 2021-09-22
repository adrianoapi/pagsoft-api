<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;
use App\LedgerEntry;

class LedgerEntryRepositoryEloquent implements LedgerEntryRepositoryInterface
{
	private $model;
    private $perPage = 10;

	public function __construct(LedgerEntry $model)
	{
		$this->model = $model;
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

	public function findAll()
	{
		return $this->model->all();
	}

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $model = $this->model;

        if(!empty($criteria)){
            foreach ($criteria as $condition => $value) {
                $model = $model->where($condition,'like','%'.$value.'%');
            }
        }

        if(!empty($orderBy)){
            foreach ($orderBy as $order => $value) {
                $model = $model->orderBy($order, $value);
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

        return response()->json($model);
    }
}
