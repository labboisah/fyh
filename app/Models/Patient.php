<?php

namespace App\Models;

use App\Models\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes, Syncable;

    protected $guarded = [];

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

    public function fileType() : Returntype {
        return $this->belongsTo(FileType::class);
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

    /**
     * Get all antenatal care records for this patient
     */
    public function antenatalCares()
    {
        return $this->hasMany(AntenatalCare::class);
    }

    /**
     * Get all labour records for this patient
     */
    public function labours()
    {
        return $this->hasMany(Labour::class);
    }

    /**
     * Get all deliveries for this patient
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    /**
     * Get all newborns from this patient (as mother)
     */
    public function newborns()
    {
        return $this->hasMany(Newborn::class);
    }

    /**
     * Get all postnatal examinations for this patient
     */
    public function postnatalExaminations()
    {
        return $this->hasMany(PostnatalExamination::class);
    }

    /**
     * Get all child follow-ups related to this patient (as mother)
     */
    public function childFollowUps()
    {
        return $this->hasMany(ChildFollowUp::class);
    }

    /**
     * Get the latest antenatal care record
     */
    public function latestAntenatalCare()
    {
        return $this->antenatalCares()->latest('created_at')->first();
    }

    /**
     * Get the latest labour
     */
    public function latestLabour()
    {
        return $this->labours()->latest('labour_onset_time')->first();
    }

    /**
     * Get the latest delivery
     */
    public function latestDelivery()
    {
        return $this->deliveries()->latest('delivery_date_time')->first();
    }

   
    public function recordFirstVisit() {

        $visit = $this->patientVisits()->create([
            'visit_date'=>date('d M, Y'),
            'visit_type'=>'Consultation',
            'created_by'=>auth()->user()->id
        ]);

        $visit->generateFileOpeningBill();

        

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
