<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Labour extends Model
{
    use SoftDeletes;

    protected $table = 'labours';

    protected $fillable = [
        'patient_id',
        'admission_id',
        'recorded_by',
        'labour_onset_time',
        'mode_of_onset',
        'reason_for_induction',
        'gestational_weeks',
        'labour_type',
        'previous_obstetric_history',
        'cervical_state',
        'show',
        'rupture_of_membranes',
        'liquor',
        'blood_pressure',
        'pulse_rate',
        'temperature',
        'respiration_rate',
        'stage',
        'first_stage_started_at',
        'second_stage_started_at',
        'third_stage_started_at',
        'fetal_heart_rate',
        'fetal_monitoring_notes',
        'complications',
        'clinical_notes',
        'status',
    ];

    protected $casts = [
        'labour_onset_time' => 'datetime',
        'first_stage_started_at' => 'datetime',
        'second_stage_started_at' => 'datetime',
        'third_stage_started_at' => 'datetime',
    ];

    /**
     * Get the patient in labour
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the admission for this labour
     */
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    /**
     * Get the midwife/staff who recorded this
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Get all progress records for this labour
     */
    public function progressRecords()
    {
        return $this->hasMany(LabourProgress::class);
    }

    /**
     * Get the delivery associated with this labour
     */
    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }

    /**
     * Calculate duration of labour stage
     */
    public function calculateStageDuration($stage)
    {
        $startTime = match($stage) {
            'first' => $this->first_stage_started_at,
            'second' => $this->second_stage_started_at,
            'third' => $this->third_stage_started_at,
            default => null
        };

        if (!$startTime) {
            return null;
        }

        $endTime = match($stage) {
            'first' => $this->second_stage_started_at ?? now(),
            'second' => $this->third_stage_started_at ?? now(),
            'third' => now(),
            default => now()
        };

        return $startTime->diffInMinutes($endTime);
    }

    /**
     * Scope to get active labours
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope to get complicated labours
     */
    public function scopeComplicated($query)
    {
        return $query->where('status', 'complicated');
    }
}
