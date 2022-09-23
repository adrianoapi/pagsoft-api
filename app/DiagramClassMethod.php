<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagramClassMethod extends Model
{
    public function diagramClass()
    {
        return $this->hasOne(DiagramClass::class, 'id', 'diagram_class_id');
    }

    public function diagramClassMethodParameters()
    {
        return $this->hasMany(DiagramClassMethodParameter::class, 'diagram_class_method_id', 'id');
    }
}
