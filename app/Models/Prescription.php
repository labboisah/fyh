<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $guarded = [];

    public function prescriptionItems() {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function patientVisit() {
        return $this->belongsTo(PatientVisit::class);
    }
}
