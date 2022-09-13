<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CronJob extends Model
{
    protected $fillable = [
        'client_id',
        'updated_user_id',
        'deactivate_user_id',
        'description',
        'link',
        'limit',
        'date',
        'time',
        'every_day',
        'every_time',
        'executed',
    ];

    public function Client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }
}
