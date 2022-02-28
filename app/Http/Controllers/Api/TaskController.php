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
        #$condition = ['description' => request('description')];
        #$orderBy   = ['entry_date' => 'desc', 'description' => 'asc'];

        return $this->repository->getAll();
    }
}
