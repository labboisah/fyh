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
        'status',
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

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function investigationResults()
    {
        return $this->hasMany(InvestigationResult::class);
    }
}
