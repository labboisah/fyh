<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $guarded = [];

    public function medicineType() {
        return $this->belongsTo(MedicineType::class);
    }

    public function batches() {
        return $this->hasMany(MedicineBatch::class);
    }

}
