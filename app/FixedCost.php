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
    
    public function setEntryDateAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['entry_date'] = date("Y-m-d", strtotime($date));
    }

    public function getEntryDateAttribute($value)
    {
        return $this->attributes['entry_date'] = date("d/m/Y", strtotime($value));
    }

    public function setAmountAttribute($value)
    {
        return $this->attributes['amount'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getAmountAttribute($value)
    {
        return $this->attributes['amount'] = number_format($value, 2, ",", ".");
    }
}
