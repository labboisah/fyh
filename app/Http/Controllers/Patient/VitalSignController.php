<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VitalSign;
use App\Models\Patient;
use Auth;

class VitalSignController extends Controller
{
     public function create(Patient $patient)
    {
        return view('patient.vital_signs.create', compact('patient'));
    }

    /**
     * Store vital signs recording
     */
    public function register(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'body_temperature' => 'required|numeric|between:35,42',
            'blood_pressure_systolic' => 'required|numeric|between:50,250',
            'blood_pressure_diastolic' => 'required|numeric|between:30,150',
            'heart_rate' => 'required|numeric|between:30,200',
            'respiratory_rate' => 'required|numeric|between:10,50',
            'oxygen_saturation' => 'required|numeric|between:50,100',
            'blood_glucose' => 'nullable|numeric|min:0',
            'weight' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'recorded_date' => 'required|date',
        ]);

        $validated['patient_visit_id'] = $patient->currentVisit()->id;
        $validated['recorded_by'] = Auth::id();

        VitalSign::create($validated);

        return redirect()->route('patient.show', $patient)
            ->with('success', 'Vital signs recorded successfully.');
    }

    /**
     * Show vital signs history for patient
     */
    public function history(Patient $patient)
    {
        $vitalSigns = $patient->vitalSigns()
            ->latest('recorded_date')
            ->paginate(15);

        return view('vital_signs.history', compact('patient', 'vitalSigns'));
    }
}
