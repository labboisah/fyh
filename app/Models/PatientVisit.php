<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientVisit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'visit_date',
        'visit_type',
        'reason_for_visit',
        'clinical_notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
    ];

    /**
     * Get the patient this visit belongs to
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who created this visit record
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
