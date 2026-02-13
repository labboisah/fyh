<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class InvestigationController extends Controller
{
    public function create(Patient $patient)
    {
        return view('nurse.patient.investigation.create', compact('patient'));
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

        $investigationRequest = $patient->currentVisit()->investigationRequests()->create([
            'investigation_id' => $request->input('investigation'),
            'requested_by' => auth()->id(),
            'clinical_diagnoses' => $request->input('clinical_diagnoses'),
            'requested_at' => now(),
            'specimen' => $request->input('specimen'),
        ]);

        return redirect()->route('nurse.patients.show', $patient)->with('success', 'Investigation request created successfully.');
    }
}
