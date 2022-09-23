<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagramClassProperty extends Model
{
    public function diagramClass()
    {
        return $this->hasOne(DiagramClass::class, 'id', 'diagram_class_id');
    }
}
