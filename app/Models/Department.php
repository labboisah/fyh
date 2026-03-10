<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded = [];

    public function investigationTypes() {
        return $this->hasMany(InvestigationType::class);
    }

    public function investigationRequests() {
        $requests = [];
        foreach($this->investigationTypes as $type){
            foreach($type->investigations as $investigation){
                foreach($investigation->investigationRequests as $request){
                    $requests[] = $request;
                }
            }
        }
        return $requests;
    }

}
