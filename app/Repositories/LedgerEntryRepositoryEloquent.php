<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;
use App\LedgerEntry;

class LedgerEntryRepositoryEloquent implements LedgerEntryRepositoryInterface
{
	private $model;

	public function __construct(LedgerEntry $model)
	{
		$this->model = $model;
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

        if (!empty($limit)) {
            $model = $model->take((int)$limit);
        }

        if (!empty($offset)) {
            $model = $model->skip((int)$offset);
        }

        return $model->get();
    }
}
