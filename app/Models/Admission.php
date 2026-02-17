<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $guarded = [];

    public function admittedBy()
    {
        return $this->belongsTo(User::class, 'admitted_by');
    }
}
