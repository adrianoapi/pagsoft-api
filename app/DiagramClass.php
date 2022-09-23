<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagramClass extends Model
{
    public function diagram()
    {
        return $this->hasOne(Diagram::class, 'id', 'diagram_id');
    }

    public function diagramClassProperties()
    {
        return $this->hasMany(DiagramClassProperty::class, 'diagram_class_id', 'id');
    }

    public function diagramClassMethods()
    {
        return $this->hasMany(DiagramClassMethod::class, 'diagram_class_id', 'id');
    }
}
