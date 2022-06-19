<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotifyController extends Controller
{
    
    public function __construct()
    {
        $this->date_begin = date('Y-m-d');
        $this->date_begin = date('Y-m-d', strtotime("$this->date_begin -30 days"));
        $this->date_end   = date('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expensive = DB::table('events')
        ->select("*")
        ->where([
            ['events.start', '>=', $this->date_begin],
            ['events.end', '<=', $this->date_end]
        ])
        ->orderByDesc('start')
        ->get();
    }


}
