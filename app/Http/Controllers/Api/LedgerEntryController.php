<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerEntryController extends Controller
{
    public function index(LedgerEntryRepositoryInterface $repository)
    {
        /*$description = request('description');
        $ledgerEntries = LedgerEntry::where('description', 'like', '%' . $description . '%')
        ->orderBy('entry_date', 'desc')
        ->paginate(20);

        $ledgerEntries->appends(request()->input())->links();

        #$ledgerEntries = LedgerEntry::orderBy('entry_date', 'desc')->paginate(20);
        return response()->json($ledgerEntries);*/
        $description = request('description');
        return response()->json($repository->findBy(
                ['description' => request('description')],
                ['entry_date' => 'desc', 'description' => 'asc'],
                10
            )
        );
    }
}
