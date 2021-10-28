<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LedgerEntry extends Model
{
    protected $fillable = [
        'entry_date',
    ];

    public function ledgerGroup()
    {
        return $this->hasOne(LedgerGroup::class, 'id', 'ledger_group_id');
    }

    public function ledgerItems()
    {
        return $this->hasMany(LedgerItem::class, 'ledger_entry_id', 'id');
    }

    public function transitionType()
    {
        return $this->hasOne(TransitionType::class, 'id', 'transition_type_id');
    }
}
