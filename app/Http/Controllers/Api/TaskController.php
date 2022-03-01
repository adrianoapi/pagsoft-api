<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\TaskRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = [
            'title'         => request('title'        ),
            'status'        => request('status'       ),
            'level'         => request('level'        ),
            'task_group_id' => request('task_group_id'),
        ];
        
        $orderBy   = ['entry_date' => 'asc', 'description' => 'asc'];

        return $this->repository->findBy($condition, $orderBy, request('limit'));
    }

    public function findById(Request $request)
    {
        return $this->repository->findById($request->id);
    }

    public function create(Request $request)
    {
        return $this->repository->store($request->json()->all());
    }

    public function destroy(Request $request)
    {
        return $this->repository->delete($request->id);
    }
}
