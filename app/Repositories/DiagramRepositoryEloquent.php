<?php

namespace App\Repositories;

use App\Repositories\Contracts\DiagramRepositoryInterface;
use App\Repositories\UtilEloquent;
use App\Diagram;
use App\DiagramItem;
use App\DiagramLinkData;

class DiagramRepositoryEloquent extends UtilEloquent implements DiagramRepositoryInterface
{
	protected $model;
    protected $perPage = 100;

	public function __construct(Diagram $model)
	{
        $this->model = $model;
	}

    public function index($condition, $orderBy, $limit)
    {
        return $this->findBy($condition, $orderBy, $limit);
    }

    public function getDiagramById(int $id)
    {
        $data  = [];
        $model = $this->model;
        if($model::where('id', $id)->where('user_id', auth('api')->user()->id)->exists())
        {
            $model = $this->model::findOrFail($id);
            $data  = [
                'diagram' => $model->attributesToArray(),
                'items'   => $this->factoreStructure($model->items),
                'linkData'   => $this->factoreStructure($model->linkData),
            ];

            return response()->json($data);
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
            $model->user_id  = auth('api')->user()->id;
            $model->title    = $data['title'];
            $model->type     = $data['type' ];

            if($model->save()){

                $item =  $data['body'];

                if($model->type == 'mindMap'){

                    foreach($item['nodeDataArray'] as $item):

                        $modelItem = new DiagramItem();
                        $modelItem->diagram_id = $model->id;
                        $modelItem->key = $item['key'];
                        if(array_key_exists('parent', $item)){
                            $modelItem->parent = $item['parent'];
                        }
                        if(array_key_exists('text', $item)){
                            $modelItem->text = $item['text'];
                        }
                        if(array_key_exists('brush', $item)){
                            $modelItem->brush = $item['brush'];
                        }
                        if(array_key_exists('dir', $item)){
                            $modelItem->dir = $item['dir'];
                        }
                        if(array_key_exists('loc', $item)){
                            $modelItem->loc = $item['loc'];
                        }
                        $modelItem->save();

                    endforeach;
                }else{

                    foreach($item['linkDataArray'] as $value):

                        $modelLink = new DiagramLinkData();
                        $modelLink->diagram_id = $model->id;
                        if(array_key_exists('from', $value)){
                        $modelLink->from = $value['from'];
                        }
                        if(array_key_exists('to', $value)){
                            $modelLink->to = $value['to'];
                        }
                        if(array_key_exists('fromPort', $value)){
                            $modelLink->fromPort = $value['fromPort'];
                        }
                        if(array_key_exists('toPort', $value)){
                            $modelLink->toPort = $value['toPort'];
                        }
                        if(array_key_exists('visible', $value)){
                            $modelLink->visible = $value['visible'];
                        }
                        if(array_key_exists('text', $value)){
                            $modelLink->text = $value['text'];
                        }
                        $modelLink->save();

                    endforeach;

                    foreach($item['nodeDataArray'] as $item):

                        $modelItem = new DiagramItem();
                        $modelItem->diagram_id = $model->id;
                        $modelItem->key = $item['key'];

                        if(array_key_exists('category', $item)){
                            $modelItem->category = $item['category'];
                        }
                        if(array_key_exists('text', $item)){
                            $modelItem->text = $item['text'];
                        }

                        if(array_key_exists('loc', $item)){
                            $modelItem->loc = $item['loc'];
                        }
                        $modelItem->save();

                    endforeach;

                }

            }

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

                # Corrigir via relacionamento no banco
                DiagramItem::where('diagram_id',     $model->id)->delete();
                DiagramLinkData::where('diagram_id', $model->id)->delete();

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
}
