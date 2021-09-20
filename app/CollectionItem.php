<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionItem extends Model
{
    public function collection()
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }

    public function images()
    {
        return $this->hasMany(CollectionItemImage::class, 'collection_item_id', 'id');
    }

    public function setReleaseAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['release'] = date("Y-m-d", strtotime($date));
    }

    public function getReleaseAttribute($value)
    {
        return $this->attributes['release'] = !empty($value) ? date("d/m/Y", strtotime($value)) : NULL;
    }
}
