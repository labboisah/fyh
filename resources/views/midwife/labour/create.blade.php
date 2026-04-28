@extends('layouts.app')

@section('title', 'Create Labour Record - ' . $patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-plus-circle"></i> New Labour Record
            </h1>
            <small class="text-muted">Record labour admission for {{ $patient->full_name }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-9">
            <form action="{{ route('midwife.labour.store', $patient) }}" method="POST">
                @csrf

                <!-- Patient Information Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-person-badge"></i> Patient Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <small class="form-text text-muted">Hospital #</small>
                                <p><strong>{{ $patient->hospital_number }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <small class="form-text text-muted">Name</small>
                                <p><strong>{{ $patient->full_name }}</strong></p>
                            </div>
                            <div class="col-md-3">
                                <small class="form-text text-muted">Age</small>
                                <p><strong>{{ now()->diffInYears($patient->demographic->date_of_birth) }} years</strong></p>
                            </div>
                            <div class="col-md-3">
                                <small class="form-text text-muted">Gender</small>
                                <p><strong>{{ $patient->demographic->gender }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Labour Admission Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-calendar-event"></i> Labour Admission</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="date_of_admission" class="form-label">
                                    <i class="bi bi-calendar"></i> Date of Admission <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control @error('date_of_admission') is-invalid @enderror"
                                       id="date_of_admission" name="date_of_admission" required
                                       value="{{ old('date_of_admission', now()->format('Y-m-d')) }}">
                                @error('date_of_admission')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="time_of_admission" class="form-label">
                                    <i class="bi bi-clock"></i> Time of Admission
                                </label>
                                <input type="time" class="form-control @error('time_of_admission') is-invalid @enderror"
                                       id="time_of_admission" name="time_of_admission"
                                       value="{{ old('time_of_admission', now()->format('H:i')) }}">
                                @error('time_of_admission')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="type_of_labour" class="form-label">
                                    <i class="bi bi-diagram-3"></i> Type of Labour <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('type_of_labour') is-invalid @enderror"
                                        id="type_of_labour" name="type_of_labour" required>
                                    <option value="">-- Select Type --</option>
                                    <option value="spontaneous" {{ old('type_of_labour') == 'spontaneous' ? 'selected' : '' }}>
                                        Spontaneous
                                    </option>
                                    <option value="induced" {{ old('type_of_labour') == 'induced' ? 'selected' : '' }}>
                                        Induced
                                    </option>
                                    <option value="augmented" {{ old('type_of_labour') == 'augmented' ? 'selected' : '' }}>
                                        Augmented
                                    </option>
                                </select>
                                @error('type_of_labour')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stage_at_admission" class="form-label">
                                    <i class="bi bi-list-ol"></i> Stage at Admission <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('stage_at_admission') is-invalid @enderror"
                                        id="stage_at_admission" name="stage_at_admission" required>
                                    <option value="">-- Select Stage --</option>
                                    <option value="first" {{ old('stage_at_admission') == 'first' ? 'selected' : '' }}>
                                        First Stage
                                    </option>
                                    <option value="second" {{ old('stage_at_admission') == 'second' ? 'selected' : '' }}>
                                        Second Stage
                                    </option>
                                    <option value="third" {{ old('stage_at_admission') == 'third' ? 'selected' : '' }}>
                                        Third Stage
                                    </option>
                                    <option value="fourth" {{ old('stage_at_admission') == 'fourth' ? 'selected' : '' }}>
                                        Fourth Stage
                                    </option>
                                </select>
                                @error('stage_at_admission')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="induction_reason" class="form-label">
                                    <i class="bi bi-chat-left-text"></i> Induction Reason (if induced)
                                </label>
                                <textarea class="form-control @error('induction_reason') is-invalid @enderror"
                                          id="induction_reason" name="induction_reason" rows="2"
                                          placeholder="e.g., Post-term pregnancy...">{{ old('induction_reason') }}</textarea>
                                @error('induction_reason')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cervical Findings Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-thermometer"></i> Cervical Findings</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cervical_dilation" class="form-label">
                                    <i class="bi bi-expand"></i> Cervical Dilation (cm)
                                </label>
                                <input type="number" class="form-control @error('cervical_dilation') is-invalid @enderror"
                                       id="cervical_dilation" name="cervical_dilation" min="0" max="10"
                                       value="{{ old('cervical_dilation') }}"
                                       placeholder="0-10">
                                @error('cervical_dilation')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">0-10 cm</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cervical_effacement" class="form-label">
                                    <i class="bi bi-percent"></i> Cervical Effacement (%)
                                </label>
                                <input type="number" class="form-control @error('cervical_effacement') is-invalid @enderror"
                                       id="cervical_effacement" name="cervical_effacement" min="0" max="100"
                                       value="{{ old('cervical_effacement') }}"
                                       placeholder="0-100">
                                @error('cervical_effacement')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">0-100%</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cervical_consistency" class="form-label">
                                    <i class="bi bi-circle-fill"></i> Cervical Consistency
                                </label>
                                <select class="form-select @error('cervical_consistency') is-invalid @enderror"
                                        id="cervical_consistency" name="cervical_consistency">
                                    <option value="">-- Select Consistency --</option>
                                    <option value="firm" {{ old('cervical_consistency') == 'firm' ? 'selected' : '' }}>Firm</option>
                                    <option value="medium" {{ old('cervical_consistency') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="soft" {{ old('cervical_consistency') == 'soft' ? 'selected' : '' }}>Soft</option>
                                </select>
                                @error('cervical_consistency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cervical_position" class="form-label">
                                    <i class="bi bi-arrow-up-down"></i> Cervical Position
                                </label>
                                <select class="form-select @error('cervical_position') is-invalid @enderror"
                                        id="cervical_position" name="cervical_position">
                                    <option value="">-- Select Position --</option>
                                    <option value="posterior" {{ old('cervical_position') == 'posterior' ? 'selected' : '' }}>Posterior</option>
                                    <option value="middle" {{ old('cervical_position') == 'middle' ? 'selected' : '' }}>Middle</option>
                                    <option value="anterior" {{ old('cervical_position') == 'anterior' ? 'selected' : '' }}>Anterior</option>
                                </select>
                                @error('cervical_position')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="cervical_application" class="form-label">
                                    <i class="bi bi-layers"></i> Cervical Application
                                </label>
                                <select class="form-select @error('cervical_application') is-invalid @enderror"
                                        id="cervical_application" name="cervical_application">
                                    <option value="">-- Select Application --</option>
                                    <option value="unpadded" {{ old('cervical_application') == 'unpadded' ? 'selected' : '' }}>Unpadded</option>
                                    <option value="padded" {{ old('cervical_application') == 'padded' ? 'selected' : '' }}>Padded</option>
                                </select>
                                @error('cervical_application')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uterine Contractions Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-graph-up"></i> Uterine Contractions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="contraction_frequency" class="form-label">
                                    <i class="bi bi-stopwatch"></i> Frequency (per 10 min)
                                </label>
                                <input type="number" class="form-control @error('contraction_frequency') is-invalid @enderror"
                                       id="contraction_frequency" name="contraction_frequency" min="0" max="10"
                                       value="{{ old('contraction_frequency') }}"
                                       placeholder="e.g., 3">
                                @error('contraction_frequency')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="contraction_duration" class="form-label">
                                    <i class="bi bi-hourglass-split"></i> Duration (seconds)
                                </label>
                                <input type="number" class="form-control @error('contraction_duration') is-invalid @enderror"
                                       id="contraction_duration" name="contraction_duration" min="0" max="120"
                                       value="{{ old('contraction_duration') }}"
                                       placeholder="e.g., 60">
                                @error('contraction_duration')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="contraction_intensity" class="form-label">
                                    <i class="bi bi-lightning"></i> Intensity
                                </label>
                                <select class="form-select @error('contraction_intensity') is-invalid @enderror"
                                        id="contraction_intensity" name="contraction_intensity">
                                    <option value="">-- Select Intensity --</option>
                                    <option value="mild" {{ old('contraction_intensity') == 'mild' ? 'selected' : '' }}>Mild</option>
                                    <option value="moderate" {{ old('contraction_intensity') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                    <option value="strong" {{ old('contraction_intensity') == 'strong' ? 'selected' : '' }}>Strong</option>
                                </select>
                                @error('contraction_intensity')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fetal Status Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-heart-pulse"></i> Fetal Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fetal_position" class="form-label">
                                    <i class="bi bi-diagram-2"></i> Fetal Position <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('fetal_position') is-invalid @enderror"
                                        id="fetal_position" name="fetal_position" required>
                                    <option value="">-- Select Position --</option>
                                    <option value="cephalic" {{ old('fetal_position') == 'cephalic' ? 'selected' : '' }}>Cephalic</option>
                                    <option value="breech" {{ old('fetal_position') == 'breech' ? 'selected' : '' }}>Breech</option>
                                    <option value="oblique" {{ old('fetal_position') == 'oblique' ? 'selected' : '' }}>Oblique</option>
                                    <option value="transverse" {{ old('fetal_position') == 'transverse' ? 'selected' : '' }}>Transverse</option>
                                </select>
                                @error('fetal_position')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fetal_descent" class="form-label">
                                    <i class="bi bi-arrow-down-up"></i> Fetal Descent (station)
                                </label>
                                <input type="number" class="form-control @error('fetal_descent') is-invalid @enderror"
                                       id="fetal_descent" name="fetal_descent" min="-5" max="5"
                                       value="{{ old('fetal_descent') }}"
                                       placeholder="e.g., 0">
                                @error('fetal_descent')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">-5 to +5 (0 = station)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="fetal_heart_rate" class="form-label">
                                    <i class="bi bi-heart-fill"></i> Fetal Heart Rate (bpm)
                                </label>
                                <input type="number" class="form-control @error('fetal_heart_rate') is-invalid @enderror"
                                       id="fetal_heart_rate" name="fetal_heart_rate" min="100" max="160"
                                       value="{{ old('fetal_heart_rate') }}"
                                       placeholder="120-160">
                                @error('fetal_heart_rate')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">100-160 bpm</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="meconium_staining" class="form-label">
                                    <i class="bi bi-exclamation-triangle"></i> Meconium Staining
                                </label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="meconium_staining"
                                           name="meconium_staining" value="1" {{ old('meconium_staining') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="meconium_staining">
                                        Present
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="fetal_movements" class="form-label">
                                    <i class="bi bi-arrow-repeat"></i> Fetal Movements / Noted
                                </label>
                                <textarea class="form-control @error('fetal_movements') is-invalid @enderror"
                                          id="fetal_movements" name="fetal_movements" rows="2"
                                          placeholder="e.g., Good kicks, breathing movements...">{{ old('fetal_movements') }}</textarea>
                                @error('fetal_movements')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Maternal Vital Signs Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-graph-up"></i> Maternal Vital Signs</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="systolic_bp" class="form-label">
                                    <i class="bi bi-activity"></i> Systolic BP (mmHg)
                                </label>
                                <input type="number" class="form-control @error('systolic_bp') is-invalid @enderror"
                                       id="systolic_bp" name="systolic_bp" min="60" max="250"
                                       value="{{ old('systolic_bp') }}"
                                       placeholder="e.g., 120">
                                @error('systolic_bp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="diastolic_bp" class="form-label">
                                    <i class="bi bi-activity"></i> Diastolic BP (mmHg)
                                </label>
                                <input type="number" class="form-control @error('diastolic_bp') is-invalid @enderror"
                                       id="diastolic_bp" name="diastolic_bp" min="40" max="150"
                                       value="{{ old('diastolic_bp') }}"
                                       placeholder="e.g., 80">
                                @error('diastolic_bp')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pulse_rate" class="form-label">
                                    <i class="bi bi-heart"></i> Pulse Rate (bpm)
                                </label>
                                <input type="number" class="form-control @error('pulse_rate') is-invalid @enderror"
                                       id="pulse_rate" name="pulse_rate" min="40" max="150"
                                       value="{{ old('pulse_rate') }}"
                                       placeholder="60-100">
                                @error('pulse_rate')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="temperature" class="form-label">
                                    <i class="bi bi-thermometer"></i> Temperature (°C)
                                </label>
                                <input type="number" class="form-control @error('temperature') is-invalid @enderror"
                                       id="temperature" name="temperature" min="34" max="42" step="0.1"
                                       value="{{ old('temperature') }}"
                                       placeholder="36.5">
                                @error('temperature')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="respiratory_rate" class="form-label">
                                    <i class="bi bi-wind"></i> Respiratory Rate (per min)
                                </label>
                                <input type="number" class="form-control @error('respiratory_rate') is-invalid @enderror"
                                       id="respiratory_rate" name="respiratory_rate" min="10" max="40"
                                       value="{{ old('respiratory_rate') }}"
                                       placeholder="16-20">
                                @error('respiratory_rate')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mode of Delivery Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-diagram-3"></i> Mode of Delivery</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="mode_of_delivery" class="form-label">
                                    <i class="bi bi-diagram-3"></i> Mode <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('mode_of_delivery') is-invalid @enderror"
                                        id="mode_of_delivery" name="mode_of_delivery" required>
                                    <option value="">-- Select Mode --</option>
                                    <option value="vaginal" {{ old('mode_of_delivery') == 'vaginal' ? 'selected' : '' }}>Vaginal</option>
                                    <option value="assisted_vaginal" {{ old('mode_of_delivery') == 'assisted_vaginal' ? 'selected' : '' }}>Assisted Vaginal</option>
                                    <option value="caesarean" {{ old('mode_of_delivery') == 'caesarean' ? 'selected' : '' }}>Caesarean Section</option>
                                </select>
                                @error('mode_of_delivery')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="assisted_delivery_type" class="form-label">
                                    <i class="bi bi-tools"></i> Type (if assisted)
                                </label>
                                <select class="form-select @error('assisted_delivery_type') is-invalid @enderror"
                                        id="assisted_delivery_type" name="assisted_delivery_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="forceps" {{ old('assisted_delivery_type') == 'forceps' ? 'selected' : '' }}>Forceps</option>
                                    <option value="vacuum" {{ old('assisted_delivery_type') == 'vacuum' ? 'selected' : '' }}>Vacuum</option>
                                </select>
                                @error('assisted_delivery_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="indication_for_operative" class="form-label">
                                    <i class="bi bi-chat-left-text"></i> Indication for Operative Delivery
                                </label>
                                <textarea class="form-control @error('indication_for_operative') is-invalid @enderror"
                                          id="indication_for_operative" name="indication_for_operative" rows="2"
                                          placeholder="e.g., Prolonged second stage...">{{ old('indication_for_operative') }}</textarea>
                                @error('indication_for_operative')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Peritoneal & Complications Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-exclamation-triangle"></i> Perineal & Complications</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="episiotomy_performed" class="form-label">
                                    <i class="bi bi-check-square"></i> Episiotomy Performed
                                </label>
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="episiotomy_performed"
                                           name="episiotomy_performed" value="1" {{ old('episiotomy_performed') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="episiotomy_performed">
                                        Yes
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="episiotomy_type" class="form-label">
                                    <i class="bi bi-diagram-2"></i> Episiotomy Type
                                </label>
                                <select class="form-select @error('episiotomy_type') is-invalid @enderror"
                                        id="episiotomy_type" name="episiotomy_type">
                                    <option value="">-- Select Type --</option>
                                    <option value="midline" {{ old('episiotomy_type') == 'midline' ? 'selected' : '' }}>Midline</option>
                                    <option value="mediolateral" {{ old('episiotomy_type') == 'mediolateral' ? 'selected' : '' }}>Mediolateral</option>
                                </select>
                                @error('episiotomy_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="perineal_tear" class="form-label">
                                    <i class="bi bi-exclamation-diamond"></i> Perineal Tear
                                </label>
                                <select class="form-select @error('perineal_tear') is-invalid @enderror"
                                        id="perineal_tear" name="perineal_tear">
                                    <option value="">-- Select Degree --</option>
                                    <option value="none" {{ old('perineal_tear') == 'none' ? 'selected' : '' }}>None</option>
                                    <option value="first_degree" {{ old('perineal_tear') == 'first_degree' ? 'selected' : '' }}>First Degree</option>
                                    <option value="second_degree" {{ old('perineal_tear') == 'second_degree' ? 'selected' : '' }}>Second Degree</option>
                                    <option value="third_degree" {{ old('perineal_tear') == 'third_degree' ? 'selected' : '' }}>Third Degree</option>
                                    <option value="fourth_degree" {{ old('perineal_tear') == 'fourth_degree' ? 'selected' : '' }}>Fourth Degree</option>
                                </select>
                                @error('perineal_tear')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="maternal_complications" class="form-label">
                                    <i class="bi bi-exclamation-triangle"></i> Maternal Complications
                                </label>
                                <textarea class="form-control @error('maternal_complications') is-invalid @enderror"
                                          id="maternal_complications" name="maternal_complications" rows="2"
                                          placeholder="e.g., Postpartum hemorrhage, haematoma...">{{ old('maternal_complications') }}</textarea>
                                @error('maternal_complications')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="fetal_complications" class="form-label">
                                    <i class="bi bi-exclamation-triangle"></i> Fetal Complications
                                </label>
                                <textarea class="form-control @error('fetal_complications') is-invalid @enderror"
                                          id="fetal_complications" name="fetal_complications" rows="2"
                                          placeholder="e.g., Fetal distress, meconium aspiration...">{{ old('fetal_complications') }}</textarea>
                                @error('fetal_complications')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management & Treatment Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-prescription"></i> Management & Treatment</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="analgesia_given" class="form-label">
                                    <i class="bi bi-capsule"></i> Analgesia Given
                                </label>
                                <textarea class="form-control @error('analgesia_given') is-invalid @enderror"
                                          id="analgesia_given" name="analgesia_given" rows="2"
                                          placeholder="e.g., Pethedine 100mg IM, Epidural...">{{ old('analgesia_given') }}</textarea>
                                @error('analgesia_given')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="augmentation_method" class="form-label">
                                    <i class="bi bi-arrows-expand"></i> Augmentation Method (if applicable)
                                </label>
                                <textarea class="form-control @error('augmentation_method') is-invalid @enderror"
                                          id="augmentation_method" name="augmentation_method" rows="2"
                                          placeholder="e.g., Oxytocin infusion...">{{ old('augmentation_method') }}</textarea>
                                @error('augmentation_method')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="management_given" class="form-label">
                                    <i class="bi bi-chat-left-text"></i> Overall Management & Outcome
                                </label>
                                <textarea class="form-control @error('management_given') is-invalid @enderror"
                                          id="management_given" name="management_given" rows="3"
                                          placeholder="Summary of management and delivery outcome...">{{ old('management_given') }}</textarea>
                                @error('management_given')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinical Notes Card -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <h6 class="card-title mb-0"><i class="bi bi-file-earmark-text"></i> Clinical Notes</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="clinical_notes" class="form-label">
                                    <i class="bi bi-pencil"></i> Additional Notes
                                </label>
                                <textarea class="form-control @error('clinical_notes') is-invalid @enderror"
                                          id="clinical_notes" name="clinical_notes" rows="4"
                                          placeholder="Any additional clinical observations or notes...">{{ old('clinical_notes') }}</textarea>
                                @error('clinical_notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Create Labour Record
                    </button>
                    <a href="{{ route('midwife.labour.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Sidebar Reference Guide -->
        <div class="col-lg-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="bi bi-lightbulb"></i> Quick Reference</h6>
                </div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle"></i> Cervical Dilation</strong>
                        <hr class="my-2">
                        <small>
                            0-3 cm: Latent phase<br>
                            4-7 cm: Active phase<br>
                            8-10 cm: Transition phase
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle"></i> Fetal Position</strong>
                        <hr class="my-2">
                        <small>
                            Cephalic: Most common<br>
                            Breech: Extended or flexed<br>
                            Oblique: Can progress<br>
                            Transverse: May need CS
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle"></i> Normal BP</strong>
                        <hr class="my-2">
                        <small>
                            Systolic: 90-140 mmHg<br>
                            Diastolic: 60-90 mmHg<br>
                            Watch for ≥160/110
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle"></i> Fetal HR</strong>
                        <hr class="my-2">
                        <small>
                            Normal: 120-160 bpm<br>
                            <140: Tachycardia<br>
                            >160: Fetal distress
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong><i class="bi bi-info-circle"></i> Labour Stages</strong>
                        <hr class="my-2">
                        <small>
                            <strong>1st:</strong> 0-10cm dilation<br>
                            <strong>2nd:</strong> Pushing to delivery<br>
                            <strong>3rd:</strong> Placenta expulsion<br>
                            <strong>4th:</strong> 1-2 hours post delivery
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
