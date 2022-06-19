<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\DiagramRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiagramController extends Controller
{
    private $repository;

    public function __construct(DiagramRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $condition = [];
        $orderBy   = ['id' => 'desc'];

        return $this->repository->findBy($condition, $orderBy, request('limit'));
    }

    public function findById(Request $request)
    {
        return $this->repository->getDiagramById($request->id);
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

    public function flow(Request $request)
    {
        $data = [
            'ledger_group_id' => request('ledger_group_id'),
            'entry_date_begin' => request('entry_date_begin'),
            'entry_date_end' => request('entry_date_end'),
        ];
        return $this->repository->flow($data);
    }
}
