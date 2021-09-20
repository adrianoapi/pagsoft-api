<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkItem extends Model
{
    protected $table = 'links_items';

    public function link()
    {
        return $this->hasOne(Link::class, 'id', 'link_id');
    }
}
