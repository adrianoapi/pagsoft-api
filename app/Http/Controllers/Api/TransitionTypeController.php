<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\TransitionTypeRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransitionTypeController extends Controller
{
    private $repository;

    public function __construct(TransitionTypeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        return $this->repository->getAll();
    }
}
