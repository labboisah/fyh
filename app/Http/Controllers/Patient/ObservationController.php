<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class ObservationController extends Controller
{
    public function record(Patient $patient){
        return view('patient.observation.record',compact('patient'));
    }

    public function register(Request $request, Patient $patient) {
        $request->validate([
            "temperature" => "required",
            "mate_pulse" => "required",
            "blood_pressure_systolic" => "required",
            "blood_pressure_diastolic" => "required",
            "respiratory_rate" => "required",
            "drop_rate" => "required",
            "contraction" => "required",
            "fits" => "required",
            "date" => "required",
            "time" => "required",
            "notes" => "required"
        ]);

        $patient->currentVisit()->observations()->create([
            "temperature" => $request->temperature,
            "mate_pulse" => $request->mate_pulse,
            "blood_pressure" => $request->blood_pressure_systolic.'/'.$request->blood_pressure_diastolic,
            "respiration" => $request->respiratory_rate,
            "drop_rate" => $request->drop_rate,
            "constraction" => $request->constraction,
            "fits" => $request->fits,
            "date" => $request->date,
            "time" => $request->time,
            "remark" => $request->remark,
            'recorded_by'=>auth()->user()->id
        ]);

        return redirect()->route('patient.show',$patient)->with('success', 'Observation Recorded Successfully');
    }
}
