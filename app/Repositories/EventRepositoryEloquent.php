<?php

namespace App\Repositories;

use App\Repositories\Contracts\EventRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Event;
use Illuminate\Support\Facades\DB;

class EventRepositoryEloquent extends UtilEloquent implements EventRepositoryInterface
{
	protected $model;
    protected $perPage = 160;
    private $start;
    private $end;

	public function __construct(Event $model)
	{
        $this->model = $model;
        $this->start = date('Y-m-01');
        $this->end   = date('Y-m-d');
	}

    public function index($request, $condition, $orderBy, $limit)
    {
        #Convert a string em tempo
        $time = strtotime("$request->start");
        $final = date("Y-m-d", strtotime("+1 month", $time));

        $month = date("m",strtotime($final));
        $year = date("Y",strtotime($final));

        $model = $this->model->select(
                'events.*',
                DB::raw('(CASE WHEN DATE_FORMAT(events.start, \'%Y\') <> \''.date('Y').'\' THEN DATE_FORMAT(events.start, \''.date('Y').'-%m-%d\') ELSE events.start END) as start'),
                DB::raw('(CASE WHEN DATE_FORMAT(events.end, \'%Y\') <> \''.date('Y').'\' THEN DATE_FORMAT(events.end, \''.date('Y').'-%m-%d\') ELSE events.start END) as end'),
            )
            ->where('start', '>=', $request->start.' 00:00:00')
            ->where('start', '<=', $request->end.' 23:59:59')
            ->orWhereMonth('start', $month)
            ->where('repeat_year', true)
            ->where('user_id', auth('api')->user()->id);

        if(!empty($orderBy)){
            foreach ($orderBy as $order => $value) {
                $model = $model->orderBy($order, $value);
            }
        }

        if(!empty($limit))
        {
            if($limit > 0 && $limit <= 20){
                $this->perPage =  (int)$limit;
            }
        }

        if (!empty($offset)) {
            $model = $model->skip((int)$offset);
        }

        $model = $model->paginate($this->perPage);

        $model->appends(request()->input())->links();

        return response()->json($model, 200);
    }

    public function store(array $data)
    {
        try{
            $model = $this->model;
            $model->user_id  = auth('api')->user()->id;
            $model->title    = $data['title'   ];
            $model->start    = $data['start'   ];
            $model->end      = $data['end'     ];
            $model->editable = $data['editable'];
            $model->all_day  = $data['all_day' ];
            $model->location = $data['location'];

            $model->backgroundColor  = $data['backgroundColor' ];
            
            $model->save();

            return response()->json(["id" => $model->id, "message" => "Created Successful!", "body" => $model->getAttributes()], 201);
        }
        catch(\Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    public function update(array $data, int $id)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->title    = $data['title'   ];
                $model->start    = $data['start'   ];
                $model->end      = $data['end'     ];
                $model->editable = $data['editable'];
                $model->all_day  = $data['all_day' ];
                $model->location = $data['location'];

                $model->backgroundColor  = $data['backgroundColor' ];

                $model->save();

                return response()->json(['message' => 'Update Successful!', "body" => $model->getAttributes()], 200);
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
                return response()->json(["message" => $e->getMessage()], 500);
            }
        }
        else
        {
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function today()
    {
        $today  = new \DateTime();
        $today->modify('-3 hours'); # GMT-3

        $result = $this->model::where('message_id', false)
                            ->where('start', '>=', $today->format('Y-m-d').' 00:00:00')
                            ->where('start', '<=', $today->format('Y-m-d').' 23:59:59')
                            ->get();

        foreach($result as $event):

            # Registra a mensagem
            $message = new \App\Message();
            $message->title = "Agenda Evento";
            $message->body  = $event->title;
            $message->type  = "email";

            if($message->save())
            {
                # Relaciona o evento com a mensagem
                $event->message_id = $message->id;
                $event->save();

                # Registra uma acao
                $action = new \App\Action();
                $action->user_id    =  $event->user_id;
                $action->message_id =  $event->message_id;
                $action->save();

            }

        endforeach;

    }
}
