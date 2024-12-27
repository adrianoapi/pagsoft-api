<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\SqlCommandRepositoryInterface;

use App\Http\Controllers\Controller;
use App\SqlCommand;
use Illuminate\Http\Request;

class SqlCommandController extends Controller
{
    private $repository;

    public function __construct(SqlCommandRepositoryInterface $repository)
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
        //
    }
    
    public function execute(Request $request)
    {
        return $this->repository->execute($request);
    }

}
