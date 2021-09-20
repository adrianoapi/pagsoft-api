<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    public function items()
    {
        return $this->hasMany(CollectionItem::class, 'collection_id', 'id')->orderBy('title', 'asc');
    }
}
