<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\EventRepositoryInterface;

use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $repository;

    public function __construct(EventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $condition = ['title' => request('title')];
        $orderBy   = ['start' => 'asc' ];

        return $this->repository->index($request, $condition, $orderBy, request('limit'));
    }

    public function findById(Request $request)
    {
        return $this->repository->findById($request->id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public function today()
    {
        return $this->repository->today();
    }

}
