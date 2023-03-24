<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionSharing extends Model
{
    public function collection()
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'user_id', 'id');
    }
}
