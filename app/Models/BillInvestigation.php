<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillInvestigation extends Model
{
    public function bill() {
        return $this->belongsTo(Bill::class);
    }

    public function investigation() {
        return $this->belongsTo(Investigation::class);
    }
}
