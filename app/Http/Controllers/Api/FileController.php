<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\FileRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    private $repository;

    public function __construct(FileRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = ['name' => request('name')];
        $orderBy   = ['id' => 'desc', 'name' => 'asc' ];

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
