<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\CollectionSharingRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CollectionSharingController extends Controller
{
    private $repository;

    public function __construct(CollectionSharingRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        return $this->repository->store($request->id, $request->json()->all());
    }

    public function destroy(Request $request)
    {
        return $this->repository->delete($request->id);
    }
}
