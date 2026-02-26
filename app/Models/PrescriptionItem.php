<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $guarded = [];

    public function prescription() {
        return $this->belongsTo(Prescription::class);
    }

    public function medicine() {
        return $this->belongsTo(Medicine::class);
    }

    public function drugCharts() {
        return $this->hasMany(DrugChart::class);
    }

    public function prescribedBy() {
        return $this->belongsTo(User::class, 'prescribe_by');
    }
}
