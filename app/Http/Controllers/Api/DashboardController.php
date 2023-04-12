<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $user_id;

    public function __construct()
    {
        $this->user_id = auth('api')->user()->id;
        $this->date_begin = date('Y-m-d');
        $this->date_begin = date('Y-m-d', strtotime("$this->date_begin -30 days"));
        $this->date_end   = date('Y-m-d');
    }

    public function finance()
    {
        //monthly
        $date_begin = date('Y-m-d', strtotime("$this->date_begin -1 year"));

        $expensive = DB::table('ledger_entries')
        ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
        ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
        ->where([
            ['ledger_entries.user_id', $this->user_id],
            ['transition_types.action', 'expensive'],
            ['transition_types.credit_card', '<>', true],
            ['ledger_entries.entry_date', '>=', $date_begin],
            ['ledger_entries.entry_date', '<=', $this->date_end]
        ])
        ->groupBy('dt_lancamento')
        ->orderByDesc('dt_lancamento')
        ->get();

        $recipe = DB::table('ledger_entries')
        ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
        ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
        ->where([
            ['ledger_entries.user_id', $this->user_id],
            ['transition_types.action', 'recipe'],
            ['ledger_entries.entry_date', '>=', $date_begin],
            ['ledger_entries.entry_date', '<=', $this->date_end]
        ])
        ->groupBy('dt_lancamento')
        ->orderByDesc('dt_lancamento')
        ->get();

        return response()->json($this->legderSort($expensive, $recipe), 200);
    }

    public function cart(Request $request)
    {
        $date_begin = date('Y-m-d', strtotime("$this->date_begin -1 year"));

        $expensive = DB::table('ledger_entries')
        ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
        ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
        ->where([
            ['ledger_entries.user_id', $this->user_id],
            ['transition_types.action', 'expensive'],
            ['transition_types.credit_card', 1],
            ['ledger_entries.entry_date', '>=', $date_begin],
            ['ledger_entries.entry_date', '<=', $this->date_end]
        ])
        ->groupBy('dt_lancamento')
        ->orderByDesc('dt_lancamento')
        ->get();

        $expensiveD = DB::table('ledger_entries')
        ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
        ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
        ->where([
            ['ledger_entries.user_id', $this->user_id],
            ['transition_types.action', 'expensive'],
            ['transition_types.credit_card', false],
            ['ledger_entries.entry_date', '>=', $date_begin],
            ['ledger_entries.entry_date', '<=', $this->date_end]
        ])
        ->groupBy('dt_lancamento')
        ->orderByDesc('dt_lancamento')
        ->get();

        return response()->json([
            "cartao" => $expensive,
            "debito" => $expensiveD
        ], 200);
    }

    protected function range(Request $request)
    {
        if($request->type == "today")
        {
            $expensive = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'), 'ledger_entries.entry_date as dt_lancamento')
            ->where([
                ['ledger_entries.user_id', $this->user_id],
                ['transition_types.action', 'expensive'],
                ['transition_types.credit_card', '<>', true],
                ['ledger_entries.entry_date', '>=', $this->date_begin],
                ['ledger_entries.entry_date', '<=', $this->date_end]
            ])
            ->groupBy('ledger_entries.entry_date')
            ->orderByDesc('ledger_entries.entry_date')
            ->get();

            $recipe = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'), 'ledger_entries.entry_date as dt_lancamento')
            ->where([
                ['transition_types.action', 'recipe'],
                ['ledger_entries.entry_date', '>=', $this->date_begin],
                ['ledger_entries.entry_date', '<=', $this->date_end]
            ])
            ->groupBy('ledger_entries.entry_date')
            ->orderByDesc('ledger_entries.entry_date')
            ->get();
        }
        elseif($request->type == "monthly")
        {
            $date_begin = date('Y-m-d', strtotime("$this->date_begin -1 year"));

            $expensive = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
            ->where([
                ['ledger_entries.user_id', $this->user_id],
                ['transition_types.action', 'expensive'],
                ['transition_types.credit_card', '<>', true],
                ['ledger_entries.entry_date', '>=', $date_begin],
                ['ledger_entries.entry_date', '<=', $this->date_end]
            ])
            ->groupBy('dt_lancamento')
            ->orderByDesc('dt_lancamento')
            ->get();

            $recipe = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw("DATE_FORMAT(ledger_entries.entry_date, '%Y-%m') dt_lancamento"))
            ->where([
                ['ledger_entries.user_id', $this->user_id],
                ['transition_types.action', 'recipe'],
                ['ledger_entries.entry_date', '>=', $date_begin],
                ['ledger_entries.entry_date', '<=', $this->date_end]
            ])
            ->groupBy('dt_lancamento')
            ->orderByDesc('dt_lancamento')
            ->get();
        }
        elseif($request->type == "annual")
        {
            $recipe = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw('YEAR(ledger_entries.entry_date) dt_lancamento'))
            ->where([
                ['ledger_entries.user_id', $this->user_id],
                ['transition_types.action', 'recipe']
            ])
            ->groupBy('dt_lancamento')
            ->orderByDesc('dt_lancamento')
            ->get();

            $expensive = DB::table('ledger_entries')
                ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
                ->select(DB::raw('sum( ledger_entries.amount ) as total'), DB::raw('YEAR(ledger_entries.entry_date) dt_lancamento'))
                ->where([
                    ['transition_types.action', 'expensive'],
                    ['transition_types.credit_card', '<>', true]
                ])
                ->groupBy('dt_lancamento')
                ->orderByDesc('dt_lancamento')
                ->get();
        }

        return response()->json([
            "recipe" => $recipe,
            "expensive" => $expensive
        ], 200);
    }

    protected function legderSort($expensive, $recipe)
    {
        $dtLancamento    = array();
        $lancamentoTotal = array();

        $tempDespesas = array();
        foreach($expensive as $value):
            $tempDespesas[$value->dt_lancamento] = $value->total;
            array_push($dtLancamento, $value->dt_lancamento);
        endforeach;

        $tempLucro = array();
        foreach($recipe as $value):
            $tempLucro[$value->dt_lancamento] = $value->total;
            if(!in_array($value->dt_lancamento, $dtLancamento)){
                array_push($dtLancamento, $value->dt_lancamento);
            }
        endforeach;

        foreach($dtLancamento as $value):
            if(array_key_exists($value, $tempDespesas)){
                $lancamentoTotal[$value]['despesa'] = $tempDespesas[$value];
            }else{
                $lancamentoTotal[$value]['despesa'] = 0;
            }
            if(array_key_exists($value, $tempLucro)){
                $lancamentoTotal[$value]['lucro'] = $tempLucro[$value];
            }else{
                $lancamentoTotal[$value]['lucro'] = 0;
            }
        endforeach;

        return  $lancamentoTotal;
    }

}
