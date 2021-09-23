<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LedgerItem extends Model
{
    public function ledgerEntry()
    {
        return $this->hasOne(LedgerEntry::class, 'id', 'ledger_entry_id');
    }
}
