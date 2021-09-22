<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerItemRepositoryInterface;
use App\LedgerItem;

class LedgerItemRepositoryEloquent implements LedgerItemRepositoryInterface
{
    private $model;
    private $perPage = 10;

	public function __construct(LedgerItem $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {
        //
    }

    public function findAll()
    {
        //
    }

    public function delete(int $id)
    {
        //
    }

    public function update(array $data, int $id)
    {
        //
    }

    public function store(array $data)
    {
        //
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

        return response()->json($model, 200);
    }
}
