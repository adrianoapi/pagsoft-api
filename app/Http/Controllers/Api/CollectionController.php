<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\CollectionRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    private $repository;

    public function __construct(CollectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = ['title' => request('title')];
        $orderBy   = ['id' => 'desc', 'description' => 'asc' ];

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
}
