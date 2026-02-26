<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $guarded = [];

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }

    public function patientVisit() {
        return $this->belongsTo(PatientVisit::class);
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
}
