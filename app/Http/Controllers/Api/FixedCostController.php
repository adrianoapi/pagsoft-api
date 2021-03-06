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
        $orderBy   = ['entry_date' => 'asc', 'description' => 'asc'];

        return $this->repository->findBy($condition, $orderBy, request('limit'));
    }

    public function findById(Request $request)
    {
        return $this->repository->findById($request->id);
    }

    public function getCollection(Request $request)
    {
        return $this->repository->getCollectionById($request->id);
    }

    public function create(Request $request)
    {
        return $this->repository->store($request->json()->all());
    }

    public function edit(Request $request)
    {
        return $this->repository->update($request->json()->all(), $request->id);
    }

    public function destroy(Request $request)
    {
        return $this->repository->delete($request->id);
    }

    public function trash(Request $request)
    {
        return $this->repository->updateStatus($request->id, FALSE);
    }

    public function restore(Request $request)
    {
        return $this->repository->updateStatus($request->id, TRUE);
    }
}
