@extends('layouts.app')

@section('title', 'New Newborn Examination')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-plus-circle"></i> Record Newborn Examination for {{ $newborn->newborn_registration_number }}</h1>

    <form action="{{ route('midwife.newborn-examination.store', $newborn) }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label for="examination_date_time" class="form-label">Examination Date & Time</label>
                <input type="datetime-local" id="examination_date_time" name="examination_date_time" class="form-control @error('examination_date_time') is-invalid @enderror" value="{{ old('examination_date_time') }}" required>
                @error('examination_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="hours_after_birth" class="form-label">Hours After Birth</label>
                <input type="number" id="hours_after_birth" name="hours_after_birth" class="form-control @error('hours_after_birth') is-invalid @enderror" value="{{ old('hours_after_birth') }}" min="0" max="168" required>
                @error('hours_after_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="exam_status" class="form-label">Examination Status</label>
                <select id="exam_status" name="exam_status" class="form-select @error('exam_status') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="normal" {{ old('exam_status') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="needs_follow_up" {{ old('exam_status') == 'needs_follow_up' ? 'selected' : '' }}>Needs Follow-up</option>
                    <option value="referral_needed" {{ old('exam_status') == 'referral_needed' ? 'selected' : '' }}>Referral Needed</option>
                </select>
                @error('exam_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="temperature" class="form-label">Temperature (°C)</label>
                <input type="number" step="0.1" id="temperature" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature') }}" min="34" max="42">
                @error('temperature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="heart_rate" class="form-label">Heart Rate (bpm)</label>
                <input type="number" id="heart_rate" name="heart_rate" class="form-control @error('heart_rate') is-invalid @enderror" value="{{ old('heart_rate') }}" min="80" max="200">
                @error('heart_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="respiratory_rate" class="form-label">Respiratory Rate</label>
                <input type="number" id="respiratory_rate" name="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" value="{{ old('respiratory_rate') }}" min="20" max="80">
                @error('respiratory_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="weight" class="form-label">Weight (g)</label>
                <input type="number" id="weight" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}" min="500" max="6000">
                @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="length" class="form-label">Length (cm)</label>
                <input type="number" step="0.1" id="length" name="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length') }}" min="25" max="70">
                @error('length')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="head_circumference" class="form-label">Head Circumference (cm)</label>
                <input type="number" step="0.1" id="head_circumference" name="head_circumference" class="form-control @error('head_circumference') is-invalid @enderror" value="{{ old('head_circumference') }}" min="20" max="50">
                @error('head_circumference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="chest_circumference" class="form-label">Chest Circumference (cm)</label>
                <input type="number" step="0.1" id="chest_circumference" name="chest_circumference" class="form-control @error('chest_circumference') is-invalid @enderror" value="{{ old('chest_circumference') }}" min="20" max="50">
                @error('chest_circumference')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="general_appearance" class="form-label">General Appearance</label>
                <textarea id="general_appearance" name="general_appearance" rows="2" class="form-control @error('general_appearance') is-invalid @enderror">{{ old('general_appearance') }}</textarea>
                @error('general_appearance')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="skin_examination" class="form-label">Skin Examination</label>
                <textarea id="skin_examination" name="skin_examination" rows="2" class="form-control @error('skin_examination') is-invalid @enderror">{{ old('skin_examination') }}</textarea>
                @error('skin_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="head_and_neck" class="form-label">Head & Neck</label>
                <textarea id="head_and_neck" name="head_and_neck" rows="2" class="form-control @error('head_and_neck') is-invalid @enderror">{{ old('head_and_neck') }}</textarea>
                @error('head_and_neck')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="eyes_examination" class="form-label">Eyes</label>
                <textarea id="eyes_examination" name="eyes_examination" rows="2" class="form-control @error('eyes_examination') is-invalid @enderror">{{ old('eyes_examination') }}</textarea>
                @error('eyes_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="ear_examination" class="form-label">Ears</label>
                <textarea id="ear_examination" name="ear_examination" rows="2" class="form-control @error('ear_examination') is-invalid @enderror">{{ old('ear_examination') }}</textarea>
                @error('ear_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="mouth_and_throat" class="form-label">Mouth & Throat</label>
                <textarea id="mouth_and_throat" name="mouth_and_throat" rows="2" class="form-control @error('mouth_and_throat') is-invalid @enderror">{{ old('mouth_and_throat') }}</textarea>
                @error('mouth_and_throat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="heart_sounds" class="form-label">Heart Sounds</label>
                <textarea id="heart_sounds" name="heart_sounds" rows="2" class="form-control @error('heart_sounds') is-invalid @enderror">{{ old('heart_sounds') }}</textarea>
                @error('heart_sounds')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="breath_sounds" class="form-label">Breath Sounds</label>
                <textarea id="breath_sounds" name="breath_sounds" rows="2" class="form-control @error('breath_sounds') is-invalid @enderror">{{ old('breath_sounds') }}</textarea>
                @error('breath_sounds')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="abdomen_shape" class="form-label">Abdomen Shape</label>
                <textarea id="abdomen_shape" name="abdomen_shape" rows="2" class="form-control @error('abdomen_shape') is-invalid @enderror">{{ old('abdomen_shape') }}</textarea>
                @error('abdomen_shape')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="genitalia_examination" class="form-label">Genitalia</label>
                <textarea id="genitalia_examination" name="genitalia_examination" rows="2" class="form-control @error('genitalia_examination') is-invalid @enderror">{{ old('genitalia_examination') }}</textarea>
                @error('genitalia_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="reflex_assessment" class="form-label">Reflex Assessment</label>
                <textarea id="reflex_assessment" name="reflex_assessment" rows="2" class="form-control @error('reflex_assessment') is-invalid @enderror">{{ old('reflex_assessment') }}</textarea>
                @error('reflex_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="muscle_tone" class="form-label">Muscle Tone</label>
                <textarea id="muscle_tone" name="muscle_tone" rows="2" class="form-control @error('muscle_tone') is-invalid @enderror">{{ old('muscle_tone') }}</textarea>
                @error('muscle_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="hip_examination" class="form-label">Hip Examination</label>
                <textarea id="hip_examination" name="hip_examination" rows="2" class="form-control @error('hip_examination') is-invalid @enderror">{{ old('hip_examination') }}</textarea>
                @error('hip_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="feeding_type" class="form-label">Feeding Type</label>
                <textarea id="feeding_type" name="feeding_type" rows="2" class="form-control @error('feeding_type') is-invalid @enderror">{{ old('feeding_type') }}</textarea>
                @error('feeding_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="feeding_tolerance" class="form-label">Feeding Tolerance</label>
                <textarea id="feeding_tolerance" name="feeding_tolerance" rows="2" class="form-control @error('feeding_tolerance') is-invalid @enderror">{{ old('feeding_tolerance') }}</textarea>
                @error('feeding_tolerance')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="abnormal_findings" class="form-label">Abnormal Findings</label>
                <textarea id="abnormal_findings" name="abnormal_findings" rows="3" class="form-control @error('abnormal_findings') is-invalid @enderror">{{ old('abnormal_findings') }}</textarea>
                @error('abnormal_findings')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="clinical_summary" class="form-label">Clinical Summary</label>
                <textarea id="clinical_summary" name="clinical_summary" rows="3" class="form-control @error('clinical_summary') is-invalid @enderror">{{ old('clinical_summary') }}</textarea>
                @error('clinical_summary')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="jaundice_present" class="form-label">Jaundice Present</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="jaundice_present" name="jaundice_present" value="1" {{ old('jaundice_present') ? 'checked' : '' }}>
                    <label class="form-check-label" for="jaundice_present">Yes</label>
                </div>
            </div>

            <div class="col-md-4">
                <label for="jaundice_level" class="form-label">Jaundice Level</label>
                <input type="text" id="jaundice_level" name="jaundice_level" class="form-control @error('jaundice_level') is-invalid @enderror" value="{{ old('jaundice_level') }}">
                @error('jaundice_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="jaundice_management" class="form-label">Jaundice Management</label>
                <textarea id="jaundice_management" name="jaundice_management" rows="2" class="form-control @error('jaundice_management') is-invalid @enderror">{{ old('jaundice_management') }}</textarea>
                @error('jaundice_management')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="follow_up_plans" class="form-label">Follow-up Plans</label>
                <textarea id="follow_up_plans" name="follow_up_plans" rows="2" class="form-control @error('follow_up_plans') is-invalid @enderror">{{ old('follow_up_plans') }}</textarea>
                @error('follow_up_plans')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="next_follow_up_date" class="form-label">Next Follow-up Date</label>
                <input type="date" id="next_follow_up_date" name="next_follow_up_date" class="form-control @error('next_follow_up_date') is-invalid @enderror" value="{{ old('next_follow_up_date') }}">
                @error('next_follow_up_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Examination</button>
                <a href="{{ route('midwife.newborn-examination.index', $newborn) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection