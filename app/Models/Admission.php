<?php

namespace App\Models;

use App\Models\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use Syncable;
    
    protected $guarded = [];

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function patientVisit() {
        return $this->belongsTo(PatientVisit::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function fluidBalances(){
        return $this->hasMany(FluidBalance::class);
    }

    public function observations() {
        return $this->hasMany(Observation::class);
    }

    public function discharge() {
        return $this->hasOne(Discharge::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get the labour associated with this admission (if applicable)
     */
    public function labour()
    {
        return $this->hasOne(Labour::class);
    }
}
