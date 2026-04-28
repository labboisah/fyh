@extends('layouts.app')

@section('title', 'Labour Records - ' . $patient->full_name)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-file-earmark-text"></i> Labour Records
            </h1>
            <small class="text-muted">Complete history for {{ $patient->full_name }}</small>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('midwife.labour.create', $patient) }}" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> New Record
            </a>
            <a href="{{ route('midwife.labour.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
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
                    <small class="form-text text-muted">Phone</small>
                    <p><strong>{{ $patient->demographic->phone_number ?? 'N/A' }}</strong></p>
                </div>
            </div>
        </div>
    </div>

    @if($labours->isEmpty())
        <div class="alert alert-info" role="alert">
            <i class="bi bi-info-circle"></i> No labour records found for this patient.
            <a href="{{ route('midwife.labour.create', $patient) }}" class="alert-link">Create the first record</a>
        </div>
    @else
        <!-- Labour Records Summary Table -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="bi bi-table"></i> Labour Records Summary</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-calendar"></i> Date</th>
                                <th><i class="bi bi-diagram-3"></i> Type</th>
                                <th><i class="bi bi-list-ol"></i> Stage</th>
                                <th><i class="bi bi-activity"></i> BP</th>
                                <th><i class="bi bi-heart-fill"></i> FHR</th>
                                <th><i class="bi bi-thermometer"></i> Temp</th>
                                <th><i class="bi bi-diagram-3"></i> Mode of Delivery</th>
                                <th><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($labours as $labour)
                                <tr>
                                    <td>
                                        <strong>{{ $labour->date_of_admission->format('M d, Y') }}</strong><br>
                                        <small class="text-muted">{{ $labour->time_of_admission ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
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
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            @switch($labour->stage_at_admission)
                                                @case('first')
                                                    1st Stage
                                                    @break
                                                @case('second')
                                                    2nd Stage
                                                    @break
                                                @case('third')
                                                    3rd Stage
                                                    @break
                                                @case('fourth')
                                                    4th Stage
                                                    @break
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        @if($labour->systolic_bp && $labour->diastolic_bp)
                                            <strong>{{ $labour->systolic_bp }}/{{ $labour->diastolic_bp }}</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($labour->fetal_heart_rate)
                                            <strong>{{ $labour->fetal_heart_rate }} bpm</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($labour->temperature)
                                            <strong>{{ $labour->temperature }}°C</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($labour->mode_of_delivery)
                                            <span class="badge bg-success">
                                                @switch($labour->mode_of_delivery)
                                                    @case('vaginal')
                                                        Vaginal
                                                        @break
                                                    @case('assisted_vaginal')
                                                        Assisted
                                                        @break
                                                    @case('caesarean')
                                                        Caesarean
                                                        @break
                                                @endswitch
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('midwife.labour.show', $labour) }}"
                                               class="btn btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('midwife.labour.edit', $labour) }}"
                                               class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('midwife.labour.destroy', $labour) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Labour Records Timeline -->
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0"><i class="bi bi-clock-history"></i> Labour Records Timeline</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($labours as $index => $labour)
                        <div class="timeline-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="timeline-marker">
                                <span class="badge bg-primary">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div class="timeline-content">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ $labour->date_of_admission->format('M d, Y') }}</strong>
                                                @if($labour->time_of_admission)
                                                    <small class="text-muted">at {{ $labour->time_of_admission }}</small>
                                                @endif
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <small class="text-muted">
                                                    <i class="bi bi-person"></i>
                                                    {{ $labour->recordedBy->name ?? 'Unknown' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <small class="text-muted">Type of Labour</small>
                                                <p class="mb-2">
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
                                                        @endswitch
                                                    </strong>
                                                </p>
                                            </div>

                                            <div class="col-md-3">
                                                <small class="text-muted">Stage at Admission</small>
                                                <p class="mb-2">
                                                    <span class="badge bg-secondary">
                                                        @switch($labour->stage_at_admission)
                                                            @case('first')
                                                                1st Stage
                                                                @break
                                                            @case('second')
                                                                2nd Stage
                                                                @break
                                                            @case('third')
                                                                3rd Stage
                                                                @break
                                                            @case('fourth')
                                                                4th Stage
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </p>
                                            </div>

                                            <div class="col-md-3">
                                                <small class="text-muted">Fetal Position</small>
                                                <p class="mb-2"><strong>{{ ucfirst($labour->fetal_position) }}</strong></p>
                                            </div>

                                            <div class="col-md-3">
                                                <small class="text-muted">Mode of Delivery</small>
                                                <p class="mb-2">
                                                    <span class="badge bg-success">
                                                        @switch($labour->mode_of_delivery)
                                                            @case('vaginal')
                                                                Vaginal
                                                                @break
                                                            @case('assisted_vaginal')
                                                                Assisted
                                                                @break
                                                            @case('caesarean')
                                                                Caesarean
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </p>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">Cervical Dilation</small>
                                                <p class="mb-2">
                                                    <strong>{{ $labour->cervical_dilation ?? 'N/A' }} cm</strong>
                                                </p>
                                            </div>

                                            <div class="col-md-6">
                                                <small class="text-muted">Vital Signs</small>
                                                <p class="mb-2">
                                                    <strong>
                                                        {{ $labour->systolic_bp ?? '-' }}/{{ $labour->diastolic_bp ?? '-' }} mmHg
                                                        | {{ $labour->fetal_heart_rate ?? '-' }} bpm
                                                    </strong>
                                                </p>
                                            </div>
                                        </div>

                                        @if($labour->maternal_complications || $labour->fetal_complications)
                                            <hr>
                                            <div class="alert alert-warning mb-0">
                                                @if($labour->maternal_complications)
                                                    <p class="mb-2">
                                                        <strong><i class="bi bi-exclamation-triangle"></i> Maternal Complications:</strong>
                                                        {{ $labour->maternal_complications }}
                                                    </p>
                                                @endif
                                                @if($labour->fetal_complications)
                                                    <p class="mb-0">
                                                        <strong><i class="bi bi-exclamation-triangle"></i> Fetal Complications:</strong>
                                                        {{ $labour->fetal_complications }}
                                                    </p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-light text-end">
                                        <a href="{{ route('midwife.labour.show', $labour) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> View Full Record
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .timeline {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }

    .timeline-marker {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-item:not(:last-child) .timeline-marker::after {
        content: '';
        position: absolute;
        top: 50px;
        left: 24px;
        width: 2px;
        height: 30px;
        background-color: #dee2e6;
    }
</style>
@endsection
