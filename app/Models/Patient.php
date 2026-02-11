<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'hospital_number',
        'payment_id',
        'registration_date',
        'is_walkIn',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'is_walkIn' => 'boolean',
    ];

    /**
     * Get the demographic information for this patient
     */
    public function demographic()
    {
        return $this->hasOne(PatientDemographic::class);
    }

    /**
     * Get all visits for this patient
     */
    public function visits()
    {
        return $this->hasMany(PatientVisit::class);
    }

    /**
     * Get appointments for this patient
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get next of kin information
     */
    public function nextOfKin()
    {
        return $this->hasOne(NextOfKin::class);
    }

    /**
     * Get all bills for this patient
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Get all payments for this patient
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate unique hospital number
     */
    public static function generateHospitalNumber()
    {
        $year = substr(date('Y'), 2, 2);
        $count = static::count() + 1;
        return 'FYH' . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Search patient by hospital number, payment ID, or phone number
     */
    public static function search($query)
    {
        return static::whereHas('demographic', function ($q) use ($query) {
            $q->where('phone_number', 'like', "%{$query}%");
        })
        ->orWhere('hospital_number', 'like', "%{$query}%")
        ->orWhere('name', 'like', "%{$query}%")
        ->get();
    }

    /**
     * Check if patient already exists (prevent duplicates)
     */
    public static function checkDuplicate($email = null, $phoneNumber = null)
    {
        if ($email) {
            $exists = PatientDemographic::where('email', $email)->exists();
            if ($exists) return true;
        }

        if ($phoneNumber) {
            $exists = PatientDemographic::where('phone_number', $phoneNumber)->exists();
            if ($exists) return true;
        }

        return false;
    }
}
