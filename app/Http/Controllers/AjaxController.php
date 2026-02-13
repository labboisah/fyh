<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investigation;
class AjaxController extends Controller
{
    function getInvestigations($typeId) {
       
        return response()->json(
            Investigation::where('investigation_type_id', $typeId)
                ->orderBy('name')
                ->get(['id', 'name'])
        );
       
    }
}
