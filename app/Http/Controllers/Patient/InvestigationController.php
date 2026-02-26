<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        // create bill for this investigation
        $bill = $investigationRequest->bills()->create([
            'amount' => $investigationRequest->investigation->price ?? 0,
            'service_description' => 'Investigation: ' . $investigationRequest->investigation->name,
            'status' => 'pending',
            'issued_by' => auth()->id(),
            'issued_date' => now(),
            'bill_number' =>  Bill::generateBillNumber(),
        ]);

        $service = Service::firstOrCreate([
            'code' => $investigationRequest->investigation->code ?? 'INV-' . $investigationRequest->investigation_id,
            'name' => $investigationRequest->investigation->name,
            'price' => $investigationRequest->investigation->price ?? 0,
            'category' => $investigationRequest->investigation->investigationType->name ?? 'Investigation',
         ], 
         [
            'description' => 'Investigation service for billing',
        ]);
        
        $bill->billServices()->create([
            'quantity' => 1,
            'unit_price' => $service->price,
            'subtotal' => $service->price,
            'service_id' => $service->id,
        ]);

        return redirect()->route('patient.show', $patient)->with('success', 'Investigation request created successfully.');
    }

    public function show($investigationRequestId)
    {
        $investigationRequest = \App\Models\InvestigationRequest::findOrFail($investigationRequestId);

        return view('patient.investigation.show', compact('investigationRequest'));
    }
}
