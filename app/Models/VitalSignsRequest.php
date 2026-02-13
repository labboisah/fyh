<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSignsRequest extends Model
{
    protected $fillable = [
        'patient_visit_id',
        'requested_by',
        'status',
        'notes',
    ];
    
    Public function patientVisit() {
        return $this->belongsTo(PatientVisit::class, 'patient_visit_id');
    }

    Public function patientVisitVitalSigns() {
        return $this->hasMany(PatientVisitVitalSign::class, 'vital_signs_request_id');
    }
}
