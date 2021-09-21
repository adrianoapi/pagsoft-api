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

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $data  = array_merge($data, ['user_id' => auth('api')->user()->id]);

            $model->ledger_group_id = 12;
            $model->transition_type_id = 1;
            $model->description = "Almoço cc Neon";
            $model->entry_date = "2016-09-20";
            $model->amount = "30.50";
            $model->installments = 0;
            $model->user_id = 1;

            if($model->save()){
                return ['status' => true, 'msg' => 'Create Success!'];
            }
         }
         catch(\Exception $e){
            return ['status' => false, 'msg' => $e->getMessage()];
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
