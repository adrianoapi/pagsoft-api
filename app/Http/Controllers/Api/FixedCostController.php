<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\FixedCostRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FixedCostController extends Controller
{
    private $repository;

    public function __construct(FixedCostRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = ['status' => request('status')];
        $orderBy   = ['entry_date' => 'desc', 'description' => 'asc'];

        return $this->repository->findBy($condition, $orderBy, request('limit'));
    }
}
