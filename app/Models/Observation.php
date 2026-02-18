<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Observation extends Model
{
    protected $guarded = [];
    
    public function recordedBy() : Returntype {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function patientVisit() {
        return $this->belongsTo(PatientVisit::class);
    }
}
