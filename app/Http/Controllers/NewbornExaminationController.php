<?php

namespace App\Http\Controllers;

use App\Models\Newborn;
use App\Models\NewbornExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewbornExaminationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Newborn $newborn)
    {
        $newborn->load('delivery.patient.demographic', 'examinations.recordedBy');

        return view('midwife.newborn-examination.index', compact('newborn'));
    }

    public function create(Newborn $newborn)
    {
        return view('midwife.newborn-examination.create', compact('newborn'));
    }

    public function store(Request $request, Newborn $newborn)
    {
        $validated = $request->validate([
            'examination_date_time' => 'required|date',
            'hours_after_birth' => 'required|integer|min:0|max:168',
            'temperature' => 'nullable|numeric|min:34|max:42',
            'heart_rate' => 'nullable|integer|min:80|max:200',
            'respiratory_rate' => 'nullable|integer|min:20|max:80',
            'weight' => 'nullable|numeric|min:500|max:6000',
            'length' => 'nullable|numeric|min:25|max:70',
            'head_circumference' => 'nullable|numeric|min:20|max:50',
            'chest_circumference' => 'nullable|numeric|min:20|max:50',
            'general_appearance' => 'nullable|string|max:500',
            'skin_examination' => 'nullable|string|max:500',
            'head_and_neck' => 'nullable|string|max:500',
            'eyes_examination' => 'nullable|string|max:500',
            'ear_examination' => 'nullable|string|max:500',
            'mouth_and_throat' => 'nullable|string|max:500',
            'heart_sounds' => 'nullable|string|max:500',
            'pulses' => 'nullable|string|max:500',
            'capillary_refill' => 'nullable|string|max:500',
            'chest_expansion' => 'nullable|string|max:500',
            'breath_sounds' => 'nullable|string|max:500',
            'nasal_breathing' => 'nullable|string|max:500',
            'abdomen_shape' => 'nullable|string|max:500',
            'umbilical_cord_check' => 'nullable|string|max:500',
            'hepatomegaly_splenomegaly' => 'nullable|string|max:500',
            'bowel_sounds' => 'nullable|string|max:500',
            'genitalia_examination' => 'nullable|string|max:500',
            'urinary_output' => 'nullable|string|max:500',
            'stool_output' => 'nullable|string|max:500',
            'reflex_assessment' => 'nullable|string|max:500',
            'muscle_tone' => 'nullable|string|max:500',
            'developmental_screening' => 'nullable|string|max:500',
            'extremities_examination' => 'nullable|string|max:500',
            'hip_examination' => 'nullable|string|max:500',
            'spine_examination' => 'nullable|string|max:500',
            'abnormal_findings' => 'nullable|string|max:1000',
            'congenital_anomalies' => 'nullable|string|max:1000',
            'jaundice_present' => 'nullable|boolean',
            'jaundice_level' => 'nullable|string|max:500',
            'jaundice_management' => 'nullable|string|max:500',
            'feeding_type' => 'nullable|string|max:500',
            'feeding_tolerance' => 'nullable|string|max:500',
            'feeding_challenges' => 'nullable|string|max:500',
            'clinical_summary' => 'nullable|string|max:2000',
            'exam_status' => 'required|in:normal,needs_follow_up,referral_needed',
            'follow_up_plans' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $validated['newborn_id'] = $newborn->id;
        $validated['recorded_by'] = Auth::id();

        $examination = NewbornExamination::create($validated);

        activity()
            ->performedOn($examination)
            ->withProperties(['action' => 'create'])
            ->log('Newborn examination record created');

        return redirect()->route('midwife.newborn-examination.show', $examination)
            ->with('success', 'Newborn examination record created successfully.');
    }

    public function show(NewbornExamination $newbornExamination)
    {
        $newbornExamination->load('newborn.delivery.patient.demographic', 'recordedBy');

        return view('midwife.newborn-examination.show', compact('newbornExamination'));
    }

    public function edit(NewbornExamination $newbornExamination)
    {
        return view('midwife.newborn-examination.edit', compact('newbornExamination'));
    }

    public function update(Request $request, NewbornExamination $newbornExamination)
    {
        $validated = $request->validate([
            'examination_date_time' => 'required|date',
            'hours_after_birth' => 'required|integer|min:0|max:168',
            'temperature' => 'nullable|numeric|min:34|max:42',
            'heart_rate' => 'nullable|integer|min:80|max:200',
            'respiratory_rate' => 'nullable|integer|min:20|max:80',
            'weight' => 'nullable|numeric|min:500|max:6000',
            'length' => 'nullable|numeric|min:25|max:70',
            'head_circumference' => 'nullable|numeric|min:20|max:50',
            'chest_circumference' => 'nullable|numeric|min:20|max:50',
            'general_appearance' => 'nullable|string|max:500',
            'skin_examination' => 'nullable|string|max:500',
            'head_and_neck' => 'nullable|string|max:500',
            'eyes_examination' => 'nullable|string|max:500',
            'ear_examination' => 'nullable|string|max:500',
            'mouth_and_throat' => 'nullable|string|max:500',
            'heart_sounds' => 'nullable|string|max:500',
            'pulses' => 'nullable|string|max:500',
            'capillary_refill' => 'nullable|string|max:500',
            'chest_expansion' => 'nullable|string|max:500',
            'breath_sounds' => 'nullable|string|max:500',
            'nasal_breathing' => 'nullable|string|max:500',
            'abdomen_shape' => 'nullable|string|max:500',
            'umbilical_cord_check' => 'nullable|string|max:500',
            'hepatomegaly_splenomegaly' => 'nullable|string|max:500',
            'bowel_sounds' => 'nullable|string|max:500',
            'genitalia_examination' => 'nullable|string|max:500',
            'urinary_output' => 'nullable|string|max:500',
            'stool_output' => 'nullable|string|max:500',
            'reflex_assessment' => 'nullable|string|max:500',
            'muscle_tone' => 'nullable|string|max:500',
            'developmental_screening' => 'nullable|string|max:500',
            'extremities_examination' => 'nullable|string|max:500',
            'hip_examination' => 'nullable|string|max:500',
            'spine_examination' => 'nullable|string|max:500',
            'abnormal_findings' => 'nullable|string|max:1000',
            'congenital_anomalies' => 'nullable|string|max:1000',
            'jaundice_present' => 'nullable|boolean',
            'jaundice_level' => 'nullable|string|max:500',
            'jaundice_management' => 'nullable|string|max:500',
            'feeding_type' => 'nullable|string|max:500',
            'feeding_tolerance' => 'nullable|string|max:500',
            'feeding_challenges' => 'nullable|string|max:500',
            'clinical_summary' => 'nullable|string|max:2000',
            'exam_status' => 'required|in:normal,needs_follow_up,referral_needed',
            'follow_up_plans' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $newbornExamination->update($validated);

        activity()
            ->performedOn($newbornExamination)
            ->withProperties(['action' => 'update'])
            ->log('Newborn examination record updated');

        return redirect()->route('midwife.newborn-examination.show', $newbornExamination)
            ->with('success', 'Newborn examination record updated successfully.');
    }

    public function destroy(NewbornExamination $newbornExamination)
    {
        $newbornExamination->delete();

        activity()
            ->performedOn($newbornExamination)
            ->withProperties(['action' => 'delete'])
            ->log('Newborn examination record deleted');

        return redirect()->route('midwife.newborn-examination.index', $newbornExamination->newborn)
            ->with('success', 'Newborn examination record deleted successfully.');
    }
}