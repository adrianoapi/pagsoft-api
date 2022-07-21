<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionItemImage extends Model
{
    public function collectionItem()
    {
        return $this->hasOne(CollectionItem::class, 'id', 'collection_item_id');
    }
}
