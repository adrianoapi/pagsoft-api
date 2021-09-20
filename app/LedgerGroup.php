<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LedgerGroup extends Model
{
    public function ledgerGroup()
    {
        return $this->hasOne(LedgerGroup::class, 'id', 'ledger_group_id');
    }
}
