<?php

namespace App\Repositories;

use App\Repositories\Contracts\SqlCommandRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Event;
use Illuminate\Support\Facades\DB;

class SqlCommandEloquent extends UtilEloquent implements SqlCommandRepositoryInterface
{
	protected $model;

	public function __construct(Event $model)
	{
        $this->model = $model;
	}

    public function execute($request)
    {
        //$model = DB::raw($request->code);
        $model = DB::select(DB::raw("$request->code"));

        return response()->json($model, 200);
    }
}
