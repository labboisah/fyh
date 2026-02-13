<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationResult extends Model
{
    protected $fillable = [
        'investigation_request_id',
        'parameter_id',
        'value',
    ];

    public function investigationRequest()
    {
        return $this->belongsTo(InvestigationRequest::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class, 'parameter_id');
    }
}
