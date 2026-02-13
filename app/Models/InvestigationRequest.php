<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationRequest extends Model
{
    protected $fillable = [
        'patient_visit_id',
        'investigation_id',
        'requested_by',
        'clinical_diagnoses',
        'requested_at',
        'completed_at',
        'performed_by',
        'specimen',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patientVisit()
    {
        return $this->belongsTo(PatientVisit::class);
    }

    public function investigation()
    {
        return $this->belongsTo(Investigation::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
