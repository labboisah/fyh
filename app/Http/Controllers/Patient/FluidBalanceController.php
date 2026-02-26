<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class FluidBalanceController extends Controller
{
    public function record(Patient $patient) {
        return view('patient.fluidbalance.record',compact('patient'));
    }

    public function register(Request $request, Patient $patient) {
        $admission = $patient->currentVisit()->confirmAdmission();
        if($admission){
            $admission->fluidBalances()->create([
                "date" => $request->date,
                "time" => $request->time,
                "type_in" => $request->type_in,
                "tube_in" => $request->tube_in,
                "oral" => $request->oral,
                "iv" => $request->IV,
                "type_out" => $request->type_out,
                "tube_out" => $request->tube_out,
                "urine" => $request->urine,
                "faces" => $request->faces,
                'recorded_by'=>auth()->user()->id
            ]);
        }else{
            return redirect()->route('patient.show',$patient)->with('error', 'No patient Admission record found');

        }
        

        return redirect()->route('patient.show',$patient)->with('success', 'Fluid Balance Chart Recorded Successfully');

    }
}
