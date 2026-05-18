<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestigationRequest;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Bill;

class InvestigationController extends Controller
{
    public function create(Patient $patient)
    {
        return view('patient.investigation.create', compact('patient'));
    }

    public function store(Request $request, $patientId)
    {
        $request->validate([
            'investigation_type' => 'required|exists:investigation_types,id',
            'investigation' => 'required|exists:investigations,id',
            'clinical_diagnoses' => 'required|string',

        ]);

        $patient = Patient::findOrFail($patientId);

        // Create the investigation request $table->unsignedBigInteger('patient_visit_id');

        $investigationRequest = $patient->currentVisit()->investigationRequests()->firstOrCreate([
            'investigation_id' => $request->input('investigation'),
            'requested_by' => auth()->id(),
            'clinical_diagnoses' => $request->input('clinical_diagnoses'),
            'requested_at' => now(),
            'specimen' => $request->input('specimen'),
            
        ]);
        $investigationRequest->updateLabNumber($investigationRequest->id,$request->investigation);

        // create bill for this investigation
        $bill = $investigationRequest->bill()->create([
            'patient_visit_id'=>$patient->currentVisit()->id,
            'amount' => $investigationRequest->investigation->price ?? 0,
            'service_description' => 'Investigation: ' . $investigationRequest->investigation->name,
            'status' => 'pending',
            'issued_by' => auth()->id(),
            'issued_date' => now(),
            'bill_number' =>  Bill::generateBillNumber(),
            'due_date'=>now()->addDays(2)->toDateString(),
            'department_id'=> $investigationRequest->investigation->investigationType->department->id,
        ]);

        // Log activity
        $patient->currentVisit()->visitActivities()->create([
            'activity' => "Investigation request created for {$investigationRequest->investigation->name}",
            'recorded_by' => auth()->id(),
        ]);

        return redirect()->route('patient.show', $patient)->with('success', 'Investigation request created successfully.');
    }

    public function show($investigationRequestId)
    {
        $investigationRequest = \App\Models\InvestigationRequest::findOrFail($investigationRequestId);

        return view('patient.investigation.show', compact('investigationRequest'));
    }
}
