<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\PostnatalExamination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostnatalExaminationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Delivery $delivery)
    {
        $delivery->load('patient.demographic', 'postnatalExaminations.recordedBy');

        return view('midwife.postnatal-examination.index', compact('delivery'));
    }

    public function create(Delivery $delivery)
    {
        return view('midwife.postnatal-examination.create', compact('delivery'));
    }

    public function store(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'examination_date_time' => 'required|date',
            'hours_post_delivery' => 'required|integer|min=0|max=168',
            'examination_time' => 'nullable|in:immediate,early,late',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|integer|min:40|max:200',
            'temperature' => 'nullable|numeric|min=34|max:42',
            'respiration_rate' => 'nullable|integer|min:10|max:40',
            'general_appearance' => 'nullable|string|max:500',
            'consciousness_level' => 'nullable|in:alert,drowsy,unconscious',
            'skin_colour' => 'nullable|string|max:255',
            'uterine_size' => 'nullable|string|max:255',
            'uterine_consistency' => 'nullable|string|max:255',
            'uterine_tenderness' => 'nullable|string|max:255',
            'fundal_height' => 'nullable|string|max:255',
            'lochia_type' => 'nullable|in:rubra,serosa,alba',
            'lochia_amount' => 'nullable|in:scanty,moderate,heavy',
            'lochia_odour' => 'nullable|string|max:255',
            'clot_presence' => 'nullable|boolean',
            'perineal_assessment' => 'nullable|string|max:500',
            'perineal_wound_status' => 'nullable|string|max:500',
            'perineal_pain' => 'nullable|string|max:255',
            'vaginal_examination' => 'nullable|string|max:500',
            'breast_examination' => 'nullable|string|max:500',
            'nipple_condition' => 'nullable|string|max:255',
            'breast_engorgement' => 'nullable|string|max:255',
            'breast_milk_expression' => 'nullable|string|max:255',
            'breastfeeding_successful' => 'nullable|boolean',
            'breastfeeding_problems' => 'nullable|string|max:500',
            'abdominal_examination' => 'nullable|string|max:500',
            'wound_assessment' => 'nullable|string|max:500',
            'drain_status' => 'nullable|string|max:255',
            'lower_limbs_examination' => 'nullable|string|max:500',
            'oedema_assessment' => 'nullable|string|max:255',
            'calf_tenderness' => 'nullable|string|max:255',
            'signs_of_dvt' => 'nullable|string|max:255',
            'maternal_mood' => 'nullable|string|max:255',
            'emotional_state' => 'nullable|string|max:500',
            'signs_of_depression' => 'nullable|boolean',
            'bonding_with_baby' => 'nullable|string|max:500',
            'complications_identified' => 'nullable|string|max:1000',
            'infection_signs' => 'nullable|string|max:500',
            'bleeding_assessment' => 'nullable|string|max:500',
            'hypertension_assessment' => 'nullable|string|max:500',
            'sleep_patterns' => 'nullable|string|max:255',
            'pain_level' => 'nullable|string|max:255',
            'activity_tolerance' => 'nullable|string|max:255',
            'perineal_care_ability' => 'nullable|string|max:255',
            'counseling_topics' => 'nullable|string|max:1000',
            'contraception_discussed' => 'nullable|boolean',
            'contraception_method_chosen' => 'nullable|string|max:255',
            'hygiene_taught' => 'nullable|boolean',
            'danger_signs_explained' => 'nullable|boolean',
            'clinical_summary' => 'nullable|string|max:2000',
            'recovery_status' => 'required|in:normal,needs_attention,needs_referral',
            'management_plan' => 'nullable|string|max:1000',
            'medications_prescribed' => 'nullable|string|max:1000',
            'follow_up_plan' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $validated['delivery_id'] = $delivery->id;
        $validated['patient_id'] = $delivery->patient_id;
        $validated['recorded_by'] = Auth::id();

        $examination = PostnatalExamination::create($validated);

        activity()
            ->performedOn($examination)
            ->withProperties(['action' => 'create'])
            ->log('Postnatal examination record created');

        return redirect()->route('midwife.postnatal-examination.show', $examination)
            ->with('success', 'Postnatal examination record created successfully.');
    }

    public function show(PostnatalExamination $postnatalExamination)
    {
        $postnatalExamination->load('delivery.patient.demographic', 'recordedBy');

        return view('midwife.postnatal-examination.show', compact('postnatalExamination'));
    }

    public function edit(PostnatalExamination $postnatalExamination)
    {
        return view('midwife.postnatal-examination.edit', compact('postnatalExamination'));
    }

    public function update(Request $request, PostnatalExamination $postnatalExamination)
    {
        $validated = $request->validate([
            'examination_date_time' => 'required|date',
            'hours_post_delivery' => 'required|integer|min=0|max=168',
            'examination_time' => 'nullable|in:immediate,early,late',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|integer|min:40|max:200',
            'temperature' => 'nullable|numeric|min=34|max:42',
            'respiration_rate' => 'nullable|integer|min:10|max:40',
            'general_appearance' => 'nullable|string|max:500',
            'consciousness_level' => 'nullable|in:alert,drowsy,unconscious',
            'skin_colour' => 'nullable|string|max:255',
            'uterine_size' => 'nullable|string|max:255',
            'uterine_consistency' => 'nullable|string|max:255',
            'uterine_tenderness' => 'nullable|string|max:255',
            'fundal_height' => 'nullable|string|max:255',
            'lochia_type' => 'nullable|in:rubra,serosa,alba',
            'lochia_amount' => 'nullable|in:scanty,moderate,heavy',
            'lochia_odour' => 'nullable|string|max:255',
            'clot_presence' => 'nullable|boolean',
            'perineal_assessment' => 'nullable|string|max:500',
            'perineal_wound_status' => 'nullable|string|max:500',
            'perineal_pain' => 'nullable|string|max:255',
            'vaginal_examination' => 'nullable|string|max:500',
            'breast_examination' => 'nullable|string|max:500',
            'nipple_condition' => 'nullable|string|max:255',
            'breast_engorgement' => 'nullable|string|max:255',
            'breast_milk_expression' => 'nullable|string|max:255',
            'breastfeeding_successful' => 'nullable|boolean',
            'breastfeeding_problems' => 'nullable|string|max:500',
            'abdominal_examination' => 'nullable|string|max:500',
            'wound_assessment' => 'nullable|string|max:500',
            'drain_status' => 'nullable|string|max:255',
            'lower_limbs_examination' => 'nullable|string|max:500',
            'oedema_assessment' => 'nullable|string|max:255',
            'calf_tenderness' => 'nullable|string|max:255',
            'signs_of_dvt' => 'nullable|string|max:255',
            'maternal_mood' => 'nullable|string|max:255',
            'emotional_state' => 'nullable|string|max:500',
            'signs_of_depression' => 'nullable|boolean',
            'bonding_with_baby' => 'nullable|string|max:500',
            'complications_identified' => 'nullable|string|max:1000',
            'infection_signs' => 'nullable|string|max:500',
            'bleeding_assessment' => 'nullable|string|max:500',
            'hypertension_assessment' => 'nullable|string|max:500',
            'sleep_patterns' => 'nullable|string|max:255',
            'pain_level' => 'nullable|string|max:255',
            'activity_tolerance' => 'nullable|string|max:255',
            'perineal_care_ability' => 'nullable|string|max:255',
            'counseling_topics' => 'nullable|string|max:1000',
            'contraception_discussed' => 'nullable|boolean',
            'contraception_method_chosen' => 'nullable|string|max:255',
            'hygiene_taught' => 'nullable|boolean',
            'danger_signs_explained' => 'nullable|boolean',
            'clinical_summary' => 'nullable|string|max:2000',
            'recovery_status' => 'required|in:normal,needs_attention,needs_referral',
            'management_plan' => 'nullable|string|max:1000',
            'medications_prescribed' => 'nullable|string|max:1000',
            'follow_up_plan' => 'nullable|string|max:1000',
            'next_follow_up_date' => 'nullable|date',
        ]);

        $postnatalExamination->update($validated);

        activity()
            ->performedOn($postnatalExamination)
            ->withProperties(['action' => 'update'])
            ->log('Postnatal examination record updated');

        return redirect()->route('midwife.postnatal-examination.show', $postnatalExamination)
            ->with('success', 'Postnatal examination record updated successfully.');
    }

    public function destroy(PostnatalExamination $postnatalExamination)
    {
        $postnatalExamination->delete();

        activity()
            ->performedOn($postnatalExamination)
            ->withProperties(['action' => 'delete'])
            ->log('Postnatal examination record deleted');

        return redirect()->route('midwife.postnatal-examination.index', $postnatalExamination->delivery)
            ->with('success', 'Postnatal examination record deleted successfully.');
    }
}