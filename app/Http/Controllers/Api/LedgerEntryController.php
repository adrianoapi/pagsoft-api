<?php

namespace App\Http\Controllers\Api;

use App\LedgerEntry;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LedgerEntryController extends Controller
{
    public function index()
    {
        $ledgerEntries = LedgerEntry::orderBy('entry_date', 'desc')->paginate(20);
        return response()->json($ledgerEntries);
    }
}
