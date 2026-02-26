<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DrugChart extends Model
{
    protected $guarded = [];

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

    public function prescriptionItem() {
        return $this->belongsTo(PrescriptionItem::class);
    }

    public function dispensedBy() {
        return $this->belongsTo(User::class, 'dispensed_by');
    }
}
