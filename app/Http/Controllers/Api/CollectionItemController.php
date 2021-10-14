<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\CollectionItemRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionItemController extends Controller
{
    private $repository;

    public function __construct(CollectionItemRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = ['description' => request('description')];
        $orderBy   = ['id' => 'desc', 'description' => 'asc' ];

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

    public function edit(Request $request)
    {
        return $this->repository->update($request->json()->all(), $request->id);
    }

    public function destroy(Request $request)
    {
        return $this->repository->delete($request->id);
    }
}
