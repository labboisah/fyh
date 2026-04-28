@extends('layouts.app')

@section('title', 'Labour Record - ' . $labour->patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-text"></i> Labour Record
            </h1>
            <small class="text-muted">{{ $labour->patient->full_name }} - {{ $labour->date_of_admission->format('M d, Y') }}</small>
        </div>
        <div class="col-md-4 text-end">
            @if(Auth::user()->hasAnyRole(['midwife', 'administrator']))
                <a href="{{ route('midwife.labour.edit', $labour) }}" class="btn btn-outline-warning btn-sm me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('midwife.labour.destroy', $labour) }}" method="POST" style="display:inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this record?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
                <a href="{{ route('midwife.labour.index') }}" class="btn btn-outline-secondary btn-sm ms-2">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            @endif
        </div>
    </div>

    <!-- Patient Information Card -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0"><i class="bi bi-person-badge"></i> Patient Information</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <small class="form-text text-muted">Hospital #</small>
                    <p><strong>{{ $labour->patient->hospital_number }}</strong></p>
                </div>
                <div class="col-md-3">
                    <small class="form-text text-muted">Name</small>
                    <p><strong>{{ $labour->patient->full_name }}</strong></p>
                </div>
                <div class="col-md-3">
                    <small class="form-text text-muted">Age</small>
                    <p><strong>{{ now()->diffInYears($labour->patient->demographic->date_of_birth) }} years</strong></p>
                </div>
                <div class="col-md-3">
                    <small class="form-text text-muted">Gender</small>
                    <p><strong>{{ $labour->patient->demographic->gender }}</strong></p>
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
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-calendar"></i> Date of Admission</small>
                    <p><strong>{{ $labour->date_of_admission->format('M d, Y') }}</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-clock"></i> Time of Admission</small>
                    <p><strong>{{ $labour->time_of_admission ?? 'N/A' }}</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-diagram-3"></i> Type of Labour</small>
                    <p>
                        <strong>
                            @switch($labour->type_of_labour)
                                @case('spontaneous')
                                    Spontaneous
                                    @break
                                @case('induced')
                                    Induced
                                    @break
                                @case('augmented')
                                    Augmented
                                    @break
                                @default
                                    {{ $labour->type_of_labour }}
                            @endswitch
                        </strong>
                    </p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-list-ol"></i> Stage at Admission</small>
                    <p>
                        <span class="badge bg-info">
                            @switch($labour->stage_at_admission)
                                @case('first')
                                    First Stage
                                    @break
                                @case('second')
                                    Second Stage
                                    @break
                                @case('third')
                                    Third Stage
                                    @break
                                @case('fourth')
                                    Fourth Stage
                                    @break
                                @default
                                    {{ $labour->stage_at_admission }}
                            @endswitch
                        </span>
                    </p>
                </div>
                @if($labour->induction_reason)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-chat-left-text"></i> Induction Reason</small>
                        <p><strong>{{ $labour->induction_reason }}</strong></p>
                    </div>
                @endif
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
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-expand"></i> Cervical Dilation</small>
                    <p><strong>{{ $labour->cervical_dilation ?? 'N/A' }} cm</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-percent"></i> Cervical Effacement</small>
                    <p><strong>{{ $labour->cervical_effacement ?? 'N/A' }}%</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-circle-fill"></i> Cervical Consistency</small>
                    <p><strong>{{ ucfirst($labour->cervical_consistency) ?? 'N/A' }}</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-arrow-up-down"></i> Cervical Position</small>
                    <p><strong>{{ ucfirst($labour->cervical_position) ?? 'N/A' }}</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-layers"></i> Cervical Application</small>
                    <p><strong>{{ ucfirst($labour->cervical_application) ?? 'N/A' }}</strong></p>
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
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-stopwatch"></i> Frequency</small>
                    <p><strong>{{ $labour->contraction_frequency ?? 'N/A' }} /10 min</strong></p>
                </div>
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-hourglass-split"></i> Duration</small>
                    <p><strong>{{ $labour->contraction_duration ?? 'N/A' }} seconds</strong></p>
                </div>
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-lightning"></i> Intensity</small>
                    <p><strong>{{ ucfirst($labour->contraction_intensity) ?? 'N/A' }}</strong></p>
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
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-diagram-2"></i> Fetal Position</small>
                    <p>
                        <strong>
                            @switch($labour->fetal_position)
                                @case('cephalic')
                                    Cephalic
                                    @break
                                @case('breech')
                                    Breech
                                    @break
                                @case('oblique')
                                    Oblique
                                    @break
                                @case('transverse')
                                    Transverse
                                    @break
                                @default
                                    {{ $labour->fetal_position }}
                            @endswitch
                        </strong>
                    </p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-arrow-down-up"></i> Fetal Descent</small>
                    <p><strong>{{ $labour->fetal_descent ?? 'N/A' }} (station)</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-heart-fill"></i> Fetal Heart Rate</small>
                    <p><strong>{{ $labour->fetal_heart_rate ?? 'N/A' }} bpm</strong></p>
                </div>
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-exclamation-triangle"></i> Meconium Staining</small>
                    <p>
                        <strong>
                            @if($labour->meconium_staining)
                                <span class="badge bg-warning">Present</span>
                            @else
                                <span class="badge bg-success">Absent</span>
                            @endif
                        </strong>
                    </p>
                </div>
                @if($labour->fetal_movements)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-arrow-repeat"></i> Fetal Movements</small>
                        <p><strong>{{ $labour->fetal_movements }}</strong></p>
                    </div>
                @endif
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
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-activity"></i> BP</small>
                    <p><strong>{{ $labour->systolic_bp ?? '-' }}/{{ $labour->diastolic_bp ?? '-' }} mmHg</strong></p>
                </div>
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-heart"></i> Pulse Rate</small>
                    <p><strong>{{ $labour->pulse_rate ?? 'N/A' }} bpm</strong></p>
                </div>
                <div class="col-md-4">
                    <small class="form-text text-muted"><i class="bi bi-thermometer"></i> Temperature</small>
                    <p><strong>{{ $labour->temperature ?? 'N/A' }}°C</strong></p>
                </div>
                <div class="col-md-12">
                    <small class="form-text text-muted"><i class="bi bi-wind"></i> Respiratory Rate</small>
                    <p><strong>{{ $labour->respiratory_rate ?? 'N/A' }} per minute</strong></p>
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
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-diagram-3"></i> Mode</small>
                    <p>
                        <strong>
                            @switch($labour->mode_of_delivery)
                                @case('vaginal')
                                    Vaginal
                                    @break
                                @case('assisted_vaginal')
                                    Assisted Vaginal
                                    @break
                                @case('caesarean')
                                    Caesarean Section
                                    @break
                                @default
                                    {{ $labour->mode_of_delivery }}
                            @endswitch
                        </strong>
                    </p>
                </div>
                @if($labour->assisted_delivery_type)
                    <div class="col-md-6">
                        <small class="form-text text-muted"><i class="bi bi-tools"></i> Assisted Delivery Type</small>
                        <p><strong>{{ ucfirst($labour->assisted_delivery_type) }}</strong></p>
                    </div>
                @endif
                @if($labour->indication_for_operative)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-chat-left-text"></i> Indication</small>
                        <p><strong>{{ $labour->indication_for_operative }}</strong></p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Perineal & Complications Card -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0"><i class="bi bi-exclamation-triangle"></i> Perineal & Complications</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <small class="form-text text-muted"><i class="bi bi-check-square"></i> Episiotomy</small>
                    <p>
                        <strong>
                            @if($labour->episiotomy_performed)
                                <span class="badge bg-warning">Performed</span>
                            @else
                                <span class="badge bg-success">Not Performed</span>
                            @endif
                        </strong>
                    </p>
                </div>
                @if($labour->episiotomy_type)
                    <div class="col-md-6">
                        <small class="form-text text-muted"><i class="bi bi-diagram-2"></i> Episiotomy Type</small>
                        <p><strong>{{ ucfirst($labour->episiotomy_type) }}</strong></p>
                    </div>
                @endif
                @if($labour->perineal_tear)
                    <div class="col-md-6">
                        <small class="form-text text-muted"><i class="bi bi-exclamation-diamond"></i> Perineal Tear</small>
                        <p>
                            <strong>
                                @switch($labour->perineal_tear)
                                    @case('none')
                                        <span class="badge bg-success">None</span>
                                        @break
                                    @case('first_degree')
                                        <span class="badge bg-info">First Degree</span>
                                        @break
                                    @case('second_degree')
                                        <span class="badge bg-warning">Second Degree</span>
                                        @break
                                    @case('third_degree')
                                        <span class="badge bg-danger">Third Degree</span>
                                        @break
                                    @case('fourth_degree')
                                        <span class="badge bg-danger">Fourth Degree</span>
                                        @break
                                @endswitch
                            </strong>
                        </p>
                    </div>
                @endif
                @if($labour->maternal_complications)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-exclamation-triangle"></i> Maternal Complications</small>
                        <p><strong>{{ $labour->maternal_complications }}</strong></p>
                    </div>
                @endif
                @if($labour->fetal_complications)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-exclamation-triangle"></i> Fetal Complications</small>
                        <p><strong>{{ $labour->fetal_complications }}</strong></p>
                    </div>
                @endif
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
                @if($labour->analgesia_given)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-capsule"></i> Analgesia Given</small>
                        <p><strong>{{ $labour->analgesia_given }}</strong></p>
                    </div>
                @endif
                @if($labour->augmentation_method)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-arrows-expand"></i> Augmentation Method</small>
                        <p><strong>{{ $labour->augmentation_method }}</strong></p>
                    </div>
                @endif
                @if($labour->management_given)
                    <div class="col-md-12">
                        <small class="form-text text-muted"><i class="bi bi-chat-left-text"></i> Overall Management & Outcome</small>
                        <p><strong>{{ $labour->management_given }}</strong></p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Clinical Notes Card -->
    @if($labour->clinical_notes)
        <div class="card mb-3">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="bi bi-file-earmark-text"></i> Clinical Notes</h6>
            </div>
            <div class="card-body">
                <p>{{ $labour->clinical_notes }}</p>
            </div>
        </div>
    @endif

    <!-- Record Metadata Sidebar -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <small class="form-text text-muted"><i class="bi bi-calendar"></i> Created Date</small>
                            <p><strong>{{ $labour->created_at->format('M d, Y H:i') }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="form-text text-muted"><i class="bi bi-arrow-repeat"></i> Last Updated</small>
                            <p><strong>{{ $labour->updated_at->format('M d, Y H:i') }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="form-text text-muted"><i class="bi bi-person"></i> Recorded By</small>
                            <p><strong>{{ $labour->recordedBy->name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="form-text text-muted"><i class="bi bi-hash"></i> Record ID</small>
                            <p><strong>{{ $labour->id }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
