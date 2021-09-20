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

    public function setEntryDateAttribute($value)
    {
        $date = str_replace('/', '-', $value);
        return $this->attributes['entry_date'] = date("Y-m-d", strtotime($date));
    }

    public function setAmountAttribute($value)
    {
        return $this->attributes['amount'] = str_replace(',', '.', str_replace('.', '', $value));
    }

}
