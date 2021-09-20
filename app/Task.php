<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function taskGroup()
    {
        return $this->hasOne(TaskGroup::class, 'id', 'task_group_id');
    }
}
