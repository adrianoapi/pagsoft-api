<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiagramClassMethodParameter extends Model
{
    public function diagramClassMethod()
    {
        return $this->hasOne(DiagramClassMethod::class, 'id', 'diagram_class_method_id');
    }
}
