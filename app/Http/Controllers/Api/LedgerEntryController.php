<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerEntryController extends Controller
{
    private $repository;

    public function __construct(LedgerEntryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return response()->json($this->repository->findBy(
                [
                    'description' => request('description')
                ],
                [
                    'entry_date' => 'desc',
                    'description' => 'asc'
                ],
                request('limit')
            )
        );
    }
}
