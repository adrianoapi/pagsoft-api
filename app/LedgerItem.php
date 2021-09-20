<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LedgerItem extends Model
{
    public function ledgerEntry()
    {
        return $this->hasOne(LedgerEntry::class, 'id', 'ledger_entry_id');
    }

    public function setPriceAttribute($value)
    {
        return $this->attributes['price'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getPriceAttribute($value)
    {
        return $this->attributes['price'] = number_format($value, 2, ",", ".");
    }

    public function setTotalPriceAttribute($value)
    {
        return $this->attributes['total_price'] = str_replace(',', '.', str_replace('.', '', $value));
    }

    public function getTotalPriceAttribute($value)
    {
        return $this->attributes['total_prcie'] = number_format($value, 2, ",", ".");
    }
}
