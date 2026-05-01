<?php

namespace App\Models;

use App\Models\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bill extends Model
{
    use SoftDeletes, Syncable;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
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
     * Get the user who issued the bill
     */
    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
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
        return $this->amount - $this->totalPaid();
    }

    /**
     * Generate unique bill number
     */
    public static function generateBillNumber()
    {
        $year = substr(date('Y'),2, 2);
        $lastBill = self::where('bill_number', 'like', "BL{$year}%")
            ->orderBy('bill_number', 'desc')
            ->first();
        
        if ($lastBill) {
            $lastNumber = (int) substr($lastBill->bill_number, -5);
            $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '00001';
        }
        
        return "BL{$year}{$newNumber}";
    }
}
