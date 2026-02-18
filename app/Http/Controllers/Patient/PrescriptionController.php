<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Patient;

class PrescriptionController extends Controller
{
    public function create(Patient $patient) {
        return view('patient.prescription.create', compact('patient'));
    }

    public function show(Prescription $prescription) {
        return view('patient.prescription.show', compact('prescription'));
    }

    public function store(Request $request, Patient $patient) {
        $request->validate([
            "medicine_id" => "required",
            "dosage" => "required",
            "period" => "required",
            "duration" => "required"
        ]);
        
        $prescription = $patient->currentVisit()->prescriptions()->create([
            'prescribe_by'=>auth()->user()->id
        ]);
        $prescription->prescriptionItems()->firstOrCreate([
            'medicine_id'=>$request->medicine_id,
            'dosage'=>$request->dosage,
            'period'=>$request->period,
            'duration'=>$request->duration,
            ]);
        return redirect()->route('patient.prescription.show',$prescription)->with('success', 'Prescription registered but you can prescribe more and submit to pharmacy');    
    }

    public function addMedicine(Request $request, Prescription $prescription) {
        $request->validate([
            "medicine_id" => "required",
            "dosage" => "required",
            "period" => "required",
            "duration" => "required"
        ]);
        
       
        $prescription->prescriptionItems()->firstOrCreate([
            'medicine_id'=>$request->medicine_id,
            'dosage'=>$request->dosage,
            'period'=>$request->period,
            'duration'=>$request->duration,
            ]);
        return redirect()->route('patient.prescription.show',$prescription)->with('success', 'Medicine added to the prescription registered but you can prescribe more and submit to pharmacy');    
    }
}
