@extends('layouts.app')

@section('title', 'New Postnatal Examination')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-plus-circle"></i> Record Postnatal Examination for {{ $delivery->patient->full_name }}</h1>

    <form action="{{ route('midwife.postnatal-examination.store', $delivery) }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Examination Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="examination_date_time" class="form-label">Examination Date & Time</label>
                        <input type="datetime-local" id="examination_date_time" name="examination_date_time" class="form-control @error('examination_date_time') is-invalid @enderror" value="{{ old('examination_date_time') }}" required>
                        @error('examination_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="hours_post_delivery" class="form-label">Hours Post Delivery</label>
                        <input type="number" id="hours_post_delivery" name="hours_post_delivery" class="form-control @error('hours_post_delivery') is-invalid @enderror" value="{{ old('hours_post_delivery') }}" min="0" max="168" required>
                        @error('hours_post_delivery')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="examination_time" class="form-label">Examination Time</label>
                        <select id="examination_time" name="examination_time" class="form-select @error('examination_time') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="immediate" {{ old('examination_time') == 'immediate' ? 'selected' : '' }}>Immediate (within 1 hour)</option>
                            <option value="early" {{ old('examination_time') == 'early' ? 'selected' : '' }}>Early (1-24 hours)</option>
                            <option value="late" {{ old('examination_time') == 'late' ? 'selected' : '' }}>Late (after 24 hours)</option>
                        </select>
                        @error('examination_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Vital Signs</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="blood_pressure" class="form-label">Blood Pressure</label>
                        <input type="text" id="blood_pressure" name="blood_pressure" class="form-control @error('blood_pressure') is-invalid @enderror" value="{{ old('blood_pressure') }}" placeholder="120/80">
                        @error('blood_pressure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="pulse_rate" class="form-label">Pulse Rate (bpm)</label>
                        <input type="number" id="pulse_rate" name="pulse_rate" class="form-control @error('pulse_rate') is-invalid @enderror" value="{{ old('pulse_rate') }}" min="40" max="200">
                        @error('pulse_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="temperature" class="form-label">Temperature (°C)</label>
                        <input type="number" step="0.1" id="temperature" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature') }}" min="34" max="42">
                        @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="respiration_rate" class="form-label">Respiration Rate</label>
                        <input type="number" id="respiration_rate" name="respiration_rate" class="form-control @error('respiration_rate') is-invalid @enderror" value="{{ old('respiration_rate') }}" min="10" max="40">
                        @error('respiration_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">General Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="general_appearance" class="form-label">General Appearance</label>
                        <textarea id="general_appearance" name="general_appearance" class="form-control @error('general_appearance') is-invalid @enderror" rows="3">{{ old('general_appearance') }}</textarea>
                        @error('general_appearance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="consciousness_level" class="form-label">Consciousness Level</label>
                        <select id="consciousness_level" name="consciousness_level" class="form-select @error('consciousness_level') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="alert" {{ old('consciousness_level') == 'alert' ? 'selected' : '' }}>Alert</option>
                            <option value="drowsy" {{ old('consciousness_level') == 'drowsy' ? 'selected' : '' }}>Drowsy</option>
                            <option value="unconscious" {{ old('consciousness_level') == 'unconscious' ? 'selected' : '' }}>Unconscious</option>
                        </select>
                        @error('consciousness_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="skin_colour" class="form-label">Skin Colour</label>
                        <input type="text" id="skin_colour" name="skin_colour" class="form-control @error('skin_colour') is-invalid @enderror" value="{{ old('skin_colour') }}">
                        @error('skin_colour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Uterine Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="uterine_size" class="form-label">Uterine Size</label>
                        <input type="text" id="uterine_size" name="uterine_size" class="form-control @error('uterine_size') is-invalid @enderror" value="{{ old('uterine_size') }}">
                        @error('uterine_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="uterine_consistency" class="form-label">Uterine Consistency</label>
                        <input type="text" id="uterine_consistency" name="uterine_consistency" class="form-control @error('uterine_consistency') is-invalid @enderror" value="{{ old('uterine_consistency') }}">
                        @error('uterine_consistency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="uterine_tenderness" class="form-label">Uterine Tenderness</label>
                        <input type="text" id="uterine_tenderness" name="uterine_tenderness" class="form-control @error('uterine_tenderness') is-invalid @enderror" value="{{ old('uterine_tenderness') }}">
                        @error('uterine_tenderness')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="fundal_height" class="form-label">Fundal Height</label>
                        <input type="text" id="fundal_height" name="fundal_height" class="form-control @error('fundal_height') is-invalid @enderror" value="{{ old('fundal_height') }}">
                        @error('fundal_height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Lochia Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="lochia_type" class="form-label">Lochia Type</label>
                        <select id="lochia_type" name="lochia_type" class="form-select @error('lochia_type') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="rubra" {{ old('lochia_type') == 'rubra' ? 'selected' : '' }}>Rubra (red)</option>
                            <option value="serosa" {{ old('lochia_type') == 'serosa' ? 'selected' : '' }}>Serosa (pinkish)</option>
                            <option value="alba" {{ old('lochia_type') == 'alba' ? 'selected' : '' }}>Alba (white/yellow)</option>
                        </select>
                        @error('lochia_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="lochia_amount" class="form-label">Lochia Amount</label>
                        <select id="lochia_amount" name="lochia_amount" class="form-select @error('lochia_amount') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="scanty" {{ old('lochia_amount') == 'scanty' ? 'selected' : '' }}>Scanty</option>
                            <option value="moderate" {{ old('lochia_amount') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="heavy" {{ old('lochia_amount') == 'heavy' ? 'selected' : '' }}>Heavy</option>
                        </select>
                        @error('lochia_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="lochia_odour" class="form-label">Lochia Odour</label>
                        <input type="text" id="lochia_odour" name="lochia_odour" class="form-control @error('lochia_odour') is-invalid @enderror" value="{{ old('lochia_odour') }}">
                        @error('lochia_odour')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="clot_presence" class="form-label">Clots Present</label>
                        <div class="form-check">
                            <input type="checkbox" id="clot_presence" name="clot_presence" class="form-check-input @error('clot_presence') is-invalid @enderror" value="1" {{ old('clot_presence') ? 'checked' : '' }}>
                            <label for="clot_presence" class="form-check-label">Yes</label>
                        </div>
                        @error('clot_presence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Perineal Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="perineal_assessment" class="form-label">Perineal Assessment</label>
                        <textarea id="perineal_assessment" name="perineal_assessment" class="form-control @error('perineal_assessment') is-invalid @enderror" rows="3">{{ old('perineal_assessment') }}</textarea>
                        @error('perineal_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="perineal_wound_status" class="form-label">Perineal Wound Status</label>
                        <textarea id="perineal_wound_status" name="perineal_wound_status" class="form-control @error('perineal_wound_status') is-invalid @enderror" rows="3">{{ old('perineal_wound_status') }}</textarea>
                        @error('perineal_wound_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="perineal_pain" class="form-label">Perineal Pain</label>
                        <input type="text" id="perineal_pain" name="perineal_pain" class="form-control @error('perineal_pain') is-invalid @enderror" value="{{ old('perineal_pain') }}">
                        @error('perineal_pain')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="vaginal_examination" class="form-label">Vaginal Examination</label>
                        <textarea id="vaginal_examination" name="vaginal_examination" class="form-control @error('vaginal_examination') is-invalid @enderror" rows="3">{{ old('vaginal_examination') }}</textarea>
                        @error('vaginal_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Breastfeeding Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="breast_examination" class="form-label">Breast Examination</label>
                        <textarea id="breast_examination" name="breast_examination" class="form-control @error('breast_examination') is-invalid @enderror" rows="3">{{ old('breast_examination') }}</textarea>
                        @error('breast_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="nipple_condition" class="form-label">Nipple Condition</label>
                        <input type="text" id="nipple_condition" name="nipple_condition" class="form-control @error('nipple_condition') is-invalid @enderror" value="{{ old('nipple_condition') }}">
                        @error('nipple_condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="breast_engorgement" class="form-label">Breast Engorgement</label>
                        <input type="text" id="breast_engorgement" name="breast_engorgement" class="form-control @error('breast_engorgement') is-invalid @enderror" value="{{ old('breast_engorgement') }}">
                        @error('breast_engorgement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="breast_milk_expression" class="form-label">Milk Expression</label>
                        <input type="text" id="breast_milk_expression" name="breast_milk_expression" class="form-control @error('breast_milk_expression') is-invalid @enderror" value="{{ old('breast_milk_expression') }}">
                        @error('breast_milk_expression')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="breastfeeding_successful" class="form-label">Breastfeeding Successful</label>
                        <div class="form-check">
                            <input type="checkbox" id="breastfeeding_successful" name="breastfeeding_successful" class="form-check-input @error('breastfeeding_successful') is-invalid @enderror" value="1" {{ old('breastfeeding_successful') ? 'checked' : '' }}>
                            <label for="breastfeeding_successful" class="form-check-label">Yes</label>
                        </div>
                        @error('breastfeeding_successful')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="breastfeeding_problems" class="form-label">Breastfeeding Problems</label>
                        <textarea id="breastfeeding_problems" name="breastfeeding_problems" class="form-control @error('breastfeeding_problems') is-invalid @enderror" rows="3">{{ old('breastfeeding_problems') }}</textarea>
                        @error('breastfeeding_problems')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Additional Assessments</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="abdominal_examination" class="form-label">Abdominal Examination</label>
                        <textarea id="abdominal_examination" name="abdominal_examination" class="form-control @error('abdominal_examination') is-invalid @enderror" rows="3">{{ old('abdominal_examination') }}</textarea>
                        @error('abdominal_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="wound_assessment" class="form-label">Wound Assessment</label>
                        <textarea id="wound_assessment" name="wound_assessment" class="form-control @error('wound_assessment') is-invalid @enderror" rows="3">{{ old('wound_assessment') }}</textarea>
                        @error('wound_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="lower_limbs_examination" class="form-label">Lower Limbs Examination</label>
                        <textarea id="lower_limbs_examination" name="lower_limbs_examination" class="form-control @error('lower_limbs_examination') is-invalid @enderror" rows="3">{{ old('lower_limbs_examination') }}</textarea>
                        @error('lower_limbs_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="oedema_assessment" class="form-label">Oedema Assessment</label>
                        <input type="text" id="oedema_assessment" name="oedema_assessment" class="form-control @error('oedema_assessment') is-invalid @enderror" value="{{ old('oedema_assessment') }}">
                        @error('oedema_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Mental Health & Bonding</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="maternal_mood" class="form-label">Maternal Mood</label>
                        <input type="text" id="maternal_mood" name="maternal_mood" class="form-control @error('maternal_mood') is-invalid @enderror" value="{{ old('maternal_mood') }}">
                        @error('maternal_mood')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="emotional_state" class="form-label">Emotional State</label>
                        <textarea id="emotional_state" name="emotional_state" class="form-control @error('emotional_state') is-invalid @enderror" rows="3">{{ old('emotional_state') }}</textarea>
                        @error('emotional_state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="signs_of_depression" class="form-label">Signs of Depression</label>
                        <div class="form-check">
                            <input type="checkbox" id="signs_of_depression" name="signs_of_depression" class="form-check-input @error('signs_of_depression') is-invalid @enderror" value="1" {{ old('signs_of_depression') ? 'checked' : '' }}>
                            <label for="signs_of_depression" class="form-check-label">Present</label>
                        </div>
                        @error('signs_of_depression')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="bonding_with_baby" class="form-label">Bonding with Baby</label>
                        <textarea id="bonding_with_baby" name="bonding_with_baby" class="form-control @error('bonding_with_baby') is-invalid @enderror" rows="3">{{ old('bonding_with_baby') }}</textarea>
                        @error('bonding_with_baby')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Complications & Counseling</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="complications_identified" class="form-label">Complications Identified</label>
                        <textarea id="complications_identified" name="complications_identified" class="form-control @error('complications_identified') is-invalid @enderror" rows="3">{{ old('complications_identified') }}</textarea>
                        @error('complications_identified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="infection_signs" class="form-label">Signs of Infection</label>
                        <textarea id="infection_signs" name="infection_signs" class="form-control @error('infection_signs') is-invalid @enderror" rows="3">{{ old('infection_signs') }}</textarea>
                        @error('infection_signs')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bleeding_assessment" class="form-label">Bleeding Assessment</label>
                        <textarea id="bleeding_assessment" name="bleeding_assessment" class="form-control @error('bleeding_assessment') is-invalid @enderror" rows="3">{{ old('bleeding_assessment') }}</textarea>
                        @error('bleeding_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="hypertension_assessment" class="form-label">Hypertension Assessment</label>
                        <textarea id="hypertension_assessment" name="hypertension_assessment" class="form-control @error('hypertension_assessment') is-invalid @enderror" rows="3">{{ old('hypertension_assessment') }}</textarea>
                        @error('hypertension_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sleep_patterns" class="form-label">Sleep Patterns</label>
                        <input type="text" id="sleep_patterns" name="sleep_patterns" class="form-control @error('sleep_patterns') is-invalid @enderror" value="{{ old('sleep_patterns') }}">
                        @error('sleep_patterns')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="pain_level" class="form-label">Pain Level</label>
                        <input type="text" id="pain_level" name="pain_level" class="form-control @error('pain_level') is-invalid @enderror" value="{{ old('pain_level') }}">
                        @error('pain_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="activity_tolerance" class="form-label">Activity Tolerance</label>
                        <input type="text" id="activity_tolerance" name="activity_tolerance" class="form-control @error('activity_tolerance') is-invalid @enderror" value="{{ old('activity_tolerance') }}">
                        @error('activity_tolerance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="perineal_care_ability" class="form-label">Perineal Care Ability</label>
                        <input type="text" id="perineal_care_ability" name="perineal_care_ability" class="form-control @error('perineal_care_ability') is-invalid @enderror" value="{{ old('perineal_care_ability') }}">
                        @error('perineal_care_ability')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="counseling_topics" class="form-label">Counseling Topics Covered</label>
                        <textarea id="counseling_topics" name="counseling_topics" class="form-control @error('counseling_topics') is-invalid @enderror" rows="3">{{ old('counseling_topics') }}</textarea>
                        @error('counseling_topics')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="contraception_discussed" class="form-label">Contraception Discussed</label>
                        <div class="form-check">
                            <input type="checkbox" id="contraception_discussed" name="contraception_discussed" class="form-check-input @error('contraception_discussed') is-invalid @enderror" value="1" {{ old('contraception_discussed') ? 'checked' : '' }}>
                            <label for="contraception_discussed" class="form-check-label">Yes</label>
                        </div>
                        @error('contraception_discussed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="contraception_method_chosen" class="form-label">Contraception Method Chosen</label>
                        <input type="text" id="contraception_method_chosen" name="contraception_method_chosen" class="form-control @error('contraception_method_chosen') is-invalid @enderror" value="{{ old('contraception_method_chosen') }}">
                        @error('contraception_method_chosen')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="hygiene_taught" class="form-label">Hygiene Taught</label>
                        <div class="form-check">
                            <input type="checkbox" id="hygiene_taught" name="hygiene_taught" class="form-check-input @error('hygiene_taught') is-invalid @enderror" value="1" {{ old('hygiene_taught') ? 'checked' : '' }}>
                            <label for="hygiene_taught" class="form-check-label">Yes</label>
                        </div>
                        @error('hygiene_taught')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="danger_signs_explained" class="form-label">Danger Signs Explained</label>
                        <div class="form-check">
                            <input type="checkbox" id="danger_signs_explained" name="danger_signs_explained" class="form-check-input @error('danger_signs_explained') is-invalid @enderror" value="1" {{ old('danger_signs_explained') ? 'checked' : '' }}>
                            <label for="danger_signs_explained" class="form-check-label">Yes</label>
                        </div>
                        @error('danger_signs_explained')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Summary & Plan</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="clinical_summary" class="form-label">Clinical Summary</label>
                        <textarea id="clinical_summary" name="clinical_summary" class="form-control @error('clinical_summary') is-invalid @enderror" rows="4">{{ old('clinical_summary') }}</textarea>
                        @error('clinical_summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="recovery_status" class="form-label">Recovery Status</label>
                        <select id="recovery_status" name="recovery_status" class="form-select @error('recovery_status') is-invalid @enderror" required>
                            <option value="">Choose...</option>
                            <option value="normal" {{ old('recovery_status') == 'normal' ? 'selected' : '' }}>Normal Recovery</option>
                            <option value="needs_attention" {{ old('recovery_status') == 'needs_attention' ? 'selected' : '' }}>Needs Attention</option>
                            <option value="needs_referral" {{ old('recovery_status') == 'needs_referral' ? 'selected' : '' }}>Needs Referral</option>
                        </select>
                        @error('recovery_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="management_plan" class="form-label">Management Plan</label>
                        <textarea id="management_plan" name="management_plan" class="form-control @error('management_plan') is-invalid @enderror" rows="3">{{ old('management_plan') }}</textarea>
                        @error('management_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="medications_prescribed" class="form-label">Medications Prescribed</label>
                        <textarea id="medications_prescribed" name="medications_prescribed" class="form-control @error('medications_prescribed') is-invalid @enderror" rows="3">{{ old('medications_prescribed') }}</textarea>
                        @error('medications_prescribed')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="follow_up_plan" class="form-label">Follow-up Plan</label>
                        <textarea id="follow_up_plan" name="follow_up_plan" class="form-control @error('follow_up_plan') is-invalid @enderror" rows="3">{{ old('follow_up_plan') }}</textarea>
                        @error('follow_up_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="next_follow_up_date" class="form-label">Next Follow-up Date</label>
                        <input type="date" id="next_follow_up_date" name="next_follow_up_date" class="form-control @error('next_follow_up_date') is-invalid @enderror" value="{{ old('next_follow_up_date') }}">
                        @error('next_follow_up_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Postnatal Examination</button>
            <a href="{{ route('midwife.postnatal-examination.index', $delivery) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection