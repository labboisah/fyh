<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $guarded = [];

     protected $casts = [
        'amount' => 'decimal:2',
        'recorded_at' => 'datetime',
    ];

    public function recordedBy() {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function category() {
        return $this->belongsTo(RevenueCategory::class, 'revenue_category_id');
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
