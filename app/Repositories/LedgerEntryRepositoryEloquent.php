<?php

namespace App\Repositories;

use App\Repositories\Contracts\LedgerEntryRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\LedgerEntry;
use Illuminate\Support\Facades\DB;

class LedgerEntryRepositoryEloquent extends UtilEloquent implements LedgerEntryRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(LedgerEntry $model)
	{
        $this->model = $model;
	}

    public function getCollectionById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            $model = $this->model::findOrFail($id);
            $data  = [
                'collection'     => $model->attributesToArray(),
                'ledgerGroup'    => $model->ledgerGroup->attributesToArray(),
                'transitionType' => $model->transitionType->attributesToArray(),
                'ledgerItems'    => $this->factoreStructure($model->ledgerItems),
            ];

            return response()->json($data);
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function delete(int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->delete();

                return response()->json(["message" => "Record Deleted"], 202);
            }
            catch(\Exception $e)
            {
                return response()->json(["message" => $e->getMessage()]);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->ledger_group_id    = $data['ledger_group_id'   ];
                $model->transition_type_id = $data['transition_type_id'];
                $model->description        = $data['description'       ];
                $model->entry_date         = $data['entry_date'        ];
                $model->amount             = $data['amount'            ];
                $model->installments       = $data['installments'      ];
                $model->save();

                return response()->json(['message' => 'Update Successful!', 'data' => $model], 200);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $model->ledger_group_id    = $data['ledger_group_id'   ];
            $model->transition_type_id = $data['transition_type_id'];
            $model->description        = $data['description'       ];
            $model->entry_date         = $data['entry_date'        ];
            $model->amount             = $data['amount'            ];
            $model->installments       = $data['installments'      ];
            $model->user_id            = auth('api')->user()->id;
            $model->save();

            return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function flow(array $data)
    {
        $begin = $data['entry_date_begin'];
        $end   = $data['entry_date_end'  ];
        $id    = $data['ledger_group_id' ];

       try{
            $select = DB::table('ledger_entries')
            ->join('transition_types', 'ledger_entries.transition_type_id', '=', 'transition_types.id')
            ->select(DB::raw('sum( ledger_entries.amount ) as total'))
            ->where([
                ['transition_types.action', 'expensive'],
                ['ledger_entries.ledger_group_id', $id],
                ['ledger_entries.entry_date', '>=', $begin],
                ['ledger_entries.entry_date', '<=', $end]
            ])
            ->get();

            return response()->json(['data' => $select], 200);
       }
       catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
       }
    }
}
