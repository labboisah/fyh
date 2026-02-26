<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discharge extends Model
{
    protected $guarded = [];
    
    public function admission() : Returntype {
        return $this->belongsTo(Admission::class);
    }

    public function dischargedBy() {
        return $this->belongsTo(User::class, 'discharge_by');
    }
}
