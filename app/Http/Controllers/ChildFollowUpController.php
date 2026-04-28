<?php

namespace App\Http\Controllers;

use App\Models\ChildFollowUp;
use App\Models\Newborn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChildFollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Newborn $newborn)
    {
        $newborn->load('patient.demographic', 'childFollowUps.recordedBy');

        return view('midwife.child-follow-up.index', compact('newborn'));
    }

    public function create(Newborn $newborn)
    {
        return view('midwife.child-follow-up.create', compact('newborn'));
    }

    public function store(Request $request, Newborn $newborn)
    {
        $validated = $request->validate([
            'follow_up_date_time' => 'required|date',
            'days_of_life' => 'required|integer|min=1|max=365',
            'follow_up_period' => 'required|in:day_3,day_7,day_10,day_14,6weeks,3months,6months,year1',
            'location' => 'required|in:home,clinic,hospital,other',
            'location_details' => 'nullable|string|max:255',
            'feeding_type' => 'required|in:breastfeeding,bottle_feeding,mixed,other',
            'how_baby_is_feeding' => 'nullable|string|max:500',
            'mother_observations' => 'nullable|string|max:1000',
            'temperature' => 'nullable|numeric|min=34|max:42',
            'heart_rate' => 'nullable|integer|min:80|max:200',
            'respiratory_rate' => 'nullable|integer|min:20|max:80',
            'weight' => 'nullable|numeric|min:0|max:20',
            'length' => 'nullable|numeric|min:20|max:80',
            'head_circumference' => 'nullable|numeric|min:20|max:50',
            'weight_percentile' => 'nullable|string|max:50',
            'weight_change_since_birth' => 'nullable|string|max:255',
            'weight_gain_rate' => 'nullable|string|max:255',
            'weight_assessment' => 'nullable|in:adequate,inadequate,excessive',
            'general_appearance' => 'nullable|string|max:500',
            'activity_level' => 'nullable|in:active,lethargic,normal',
            'alertness' => 'nullable|in:alert,drowsy,unresponsive',
            'skin_examination' => 'nullable|string|max:500',
            'umbilical_cord_status' => 'nullable|string|max:255',
            'umbilical_discharge' => 'nullable|string|max:255',
            'signs_of_infection' => 'nullable|string|max:500',
            'jaundice_present' => 'nullable|boolean',
            'jaundice_level' => 'nullable|in:mild,moderate,high,severe',
            'jaundice_management' => 'nullable|string|max:500',
            'breast_examination' => 'nullable|string|max:500',
            'latching_quality' => 'nullable|in:good,fair,poor',
            'suckling_pattern' => 'nullable|in:good,fair,poor',
            'milk_transfer' => 'nullable|in:good,fair,poor',
            'bottle_feeding_if_applicable' => 'nullable|string|max:500',
            'feeding_frequency' => 'nullable|string|max:255',
            'feeding_duration' => 'nullable|string|max:255',
            'feeding_problems' => 'nullable|string|max:500',
            'mother_nipple_problems' => 'nullable|string|max:500',
            'urinary_output' => 'nullable|string|max:255',
            'stool_output' => 'nullable|string|max:255',
            'stool_characteristics' => 'nullable|string|max:255',
            'elimination_problems' => 'nullable|string|max:500',
            'responsiveness' => 'nullable|in:good,fair,poor',
            'cry_quality' => 'nullable|in:strong,weak,normal',
            'reflex_assessment' => 'nullable|string|max:500',
            'muscle_tone' => 'nullable|in:normal,increased,decreased',
            'immunizations_up_to_date' => 'nullable|boolean',
            'immunizations_given' => 'nullable|string|max:1000',
            'immunizations_planned' => 'nullable|string|max:1000',
            'newborn_screening_done' => 'nullable|boolean',
            'newborn_screening_results' => 'nullable|string|max:1000',
            'hearing_screening_done' => 'nullable|boolean',
            'hearing_screening_results' => 'nullable|string|max:255',
            'developmental_milestones' => 'nullable|string|max:1000',
            'developmental_concerns' => 'nullable|string|max:1000',
            'mother_recovery_status' => 'nullable|string|max:500',
            'mother_emotional_wellbeing' => 'nullable|string|max:500',
            'mother_breastfeeding_support' => 'nullable|string|max:500',
            'baby_concerns' => 'nullable|string|max:1000',
            'mother_concerns' => 'nullable|string|max:1000',
            'complications_identified' => 'nullable|string|max:1000',
            'counseling_topics' => 'nullable|string|max:1000',
            'infant_care_advice_given' => 'nullable|boolean',
            'feeding_guidance_given' => 'nullable|boolean',
            'cord_care_advice_given' => 'nullable|boolean',
            'hygiene_safety_advice_given' => 'nullable|boolean',
            'danger_signs_explained' => 'nullable|boolean',
            'clinical_summary' => 'nullable|string|max:2000',
            'health_status' => 'required|in:normal,at_risk,needs_referral,referred',
            'referral_reason' => 'nullable|string|max:1000',
            'referral_destination' => 'nullable|string|max:255',
            'management_plan' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
            'next_follow_up_reason' => 'nullable|string|max:500',
        ]);

        $validated['newborn_id'] = $newborn->id;
        $validated['patient_id'] = $newborn->patient_id;
        $validated['recorded_by'] = Auth::id();

        $followUp = ChildFollowUp::create($validated);

        activity()
            ->performedOn($followUp)
            ->withProperties(['action' => 'create'])
            ->log('Child follow-up record created');

        return redirect()->route('midwife.child-follow-up.show', $followUp)
            ->with('success', 'Child follow-up record created successfully.');
    }

    public function show(ChildFollowUp $childFollowUp)
    {
        $childFollowUp->load('newborn.patient.demographic', 'recordedBy');

        return view('midwife.child-follow-up.show', compact('childFollowUp'));
    }

    public function edit(ChildFollowUp $childFollowUp)
    {
        return view('midwife.child-follow-up.edit', compact('childFollowUp'));
    }

    public function update(Request $request, ChildFollowUp $childFollowUp)
    {
        $validated = $request->validate([
            'follow_up_date_time' => 'required|date',
            'days_of_life' => 'required|integer|min=1|max=365',
            'follow_up_period' => 'required|in:day_3,day_7,day_10,day_14,6weeks,3months,6months,year1',
            'location' => 'required|in:home,clinic,hospital,other',
            'location_details' => 'nullable|string|max:255',
            'feeding_type' => 'required|in:breastfeeding,bottle_feeding,mixed,other',
            'how_baby_is_feeding' => 'nullable|string|max:500',
            'mother_observations' => 'nullable|string|max:1000',
            'temperature' => 'nullable|numeric|min=34|max:42',
            'heart_rate' => 'nullable|integer|min:80|max:200',
            'respiratory_rate' => 'nullable|integer|min:20|max:80',
            'weight' => 'nullable|numeric|min:0|max:20',
            'length' => 'nullable|numeric|min:20|max:80',
            'head_circumference' => 'nullable|numeric|min:20|max:50',
            'weight_percentile' => 'nullable|string|max:50',
            'weight_change_since_birth' => 'nullable|string|max:255',
            'weight_gain_rate' => 'nullable|string|max:255',
            'weight_assessment' => 'nullable|in:adequate,inadequate,excessive',
            'general_appearance' => 'nullable|string|max:500',
            'activity_level' => 'nullable|in:active,lethargic,normal',
            'alertness' => 'nullable|in:alert,drowsy,unresponsive',
            'skin_examination' => 'nullable|string|max:500',
            'umbilical_cord_status' => 'nullable|string|max:255',
            'umbilical_discharge' => 'nullable|string|max:255',
            'signs_of_infection' => 'nullable|string|max:500',
            'jaundice_present' => 'nullable|boolean',
            'jaundice_level' => 'nullable|in:mild,moderate,high,severe',
            'jaundice_management' => 'nullable|string|max:500',
            'breast_examination' => 'nullable|string|max:500',
            'latching_quality' => 'nullable|in:good,fair,poor',
            'suckling_pattern' => 'nullable|in:good,fair,poor',
            'milk_transfer' => 'nullable|in:good,fair,poor',
            'bottle_feeding_if_applicable' => 'nullable|string|max:500',
            'feeding_frequency' => 'nullable|string|max:255',
            'feeding_duration' => 'nullable|string|max:255',
            'feeding_problems' => 'nullable|string|max:500',
            'mother_nipple_problems' => 'nullable|string|max:500',
            'urinary_output' => 'nullable|string|max:255',
            'stool_output' => 'nullable|string|max:255',
            'stool_characteristics' => 'nullable|string|max:255',
            'elimination_problems' => 'nullable|string|max:500',
            'responsiveness' => 'nullable|in:good,fair,poor',
            'cry_quality' => 'nullable|in:strong,weak,normal',
            'reflex_assessment' => 'nullable|string|max:500',
            'muscle_tone' => 'nullable|in:normal,increased,decreased',
            'immunizations_up_to_date' => 'nullable|boolean',
            'immunizations_given' => 'nullable|string|max:1000',
            'immunizations_planned' => 'nullable|string|max:1000',
            'newborn_screening_done' => 'nullable|boolean',
            'newborn_screening_results' => 'nullable|string|max:1000',
            'hearing_screening_done' => 'nullable|boolean',
            'hearing_screening_results' => 'nullable|string|max:255',
            'developmental_milestones' => 'nullable|string|max:1000',
            'developmental_concerns' => 'nullable|string|max:1000',
            'mother_recovery_status' => 'nullable|string|max:500',
            'mother_emotional_wellbeing' => 'nullable|string|max:500',
            'mother_breastfeeding_support' => 'nullable|string|max:500',
            'baby_concerns' => 'nullable|string|max:1000',
            'mother_concerns' => 'nullable|string|max:1000',
            'complications_identified' => 'nullable|string|max:1000',
            'counseling_topics' => 'nullable|string|max:1000',
            'infant_care_advice_given' => 'nullable|boolean',
            'feeding_guidance_given' => 'nullable|boolean',
            'cord_care_advice_given' => 'nullable|boolean',
            'hygiene_safety_advice_given' => 'nullable|boolean',
            'danger_signs_explained' => 'nullable|boolean',
            'clinical_summary' => 'nullable|string|max:2000',
            'health_status' => 'required|in:normal,at_risk,needs_referral,referred',
            'referral_reason' => 'nullable|string|max:1000',
            'referral_destination' => 'nullable|string|max:255',
            'management_plan' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
            'next_follow_up_reason' => 'nullable|string|max:500',
        ]);

        $childFollowUp->update($validated);

        activity()
            ->performedOn($childFollowUp)
            ->withProperties(['action' => 'update'])
            ->log('Child follow-up record updated');

        return redirect()->route('midwife.child-follow-up.show', $childFollowUp)
            ->with('success', 'Child follow-up record updated successfully.');
    }

    public function destroy(ChildFollowUp $childFollowUp)
    {
        $childFollowUp->delete();

        activity()
            ->performedOn($childFollowUp)
            ->withProperties(['action' => 'delete'])
            ->log('Child follow-up record deleted');

        return redirect()->route('midwife.child-follow-up.index', $childFollowUp->newborn)
            ->with('success', 'Child follow-up record deleted successfully.');
    }
}