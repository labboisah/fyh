<?php

namespace App\Http\Controllers;

use App\Models\Labour;
use App\Models\Patient;
use App\Models\PatientVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabourController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of patients with labour records
     */
    public function index()
    {
        // Only show female patients aged 13-55 (reproductive age)
        $patients = Patient::whereHas('demographic', function ($query) {
            $query->where('gender', 'Female')
                  ->whereRaw('YEAR(CURDATE()) - YEAR(date_of_birth) - (DATE_FORMAT(CURDATE(), \'%m%d\') < DATE_FORMAT(date_of_birth, \'%m%d\')) BETWEEN 13 AND 55');
        })->with(['demographic', 'labours'])->get();

        return view('midwife.labour.index', compact('patients'));
    }

    /**
     * Show the form for creating a new labour record
     */
    public function create(Patient $patient)
    {
        // Validate patient is female and reproductive age
        $age = now()->diffInYears($patient->demographic->date_of_birth);
        
        if ($patient->demographic->gender !== 'Female' || $age < 13 || $age > 55) {
            return redirect()->route('midwife.labour.index')
                           ->with('error', 'Labour records can only be created for female patients aged 13-55 years.');
        }

        // Get or create current visit
        $currentVisit = $patient->visits()
                               ->where('visit_date', now()->format('Y-m-d'))
                               ->first();

        if (!$currentVisit) {
            $currentVisit = PatientVisit::create([
                'patient_id' => $patient->id,
                'visit_date' => now(),
                'visit_type' => 'Labour',
                'chief_complaint' => 'Labour admission',
                'diagnosis' => 'Labour',
            ]);
        }

        return view('midwife.labour.create', compact('patient', 'currentVisit'));
    }

    /**
     * Store a newly created labour record in storage
     */
    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'date_of_admission' => 'required|date',
            'time_of_admission' => 'nullable|date_format:H:i',
            'type_of_labour' => 'in:spontaneous,induced,augmented',
            'induction_reason' => 'nullable|string|max:500',
            'stage_at_admission' => 'in:first,second,third,fourth',
            
            // Cervical findings
            'cervical_dilation' => 'nullable|integer|min:0|max:10',
            'cervical_effacement' => 'nullable|integer|min:0|max:100',
            'cervical_consistency' => 'in:firm,medium,soft',
            'cervical_position' => 'in:posterior,middle,anterior',
            'cervical_application' => 'in:unpadded,padded',
            
            // Uterine contractions
            'contraction_frequency' => 'nullable|integer|min:0|max:10',
            'contraction_duration' => 'nullable|integer|min:0|max:120',
            'contraction_intensity' => 'in:mild,moderate,strong',
            
            // Fetal status
            'fetal_position' => 'in:cephalic,breech,oblique,transverse',
            'fetal_descent' => 'nullable|integer|min:-5|max:5',
            'fetal_heart_rate' => 'nullable|integer|min:100|max:160',
            'fetal_movements' => 'nullable|string|max:500',
            'meconium_staining' => 'boolean',
            
            // Maternal vital signs
            'systolic_bp' => 'nullable|integer|min:60|max:250',
            'diastolic_bp' => 'nullable|integer|min:40|max:150',
            'pulse_rate' => 'nullable|integer|min:40|max:150',
            'temperature' => 'nullable|numeric|min:34|max:42',
            'respiratory_rate' => 'nullable|integer|min:10|max:40',
            
            // Progress monitoring
            'mode_of_delivery' => 'in:vaginal,assisted_vaginal,caesarean',
            'assisted_delivery_type' => 'nullable|in:forceps,vacuum',
            'indication_for_operative' => 'nullable|string|max:500',
            
            // Complications
            'maternal_complications' => 'nullable|string|max:1000',
            'fetal_complications' => 'nullable|string|max:1000',
            
            // Management
            'management_given' => 'nullable|string|max:1000',
            'analgesia_given' => 'nullable|string|max:500',
            'augmentation_method' => 'nullable|string|max:500',
            'episiotomy_performed' => 'boolean',
            'episiotomy_type' => 'nullable|in:mediolateral,midline',
            'perineal_tear' => 'nullable|in:none,first_degree,second_degree,third_degree,fourth_degree',
            
            // Clinical notes
            'clinical_notes' => 'nullable|string|max:2000',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['recorded_by'] = Auth::id();

        $labour = Labour::create($validated);

        activity()
            ->performedOn($labour)
            ->withProperties(['action' => 'create'])
            ->log('Labour record created');

        return redirect()->route('midwife.labour.show', $labour)
                       ->with('success', 'Labour record created successfully.');
    }

    /**
     * Display the specified labour record
     */
    public function show(Labour $labour)
    {
        $labour->load('patient.demographic', 'recordedBy');
        return view('midwife.labour.show', compact('labour'));
    }

    /**
     * Show the form for editing the specified labour record
     */
    public function edit(Labour $labour)
    {
        $labour->load('patient.demographic');
        $patient = $labour->patient;
        
        return view('midwife.labour.edit', compact('labour', 'patient'));
    }

    /**
     * Update the specified labour record in storage
     */
    public function update(Request $request, Labour $labour)
    {
        $validated = $request->validate([
            'date_of_admission' => 'required|date',
            'time_of_admission' => 'nullable|date_format:H:i',
            'type_of_labour' => 'in:spontaneous,induced,augmented',
            'induction_reason' => 'nullable|string|max:500',
            'stage_at_admission' => 'in:first,second,third,fourth',
            
            'cervical_dilation' => 'nullable|integer|min:0|max:10',
            'cervical_effacement' => 'nullable|integer|min:0|max:100',
            'cervical_consistency' => 'in:firm,medium,soft',
            'cervical_position' => 'in:posterior,middle,anterior',
            'cervical_application' => 'in:unpadded,padded',
            
            'contraction_frequency' => 'nullable|integer|min:0|max:10',
            'contraction_duration' => 'nullable|integer|min:0|max:120',
            'contraction_intensity' => 'in:mild,moderate,strong',
            
            'fetal_position' => 'in:cephalic,breech,oblique,transverse',
            'fetal_descent' => 'nullable|integer|min:-5|max:5',
            'fetal_heart_rate' => 'nullable|integer|min:100|max:160',
            'fetal_movements' => 'nullable|string|max:500',
            'meconium_staining' => 'boolean',
            
            'systolic_bp' => 'nullable|integer|min:60|max:250',
            'diastolic_bp' => 'nullable|integer|min:40|max:150',
            'pulse_rate' => 'nullable|integer|min:40|max:150',
            'temperature' => 'nullable|numeric|min:34|max:42',
            'respiratory_rate' => 'nullable|integer|min:10|max:40',
            
            'mode_of_delivery' => 'in:vaginal,assisted_vaginal,caesarean',
            'assisted_delivery_type' => 'nullable|in:forceps,vacuum',
            'indication_for_operative' => 'nullable|string|max:500',
            
            'maternal_complications' => 'nullable|string|max:1000',
            'fetal_complications' => 'nullable|string|max:1000',
            
            'management_given' => 'nullable|string|max:1000',
            'analgesia_given' => 'nullable|string|max:500',
            'augmentation_method' => 'nullable|string|max:500',
            'episiotomy_performed' => 'boolean',
            'episiotomy_type' => 'nullable|in:mediolateral,midline',
            'perineal_tear' => 'nullable|in:none,first_degree,second_degree,third_degree,fourth_degree',
            
            'clinical_notes' => 'nullable|string|max:2000',
        ]);

        $labour->update($validated);

        activity()
            ->performedOn($labour)
            ->withProperties(['action' => 'update'])
            ->log('Labour record updated');

        return redirect()->route('midwife.labour.show', $labour)
                       ->with('success', 'Labour record updated successfully.');
    }

    /**
     * Remove the specified labour record from storage (soft delete)
     */
    public function destroy(Labour $labour)
    {
        $labourId = $labour->id;
        
        $labour->delete();

        activity()
            ->performedOn($labour)
            ->withProperties(['action' => 'delete'])
            ->log('Labour record deleted');

        return redirect()->route('midwife.labour.index')
                       ->with('success', 'Labour record deleted successfully.');
    }

    /**
     * Display all labour records for a specific patient
     */
    public function patientRecords(Patient $patient)
    {
        // Validate patient is female and reproductive age
        $age = now()->diffInYears($patient->demographic->date_of_birth);
        
        if ($patient->demographic->gender !== 'Female' || $age < 13 || $age > 55) {
            return redirect()->route('midwife.labour.index')
                           ->with('error', 'Labour records are only available for female patients aged 13-55 years.');
        }

        $labours = $patient->labours()
                          ->orderBy('date_of_admission', 'desc')
                          ->get();

        return view('midwife.labour.patient-records', compact('patient', 'labours'));
    }
}
