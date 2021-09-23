<?php

namespace App\Repositories;

class UtilEloquent
{
    protected $model;
    protected $perPage = 10;

    public function findById(int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                return response()->json($model, 200);
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

        return response()->json($model, 200);
    }

    public function factoreStructure(object $data)
    {
        $items = [];
        foreach($data as $value):
            $items[] = $value->attributesToArray();
        endforeach;

        return $items;
    }
}
