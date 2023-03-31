<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\User;

class UserRepositoryEloquent extends UtilEloquent implements UserRepositoryInterface
{
	protected $model;
    protected $perPage = 10;

	public function __construct(User $model)
	{
        $this->model = $model;
	} 

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        $model = $this->model;
        if($model::where('level','>', 0)->where('id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::get();

                if(!empty($model) && !empty($relations))
                {
                    #Injeta os objetos relacionados a matriz
                    foreach($relations as $relation):
                        $model->$relation;
                    endforeach;
                }
                
                return response()->json($model, 200);
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

    public function findById(int $id, array $relations = [])
    {
        if(
            $this->model::whereHas('Client', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
        {
            try{
                $result = $this->model::findOrFail($id);
                return response()->json($result, 200);
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
        if(
            $this->model::whereHas('Client', function($q){
                $q->where('user_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
        {
            try{
                $model = $this->model::findOrFail($id);
                foreach($data as $key => $value):
                    $model->$key = $value;
                endforeach;
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
        if(Client::where('id', $data['client_id'])->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model;
                foreach($data as $key => $value):
                    $model->$key = $value;
                endforeach;
                $model->save();
    
                return response()->json(["id" => $model->id, 'message' => 'Created Successful!'], 201);
            }
            catch(\Exception $e){
                return response()->json(['message' => $e->getMessage()]);
            }

        }else{
            return response()->json(["message" => "Record Not Found!"], 404);
        }
    }

    public function updateStatus(int $id, bool $flag)
    {
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            try{
                $model = $this->model::findOrFail($id);
                $model->status = $flag;
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

    public function delete(int $id)
    {
        if(
            $this->model::whereHas('Client', function($q){
                $q->where('client_id', auth('api')->user()->id);
            })
            ->where('id', $id)
            ->exists()
        )
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

    public function run()
    {
        $result = $this->model::where('status', true)->get();

        foreach($result as $value):

            $flag   = true;
            $today  = new \DateTime();

            $today->modify('-3 hours'); # GMT-3

            #$today->format('H:i:s');
            /**
             * Checa se:
             * $flag é verdadeira, e
             * $every_day é falso, e
             * $date é diferente de vazio
            */
            if($flag == true && $value->every_day != true && !empty($value->date))
            {
                /**
                 * Checa se:
                 * $date é diferente de hoje
                 */
                if($value->date != $today->format('Y-m-d'))
                {
                    $flag = false;
                }
            }

            /**
             * Checa se:
             * $flag é verdadeira, e
             * $every_time é falso, e
             * $time é diferente de vazio
            */
            if($flag == true && $value->every_time != true && !empty($value->time))
            {
                $today->modify('-30 seconds');
                $menos  = explode(":",$today->format('H:i:s')); # Armazena H min s -1min

                $today->modify('+60 seconds');
                $mais   = explode(":",$today->format('H:i:s')); # Armazena H min s +1min
                
                $margemAnt  = new \DateTime();
                $margemAnt  = $margemAnt->setTime($menos[0], $menos[1], $menos[2]);
                
                $margemPost = new \DateTime();
                $margemPost = $margemPost->setTime($mais[0], $mais[1], $mais[2]);
                
                $nValueTime = explode(":", $value->time);
                $timeLink   = new \DateTime();
                $timeLink   = $timeLink->setTime($nValueTime[0], $nValueTime[1], $nValueTime[2]);

                #echo "{$margemAnt->format('H:i:s')} {$timeLink->format('H:i:s')} {$margemPost->format('H:i:s')}<br>";
                if($timeLink < $margemAnt || $timeLink > $margemPost)
                {
                    $flag = false;
                }
            }
            
            if($flag == true && $value->limit > 0)
            {
                if($value->executed >= $value->limit)
                {
                    $flag = false;
                }
            }
            
            if($flag)
            {
                $context = stream_context_create(['http' => ['ignore_errors' => true]]);
                $result = file_get_contents($value->link, false, $context);

                $value->executed += 1;
                $value->save();
            }

        endforeach;
    }
}
