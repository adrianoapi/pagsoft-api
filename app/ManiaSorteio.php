<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ManiaSorteio extends Model
{
    protected $connection = '';
    protected $table = 'tbl_sorteios';

    public function set_connection($val){
        $this->connection=$val;
    }
    public function selectSorteios($order = 'id DESC', $limit = 50)
    {
        $sql = "
            SELECT *
            FROM $this->table
            ORDER BY $order
            LIMIT 0, $limit;
        ";
        $results=DB::connection($this->connection)
            ->select(DB::raw($sql));
        return $results;
    }

    public function deleteSorteio(int $id)
    {

        $results = DB::connection($this->connection)
        ->table($this->table)
        ->where('id', $id)
        ->delete();

        return $results;
    }


    public function close_connection(){
        DB::disconnect('mania');
    }
}
