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

    public function patientVisits() {
        return $this->hasMany(PatientVisit::class);
    }

   
    public function recordFirstVisit() {

        $visit = $this->patientVisits()->create([
            'visit_date'=>date('d M, Y'),
            'visit_type'=>'Consultation',
            'created_by'=>auth()->user()->id
        ]);

        $visit->generateFileOpeningBill();

        $service = Service::where('name','General Consultation')->first();

        $visit->generateServiceBillOf($service);

    }

    // currnt visit
    public function currentVisit() { 
        return $this->patientVisits()->latest('created_at')->first(); 
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
     * Get payment summary for this patient
     */
    public function payment()
    {
        $total = 0;
        $count = 0;
        $paid = 0;
        $reversed = 0;

        foreach ($this->patientVisits as $visit) {
            foreach ($visit->bills as $bill) {
                foreach ($bill->payments as $payment) {
                    if ($payment->status === 'completed') {
                        $paid += $payment->amount;
                        $count++;
                    }
                }
                $total += $bill->amount;
            }
        }

        return [
            'total' => $total,
            'count' => $count,
            'paid' => $paid,
            'pending' => $total - $paid,
            'outstanding' => $total - $paid,
            'revenue' => $paid,
            'reversed' => $reversed // Placeholder for any reversed payments logic
        ];
    }

     public function bills()
    {
        $amount = 0;
        $paid = 0;
        $pending = 0;
        $overdue = 0;
        $partial = 0;
        

        foreach ($this->patientVisits as $visit) {
            foreach ($visit->bills as $bill) {
                $amount+=$bill->amount;

                if($bill->status == 'paid'){
                    $paid += $bill->amount;
                }elseif($bill->status == 'pending'){
                    $pending += $bill->amount;
                    if(time() > strtotime($bill->due_date)){
                        $overdue+=$bill->getBalanceAttribute();
                    }
                }elseif($bill->status == 'partial'){
                    $partial += $bill->totalPaid();
                    if(time() > strtotime($bill->due_date)){
                        $overdue+=$bill->getBalanceAttribute();
                    }
                }

            }
        }

        return [
            'amount' => $amount,
            'paid' => $paid,
            'partial' => $partial,
            'pending' => $pending,
            'overdue' => $overdue,
            
        ];
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
