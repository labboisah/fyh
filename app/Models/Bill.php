<?php

namespace App\Models;

use App\Models\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bill extends Model
{
    use SoftDeletes, Syncable;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'issued_date' => 'datetime',
        'due_date' => 'datetime',
    ];

    /**
     * Get the patient associated with the bill
     */
    public function patientVisit()
    {
        return $this->belongsTo(PatientVisit::class);
    }

    /**
     * Get the walk-in patient associated with the bill
     */
    public function walkinPatient()
    {
        return $this->belongsTo(WalkinPatient::class, 'walkin_id');
    }

    /**
     * Get the user who issued the bill
     */
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function investigationRequests()
    {
        return $this->hasMany(InvestigationRequest::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
    /**
     * Get services included in this bill
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'bill_services', 'bill_id', 'service_id')
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }

    public function investigations()
    {
        return $this->belongsToMany(Investigation::class, 'bill_investigations', 'bill_id', 'investigation_id')
            ->withPivot('quantity', 'unit_price', 'subtotal')
            ->withTimestamps();
    }

    public function syncRelations(): array
    {
        return [
            'patientVisit' => 'patient_visit_sync_uuid',
            'issuedBy' => 'user_sync_uuid',
        ];
    }

    public function syncDependencies(): array
    {
        return [
            'patient_visit_sync_uuid' => PatientVisit::class,
            'user_sync_uuid' => User::class,
        ];
    }

    /**
     * Get all payments for this bill
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get bill services for this bill
     */
    public function billServices()
    {
        return $this->hasMany(BillService::class);
    }

    public function getDiscountedAmount(float $amount): float
    {
        if ($this->discount <= 0) {
            return round($amount, 2);
        }

        return round($amount * (1 - ($this->discount / 100)), 2);
    }

    public function refreshRequestPaymentStatuses()
    {
        $totalPaid = $this->totalPaid();

        if ($this->due_amount == 0 || $totalPaid >= $this->due_amount) {
            $this->serviceRequests()->update(['payment_status' => 'paid']);
            $this->investigationRequests()->update(['payment_status' => 'paid']);
            return;
        }

        $remaining = $totalPaid;

        foreach ($this->serviceRequests as $serviceRequest) {
            $discountedAmount = $this->getDiscountedAmount($serviceRequest->service->price);
            if ($remaining >= $discountedAmount) {
                $serviceRequest->update(['payment_status' => 'paid']);
                $remaining -= $discountedAmount;
            } elseif ($remaining > 0) {
                $serviceRequest->update(['payment_status' => 'partial']);
                $remaining = 0;
            } else {
                $serviceRequest->update(['payment_status' => 'pending']);
            }
        }

        foreach ($this->investigationRequests as $investigationRequest) {
            $discountedAmount = $this->getDiscountedAmount($investigationRequest->investigation->price);
            if ($remaining >= $discountedAmount) {
                $investigationRequest->update(['payment_status' => 'paid']);
                $remaining -= $discountedAmount;
            } elseif ($remaining > 0) {
                $investigationRequest->update(['payment_status' => 'partial']);
                $remaining = 0;
            } else {
                $investigationRequest->update(['payment_status' => 'pending']);
            }
        }
    }

    /**
     * Get total amount paid
     */
    public function totalPaid()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    /**
     * Get balance remaining
     */
    public function getBalanceAttribute()
    {
        return $this->due_amount - $this->totalPaid();
    }

    /**
     * Generate unique bill number
     */
    public static function generateBillNumber()
    {
        $year = substr(date('Y'), 2, 2);
        $prefix = "BL{$year}";

        $lastNumber = self::where('bill_number', 'like', "{$prefix}%")
            ->select(DB::raw('MAX(CAST(SUBSTRING(bill_number, 5) AS UNSIGNED)) as max_number'))
            ->value('max_number');

        $nextNumber = $lastNumber ? (int) $lastNumber + 1 : 1;
        $newNumber = str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        $bill_no = "{$prefix}{$newNumber}";

        while (self::where('bill_number', $bill_no)->exists()) {
            $nextNumber++;
            $bill_no = "{$prefix}" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
        }

        return $bill_no;
    }

    public function patientName() {
        if($this->patientVisit) {
            return $this->patientVisit->patient->name();
        } elseif($this->walkinPatient) {
            return $this->walkinPatient->name;
        } else {
            return 'N/A';
        }
    }
}
