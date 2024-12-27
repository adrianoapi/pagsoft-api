<?php

namespace App\Repositories;

use App\Repositories\Contracts\SqlCommandInterface;
use App\Repositories\UtilEloquent;
use App\Event;
use Illuminate\Support\Facades\DB;

class SqlCommandEloquent extends UtilEloquent implements SqlCommandInterface
{
	protected $model;
    protected $perPage = 160;
    private $start;
    private $end;

	public function __construct(Event $model)
	{
        $this->model = $model;
	}

    public function index($request, $condition, $orderBy, $limit)
    {
        $model = DB::select("select @@sql_mode");

        return response()->json($model, 200);
    }
}
