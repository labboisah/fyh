<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Continuation extends Model
{
    protected $guarded = [];

    public function patientVisit(){

        return $this->belongsTo(PatientVisit::class);
    }

    public function writtenBy() {
        return $this->belongsTo(User::class, 'written_by');
    }
}
