<?php

namespace App\Models;

use App\Models\Traits\Syncable;
use Illuminate\Database\Eloquent\Model;

class InvestigationRequest extends Model
{
    use Syncable;
    protected $guarded = [];

    protected $casts = [
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patientVisit()
    {
        return $this->belongsTo(PatientVisit::class);
    }

    public function investigation()
    {
        return $this->belongsTo(Investigation::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    public function investigationResults()
    {
        return $this->hasMany(InvestigationResult::class);
    }

    public function bill() {
        return $this->hasOne(Bill::class);
    }

    public function walkinPatient() {
        return $this->belongsTo(WalkinPatient::class, 'walkin_id');
    }

    public function paymentStatus() {
        if($this->bill)
            return $this->bill->status;
        else
            return 'pending';
    }

    public static function updateLabNumber($requestId, $investigationId)
    {
        $year = substr(date('Y'), 2, 2);
        $type = Investigation::find($investigationId)->investigationType;
        $count = count($type->department->investigationRequests()) + 1;
        $number = strtoupper(substr($type->name, 0, 3)) . $year . str_pad($count, 4, '0', STR_PAD_LEFT);
        $request = InvestigationRequest::find($requestId);
        $request->update(['lab_no'=>$number]);
    }
}
