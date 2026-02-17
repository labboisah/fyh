<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investigation;
use App\Models\Bed;

class AjaxController extends Controller
{
    function getInvestigations($typeId) {
       
        return response()->json(
            Investigation::where('investigation_type_id', $typeId)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
       
    }

    function getWardBeds($wardId) {
       
        return response()->json(
            Bed::where('ward_id', $wardId)->where('status', 'vacant')
                ->orderBy('bed_no')
                ->get(['id', 'bed_no', 'status'])
        );
       
    }
}
