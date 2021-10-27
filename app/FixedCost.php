<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FixedCost extends Model
{
    public function ledgerGroup()
    {
        return $this->hasOne(LedgerGroup::class, 'id', 'ledger_group_id');
    }

    public function transitionType()
    {
        return $this->hasOne(TransitionType::class, 'id', 'transition_type_id');
    }
}
