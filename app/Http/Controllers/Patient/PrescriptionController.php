<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Medicine;

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
            "dosage" => "required",
            "period" => "required",
            "duration" => "required",
            "route_id" => "required"
        ]);

        if($request->other_medicine){
            $medicine = Medicine::create([
                'name'=>$request->other_medicine,
                'medicine_type_id'=>$request->medicine_type_id
                ]);
        }else{
            $medicine = Medicine::find($request->medicine_id);
        }

        if(!$medicine){
            return back()->withWarning('Pls Prescribe Medicine');
        }
        
        $prescription = $patient->currentVisit()->prescriptions()->create([
            'prescribe_by'=>auth()->user()->id
        ]);

        $prescription->prescriptionItems()->firstOrCreate([
            'medicine_id'=>$medicine->id,
            'route_id'=>$request->route_id,
            'dosage'=>$request->dosage,
            'period'=>$request->period,
            'duration'=>$request->duration,
            ]);
            // Log activity
            $patient->currentVisit()->visitActivities()->create([
                'activity' => "Prescription created for medicine: {$medicine->name}",
                'recorded_by' => auth()->id(),
            ]);
        return redirect()->route('patient.prescription.show',$prescription)->with('success', 'Prescription registered but you can prescribe more and submit to pharmacy');    
    }

    public function addMedicine(Request $request, Prescription $prescription) {
        $request->validate([
            "dosage" => "required",
            "period" => "required",
            "duration" => "required",
            'route_id'=>'required'
        ]);

        if($request->other_medicine){
            $medicine = Medicine::create([
                'name'=>$request->other_medicine,
                'medicine_type_id'=>$request->medicine_type_id
                ]);
        }else{
            $medicine = Medicine::find($request->medicine_id);
        }

        if(!$medicine){
            return back()->withWarning('Pls Prescribe Medicine');
        }
       
        $prescription->prescriptionItems()->firstOrCreate([
            'medicine_id'=>$medicine->id,
            'dosage'=>$request->dosage,
            'period'=>$request->period,
            'duration'=>$request->duration,
            'route_id'=>$request->route_id,
            ]);
        return redirect()->route('patient.prescription.show',$prescription)->with('success', 'Medicine added to the prescription registered but you can prescribe more and submit to pharmacy');    
    }
}
