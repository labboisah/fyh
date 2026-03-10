<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestigationType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function investigations()
    {
        return $this->hasMany(Investigation::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
