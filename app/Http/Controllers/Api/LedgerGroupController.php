<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\LedgerGroupRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerGroupController extends Controller
{
    private $repository;

    public function __construct(LedgerGroupRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list()
    {
        return $this->repository->getAll();
    }
}
