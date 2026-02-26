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

    public function bills() {
        return $this->hasMany(Bill::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function vitalSigns() {
        return $this->hasMany(VitalSign::class);
    }

    public function investigationRequests() {
        return $this->hasMany(InvestigationRequest::class);
    }

    /**
     * Get the user who created this visit record
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function admissions() {
        return $this->hasMany(Admission::class);
    }

    public function prescriptions(){
        return $this->hasMany(Prescription::Class);
    }

    public function continuations(){
        return $this->hasMany(Continuation::Class);
    }

    public function registeredAdmission() {
        return $this->admissions->where('status','registered')->first();
    }

    public function dischargedAdmission() {
        return $this->admissions->where('status','discharged')->first();
    }

    public function confirmAdmission() {
        $admission = null;
        foreach($this->admissions->where('status','confirmed') as $adm){
            $admission = $adm;
        }
        return $admission;
    }

    public function admissionStatus() {
        $status = 'Not Admitted';

        if($this->registeredAdmission()){
            $status = 'Registered';
        }elseif($this->confirmAdmission()){
            $status = 'Confirmed';
        }elseif($this->dischargedAdmission()){
            $status = 'Discharged';
        }

        return $status;
    }

    public function observations() {
        return $this->hasMany(Observation::class);
    }

    public function fluidBalances() {
        return $this->hasMany(FluidBalance::class);
    }
}
