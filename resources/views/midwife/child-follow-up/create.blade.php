@extends('layouts.app')

@section('title', 'New Child Follow-up')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-plus-circle"></i> Record Child Follow-up for {{ $newborn->newborn_registration_number }}</h1>

    <form action="{{ route('midwife.child-follow-up.store', $newborn) }}" method="POST">
        @csrf

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Follow-up Details</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="follow_up_date_time" class="form-label">Follow-up Date & Time</label>
                        <input type="datetime-local" id="follow_up_date_time" name="follow_up_date_time" class="form-control @error('follow_up_date_time') is-invalid @enderror" value="{{ old('follow_up_date_time') }}" required>
                        @error('follow_up_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="days_of_life" class="form-label">Days of Life</label>
                        <input type="number" id="days_of_life" name="days_of_life" class="form-control @error('days_of_life') is-invalid @enderror" value="{{ old('days_of_life') }}" min="1" max="365" required>
                        @error('days_of_life')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="follow_up_period" class="form-label">Follow-up Period</label>
                        <select id="follow_up_period" name="follow_up_period" class="form-select @error('follow_up_period') is-invalid @enderror" required>
                            <option value="">Choose...</option>
                            <option value="day_3" {{ old('follow_up_period') == 'day_3' ? 'selected' : '' }}>Day 3</option>
                            <option value="day_7" {{ old('follow_up_period') == 'day_7' ? 'selected' : '' }}>Day 7</option>
                            <option value="day_10" {{ old('follow_up_period') == 'day_10' ? 'selected' : '' }}>Day 10</option>
                            <option value="day_14" {{ old('follow_up_period') == 'day_14' ? 'selected' : '' }}>Day 14</option>
                            <option value="6weeks" {{ old('follow_up_period') == '6weeks' ? 'selected' : '' }}>6 Weeks</option>
                            <option value="3months" {{ old('follow_up_period') == '3months' ? 'selected' : '' }}>3 Months</option>
                            <option value="6months" {{ old('follow_up_period') == '6months' ? 'selected' : '' }}>6 Months</option>
                            <option value="year1" {{ old('follow_up_period') == 'year1' ? 'selected' : '' }}>1 Year</option>
                        </select>
                        @error('follow_up_period')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="location" class="form-label">Location</label>
                        <select id="location" name="location" class="form-select @error('location') is-invalid @enderror" required>
                            <option value="">Choose...</option>
                            <option value="home" {{ old('location') == 'home' ? 'selected' : '' }}>Home Visit</option>
                            <option value="clinic" {{ old('location') == 'clinic' ? 'selected' : '' }}>Clinic</option>
                            <option value="hospital" {{ old('location') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                            <option value="other" {{ old('location') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="location_details" class="form-label">Location Details</label>
                        <input type="text" id="location_details" name="location_details" class="form-control @error('location_details') is-invalid @enderror" value="{{ old('location_details') }}">
                        @error('location_details')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Feeding Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="feeding_type" class="form-label">Feeding Type</label>
                        <select id="feeding_type" name="feeding_type" class="form-select @error('feeding_type') is-invalid @enderror" required>
                            <option value="">Choose...</option>
                            <option value="breastfeeding" {{ old('feeding_type') == 'breastfeeding' ? 'selected' : '' }}>Exclusive Breastfeeding</option>
                            <option value="bottle_feeding" {{ old('feeding_type') == 'bottle_feeding' ? 'selected' : '' }}>Bottle Feeding</option>
                            <option value="mixed" {{ old('feeding_type') == 'mixed' ? 'selected' : '' }}>Mixed Feeding</option>
                            <option value="other" {{ old('feeding_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('feeding_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="how_baby_is_feeding" class="form-label">How Baby is Feeding</label>
                        <textarea id="how_baby_is_feeding" name="how_baby_is_feeding" class="form-control @error('how_baby_is_feeding') is-invalid @enderror" rows="2">{{ old('how_baby_is_feeding') }}</textarea>
                        @error('how_baby_is_feeding')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="mother_observations" class="form-label">Mother's Observations</label>
                        <textarea id="mother_observations" name="mother_observations" class="form-control @error('mother_observations') is-invalid @enderror" rows="3">{{ old('mother_observations') }}</textarea>
                        @error('mother_observations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Vital Signs & Growth</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
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
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" id="weight" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight') }}" min="0" max="20">
                        @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="length" class="form-label">Length (cm)</label>
                        <input type="number" step="0.1" id="length" name="length" class="form-control @error('length') is-invalid @enderror" value="{{ old('length') }}" min="20" max="80">
                        @error('length')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="head_circumference" class="form-label">Head Circumference (cm)</label>
                        <input type="number" step="0.1" id="head_circumference" name="head_circumference" class="form-control @error('head_circumference') is-invalid @enderror" value="{{ old('head_circumference') }}" min="20" max="50">
                        @error('head_circumference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="weight_percentile" class="form-label">Weight Percentile</label>
                        <input type="text" id="weight_percentile" name="weight_percentile" class="form-control @error('weight_percentile') is-invalid @enderror" value="{{ old('weight_percentile') }}" placeholder="e.g., 50th percentile">
                        @error('weight_percentile')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="weight_change_since_birth" class="form-label">Weight Change Since Birth</label>
                        <input type="text" id="weight_change_since_birth" name="weight_change_since_birth" class="form-control @error('weight_change_since_birth') is-invalid @enderror" value="{{ old('weight_change_since_birth') }}">
                        @error('weight_change_since_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="weight_gain_rate" class="form-label">Weight Gain Rate</label>
                        <input type="text" id="weight_gain_rate" name="weight_gain_rate" class="form-control @error('weight_gain_rate') is-invalid @enderror" value="{{ old('weight_gain_rate') }}">
                        @error('weight_gain_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="weight_assessment" class="form-label">Weight Assessment</label>
                        <select id="weight_assessment" name="weight_assessment" class="form-select @error('weight_assessment') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="adequate" {{ old('weight_assessment') == 'adequate' ? 'selected' : '' }}>Adequate</option>
                            <option value="inadequate" {{ old('weight_assessment') == 'inadequate' ? 'selected' : '' }}>Inadequate</option>
                            <option value="excessive" {{ old('weight_assessment') == 'excessive' ? 'selected' : '' }}>Excessive</option>
                        </select>
                        @error('weight_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Physical Examination</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="general_appearance" class="form-label">General Appearance</label>
                        <textarea id="general_appearance" name="general_appearance" class="form-control @error('general_appearance') is-invalid @enderror" rows="2">{{ old('general_appearance') }}</textarea>
                        @error('general_appearance')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="activity_level" class="form-label">Activity Level</label>
                        <select id="activity_level" name="activity_level" class="form-select @error('activity_level') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="active" {{ old('activity_level') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="lethargic" {{ old('activity_level') == 'lethargic' ? 'selected' : '' }}>Lethargic</option>
                            <option value="normal" {{ old('activity_level') == 'normal' ? 'selected' : '' }}>Normal</option>
                        </select>
                        @error('activity_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="alertness" class="form-label">Alertness</label>
                        <select id="alertness" name="alertness" class="form-select @error('alertness') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="alert" {{ old('alertness') == 'alert' ? 'selected' : '' }}>Alert</option>
                            <option value="drowsy" {{ old('alertness') == 'drowsy' ? 'selected' : '' }}>Drowsy</option>
                            <option value="unresponsive" {{ old('alertness') == 'unresponsive' ? 'selected' : '' }}>Unresponsive</option>
                        </select>
                        @error('alertness')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="skin_examination" class="form-label">Skin Examination</label>
                        <textarea id="skin_examination" name="skin_examination" class="form-control @error('skin_examination') is-invalid @enderror" rows="2">{{ old('skin_examination') }}</textarea>
                        @error('skin_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Umbilical Cord & Jaundice</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="umbilical_cord_status" class="form-label">Umbilical Cord Status</label>
                        <input type="text" id="umbilical_cord_status" name="umbilical_cord_status" class="form-control @error('umbilical_cord_status') is-invalid @enderror" value="{{ old('umbilical_cord_status') }}">
                        @error('umbilical_cord_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="umbilical_discharge" class="form-label">Umbilical Discharge</label>
                        <input type="text" id="umbilical_discharge" name="umbilical_discharge" class="form-control @error('umbilical_discharge') is-invalid @enderror" value="{{ old('umbilical_discharge') }}">
                        @error('umbilical_discharge')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="signs_of_infection" class="form-label">Signs of Infection</label>
                        <textarea id="signs_of_infection" name="signs_of_infection" class="form-control @error('signs_of_infection') is-invalid @enderror" rows="2">{{ old('signs_of_infection') }}</textarea>
                        @error('signs_of_infection')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="jaundice_present" class="form-label">Jaundice Present</label>
                        <div class="form-check">
                            <input type="checkbox" id="jaundice_present" name="jaundice_present" class="form-check-input @error('jaundice_present') is-invalid @enderror" value="1" {{ old('jaundice_present') ? 'checked' : '' }}>
                            <label for="jaundice_present" class="form-check-label">Yes</label>
                        </div>
                        @error('jaundice_present')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="jaundice_level" class="form-label">Jaundice Level</label>
                        <select id="jaundice_level" name="jaundice_level" class="form-select @error('jaundice_level') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="mild" {{ old('jaundice_level') == 'mild' ? 'selected' : '' }}>Mild</option>
                            <option value="moderate" {{ old('jaundice_level') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="high" {{ old('jaundice_level') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="severe" {{ old('jaundice_level') == 'severe' ? 'selected' : '' }}>Severe</option>
                        </select>
                        @error('jaundice_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jaundice_management" class="form-label">Jaundice Management</label>
                        <textarea id="jaundice_management" name="jaundice_management" class="form-control @error('jaundice_management') is-invalid @enderror" rows="2">{{ old('jaundice_management') }}</textarea>
                        @error('jaundice_management')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
                    <div class="col-md-12">
                        <label for="breast_examination" class="form-label">Breast Examination</label>
                        <textarea id="breast_examination" name="breast_examination" class="form-control @error('breast_examination') is-invalid @enderror" rows="2">{{ old('breast_examination') }}</textarea>
                        @error('breast_examination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="latching_quality" class="form-label">Latching Quality</label>
                        <select id="latching_quality" name="latching_quality" class="form-select @error('latching_quality') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="good" {{ old('latching_quality') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('latching_quality') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('latching_quality') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('latching_quality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="suckling_pattern" class="form-label">Suckling Pattern</label>
                        <select id="suckling_pattern" name="suckling_pattern" class="form-select @error('suckling_pattern') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="good" {{ old('suckling_pattern') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('suckling_pattern') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('suckling_pattern') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('suckling_pattern')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="milk_transfer" class="form-label">Milk Transfer</label>
                        <select id="milk_transfer" name="milk_transfer" class="form-select @error('milk_transfer') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="good" {{ old('milk_transfer') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('milk_transfer') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('milk_transfer') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('milk_transfer')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="bottle_feeding_if_applicable" class="form-label">Bottle Feeding (if applicable)</label>
                        <textarea id="bottle_feeding_if_applicable" name="bottle_feeding_if_applicable" class="form-control @error('bottle_feeding_if_applicable') is-invalid @enderror" rows="2">{{ old('bottle_feeding_if_applicable') }}</textarea>
                        @error('bottle_feeding_if_applicable')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mother_nipple_problems" class="form-label">Mother Nipple Problems</label>
                        <textarea id="mother_nipple_problems" name="mother_nipple_problems" class="form-control @error('mother_nipple_problems') is-invalid @enderror" rows="2">{{ old('mother_nipple_problems') }}</textarea>
                        @error('mother_nipple_problems')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Feeding & Elimination</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="feeding_frequency" class="form-label">Feeding Frequency</label>
                        <input type="text" id="feeding_frequency" name="feeding_frequency" class="form-control @error('feeding_frequency') is-invalid @enderror" value="{{ old('feeding_frequency') }}">
                        @error('feeding_frequency')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="feeding_duration" class="form-label">Feeding Duration</label>
                        <input type="text" id="feeding_duration" name="feeding_duration" class="form-control @error('feeding_duration') is-invalid @enderror" value="{{ old('feeding_duration') }}">
                        @error('feeding_duration')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="feeding_problems" class="form-label">Feeding Problems</label>
                        <textarea id="feeding_problems" name="feeding_problems" class="form-control @error('feeding_problems') is-invalid @enderror" rows="2">{{ old('feeding_problems') }}</textarea>
                        @error('feeding_problems')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="urinary_output" class="form-label">Urinary Output</label>
                        <input type="text" id="urinary_output" name="urinary_output" class="form-control @error('urinary_output') is-invalid @enderror" value="{{ old('urinary_output') }}">
                        @error('urinary_output')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="stool_output" class="form-label">Stool Output</label>
                        <input type="text" id="stool_output" name="stool_output" class="form-control @error('stool_output') is-invalid @enderror" value="{{ old('stool_output') }}">
                        @error('stool_output')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="stool_characteristics" class="form-label">Stool Characteristics</label>
                        <input type="text" id="stool_characteristics" name="stool_characteristics" class="form-control @error('stool_characteristics') is-invalid @enderror" value="{{ old('stool_characteristics') }}">
                        @error('stool_characteristics')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="elimination_problems" class="form-label">Elimination Problems</label>
                        <textarea id="elimination_problems" name="elimination_problems" class="form-control @error('elimination_problems') is-invalid @enderror" rows="2">{{ old('elimination_problems') }}</textarea>
                        @error('elimination_problems')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Neurological Assessment</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="responsiveness" class="form-label">Responsiveness</label>
                        <select id="responsiveness" name="responsiveness" class="form-select @error('responsiveness') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="good" {{ old('responsiveness') == 'good' ? 'selected' : '' }}>Good</option>
                            <option value="fair" {{ old('responsiveness') == 'fair' ? 'selected' : '' }}>Fair</option>
                            <option value="poor" {{ old('responsiveness') == 'poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('responsiveness')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="cry_quality" class="form-label">Cry Quality</label>
                        <select id="cry_quality" name="cry_quality" class="form-select @error('cry_quality') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="strong" {{ old('cry_quality') == 'strong' ? 'selected' : '' }}>Strong</option>
                            <option value="weak" {{ old('cry_quality') == 'weak' ? 'selected' : '' }}>Weak</option>
                            <option value="normal" {{ old('cry_quality') == 'normal' ? 'selected' : '' }}>Normal</option>
                        </select>
                        @error('cry_quality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="muscle_tone" class="form-label">Muscle Tone</label>
                        <select id="muscle_tone" name="muscle_tone" class="form-select @error('muscle_tone') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="normal" {{ old('muscle_tone') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="increased" {{ old('muscle_tone') == 'increased' ? 'selected' : '' }}>Increased</option>
                            <option value="decreased" {{ old('muscle_tone') == 'decreased' ? 'selected' : '' }}>Decreased</option>
                        </select>
                        @error('muscle_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="reflex_assessment" class="form-label">Reflex Assessment</label>
                        <textarea id="reflex_assessment" name="reflex_assessment" class="form-control @error('reflex_assessment') is-invalid @enderror" rows="2">{{ old('reflex_assessment') }}</textarea>
                        @error('reflex_assessment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Immunizations & Screenings</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="immunizations_up_to_date" class="form-label">Immunizations Up to Date</label>
                        <div class="form-check">
                            <input type="checkbox" id="immunizations_up_to_date" name="immunizations_up_to_date" class="form-check-input @error('immunizations_up_to_date') is-invalid @enderror" value="1" {{ old('immunizations_up_to_date') ? 'checked' : '' }}>
                            <label for="immunizations_up_to_date" class="form-check-label">Yes</label>
                        </div>
                        @error('immunizations_up_to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="newborn_screening_done" class="form-label">Newborn Screening Done</label>
                        <div class="form-check">
                            <input type="checkbox" id="newborn_screening_done" name="newborn_screening_done" class="form-check-input @error('newborn_screening_done') is-invalid @enderror" value="1" {{ old('newborn_screening_done') ? 'checked' : '' }}>
                            <label for="newborn_screening_done" class="form-check-label">Yes</label>
                        </div>
                        @error('newborn_screening_done')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="hearing_screening_done" class="form-label">Hearing Screening Done</label>
                        <div class="form-check">
                            <input type="checkbox" id="hearing_screening_done" name="hearing_screening_done" class="form-check-input @error('hearing_screening_done') is-invalid @enderror" value="1" {{ old('hearing_screening_done') ? 'checked' : '' }}>
                            <label for="hearing_screening_done" class="form-check-label">Yes</label>
                        </div>
                        @error('hearing_screening_done')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="hearing_screening_results" class="form-label">Hearing Screening Results</label>
                        <input type="text" id="hearing_screening_results" name="hearing_screening_results" class="form-control @error('hearing_screening_results') is-invalid @enderror" value="{{ old('hearing_screening_results') }}">
                        @error('hearing_screening_results')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="immunizations_given" class="form-label">Immunizations Given</label>
                        <textarea id="immunizations_given" name="immunizations_given" class="form-control @error('immunizations_given') is-invalid @enderror" rows="2">{{ old('immunizations_given') }}</textarea>
                        @error('immunizations_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="immunizations_planned" class="form-label">Immunizations Planned</label>
                        <textarea id="immunizations_planned" name="immunizations_planned" class="form-control @error('immunizations_planned') is-invalid @enderror" rows="2">{{ old('immunizations_planned') }}</textarea>
                        @error('immunizations_planned')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="newborn_screening_results" class="form-label">Newborn Screening Results</label>
                        <textarea id="newborn_screening_results" name="newborn_screening_results" class="form-control @error('newborn_screening_results') is-invalid @enderror" rows="2">{{ old('newborn_screening_results') }}</textarea>
                        @error('newborn_screening_results')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Development & Concerns</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="developmental_milestones" class="form-label">Developmental Milestones</label>
                        <textarea id="developmental_milestones" name="developmental_milestones" class="form-control @error('developmental_milestones') is-invalid @enderror" rows="3">{{ old('developmental_milestones') }}</textarea>
                        @error('developmental_milestones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="developmental_concerns" class="form-label">Developmental Concerns</label>
                        <textarea id="developmental_concerns" name="developmental_concerns" class="form-control @error('developmental_concerns') is-invalid @enderror" rows="3">{{ old('developmental_concerns') }}</textarea>
                        @error('developmental_concerns')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mother_recovery_status" class="form-label">Mother Recovery Status</label>
                        <textarea id="mother_recovery_status" name="mother_recovery_status" class="form-control @error('mother_recovery_status') is-invalid @enderror" rows="2">{{ old('mother_recovery_status') }}</textarea>
                        @error('mother_recovery_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mother_emotional_wellbeing" class="form-label">Mother Emotional Wellbeing</label>
                        <textarea id="mother_emotional_wellbeing" name="mother_emotional_wellbeing" class="form-control @error('mother_emotional_wellbeing') is-invalid @enderror" rows="2">{{ old('mother_emotional_wellbeing') }}</textarea>
                        @error('mother_emotional_wellbeing')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mother_breastfeeding_support" class="form-label">Mother Breastfeeding Support</label>
                        <textarea id="mother_breastfeeding_support" name="mother_breastfeeding_support" class="form-control @error('mother_breastfeeding_support') is-invalid @enderror" rows="2">{{ old('mother_breastfeeding_support') }}</textarea>
                        @error('mother_breastfeeding_support')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="baby_concerns" class="form-label">Baby Concerns</label>
                        <textarea id="baby_concerns" name="baby_concerns" class="form-control @error('baby_concerns') is-invalid @enderror" rows="2">{{ old('baby_concerns') }}</textarea>
                        @error('baby_concerns')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="mother_concerns" class="form-label">Mother Concerns</label>
                        <textarea id="mother_concerns" name="mother_concerns" class="form-control @error('mother_concerns') is-invalid @enderror" rows="2">{{ old('mother_concerns') }}</textarea>
                        @error('mother_concerns')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="complications_identified" class="form-label">Complications Identified</label>
                        <textarea id="complications_identified" name="complications_identified" class="form-control @error('complications_identified') is-invalid @enderror" rows="2">{{ old('complications_identified') }}</textarea>
                        @error('complications_identified')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Counseling & Education</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="counseling_topics" class="form-label">Counseling Topics Covered</label>
                        <textarea id="counseling_topics" name="counseling_topics" class="form-control @error('counseling_topics') is-invalid @enderror" rows="3">{{ old('counseling_topics') }}</textarea>
                        @error('counseling_topics')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-2">
                        <label for="infant_care_advice_given" class="form-label">Infant Care Advice</label>
                        <div class="form-check">
                            <input type="checkbox" id="infant_care_advice_given" name="infant_care_advice_given" class="form-check-input @error('infant_care_advice_given') is-invalid @enderror" value="1" {{ old('infant_care_advice_given') ? 'checked' : '' }}>
                            <label for="infant_care_advice_given" class="form-check-label">Given</label>
                        </div>
                        @error('infant_care_advice_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-2">
                        <label for="feeding_guidance_given" class="form-label">Feeding Guidance</label>
                        <div class="form-check">
                            <input type="checkbox" id="feeding_guidance_given" name="feeding_guidance_given" class="form-check-input @error('feeding_guidance_given') is-invalid @enderror" value="1" {{ old('feeding_guidance_given') ? 'checked' : '' }}>
                            <label for="feeding_guidance_given" class="form-check-label">Given</label>
                        </div>
                        @error('feeding_guidance_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-2">
                        <label for="cord_care_advice_given" class="form-label">Cord Care Advice</label>
                        <div class="form-check">
                            <input type="checkbox" id="cord_care_advice_given" name="cord_care_advice_given" class="form-check-input @error('cord_care_advice_given') is-invalid @enderror" value="1" {{ old('cord_care_advice_given') ? 'checked' : '' }}>
                            <label for="cord_care_advice_given" class="form-check-label">Given</label>
                        </div>
                        @error('cord_care_advice_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="hygiene_safety_advice_given" class="form-label">Hygiene/Safety Advice</label>
                        <div class="form-check">
                            <input type="checkbox" id="hygiene_safety_advice_given" name="hygiene_safety_advice_given" class="form-check-input @error('hygiene_safety_advice_given') is-invalid @enderror" value="1" {{ old('hygiene_safety_advice_given') ? 'checked' : '' }}>
                            <label for="hygiene_safety_advice_given" class="form-check-label">Given</label>
                        </div>
                        @error('hygiene_safety_advice_given')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label for="danger_signs_explained" class="form-label">Danger Signs Explained</label>
                        <div class="form-check">
                            <input type="checkbox" id="danger_signs_explained" name="danger_signs_explained" class="form-check-input @error('danger_signs_explained') is-invalid @enderror" value="1" {{ old('danger_signs_explained') ? 'checked' : '' }}>
                            <label for="danger_signs_explained" class="form-check-label">Explained</label>
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
                        <label for="health_status" class="form-label">Health Status</label>
                        <select id="health_status" name="health_status" class="form-select @error('health_status') is-invalid @enderror" required>
                            <option value="">Choose...</option>
                            <option value="normal" {{ old('health_status') == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="at_risk" {{ old('health_status') == 'at_risk' ? 'selected' : '' }}>At Risk</option>
                            <option value="needs_referral" {{ old('health_status') == 'needs_referral' ? 'selected' : '' }}>Needs Referral</option>
                            <option value="referred" {{ old('health_status') == 'referred' ? 'selected' : '' }}>Referred</option>
                        </select>
                        @error('health_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="referral_reason" class="form-label">Referral Reason</label>
                        <textarea id="referral_reason" name="referral_reason" class="form-control @error('referral_reason') is-invalid @enderror" rows="2">{{ old('referral_reason') }}</textarea>
                        @error('referral_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="referral_destination" class="form-label">Referral Destination</label>
                        <input type="text" id="referral_destination" name="referral_destination" class="form-control @error('referral_destination') is-invalid @enderror" value="{{ old('referral_destination') }}">
                        @error('referral_destination')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="management_plan" class="form-label">Management Plan</label>
                        <textarea id="management_plan" name="management_plan" class="form-control @error('management_plan') is-invalid @enderror" rows="3">{{ old('management_plan') }}</textarea>
                        @error('management_plan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="next_follow_up_date" class="form-label">Next Follow-up Date</label>
                        <input type="date" id="next_follow_up_date" name="next_follow_up_date" class="form-control @error('next_follow_up_date') is-invalid @enderror" value="{{ old('next_follow_up_date') }}">
                        @error('next_follow_up_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-12">
                        <label for="next_follow_up_reason" class="form-label">Next Follow-up Reason</label>
                        <textarea id="next_follow_up_reason" name="next_follow_up_reason" class="form-control @error('next_follow_up_reason') is-invalid @enderror" rows="2">{{ old('next_follow_up_reason') }}</textarea>
                        @error('next_follow_up_reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Child Follow-up</button>
            <a href="{{ route('midwife.child-follow-up.index', $newborn) }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection