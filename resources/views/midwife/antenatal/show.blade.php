@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Antenatal Care Record</h1>
            <p class="text-muted">
                {{ $antenatalCare->patient->demographic->first_name }} 
                {{ $antenatalCare->patient->demographic->last_name }} 
                | Hospital #{{ $antenatalCare->patient->hospital_number }}
            </p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                <a href="{{ route('midwife.antenatal.edit', $antenatalCare) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('midwife.antenatal.patient-records', $antenatalCare->patient) }}" class="btn btn-secondary">
                    <i class="bi bi-chevron-left"></i> Back
                </a>
            @endif
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- Patient Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Hospital Number</label>
                            <p class="fs-6">{{ $antenatalCare->patient->hospital_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Patient Name</label>
                            <p class="fs-6">{{ $antenatalCare->patient->demographic->first_name }} {{ $antenatalCare->patient->demographic->last_name }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label text-muted">Age</label>
                            <p class="fs-6">{{ now()->diffInYears($antenatalCare->patient->demographic->date_of_birth) }} years</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Gender</label>
                            <p class="fs-6">{{ $antenatalCare->patient->demographic->gender }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Contact</label>
                            <p class="fs-6">{{ $antenatalCare->patient->demographic->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pregnancy Details -->
            @if($antenatalCare->gestational_weeks || $antenatalCare->last_menstrual_period)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Pregnancy Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            @if($antenatalCare->last_menstrual_period)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Last Menstrual Period</label>
                                    <p class="fs-6">{{ $antenatalCare->last_menstrual_period->format('M d, Y') }}</p>
                                </div>
                            @endif
                            @if($antenatalCare->expected_delivery_date)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Expected Delivery Date</label>
                                    <p class="fs-6">
                                        {{ $antenatalCare->expected_delivery_date->format('M d, Y') }}
                                        @if($antenatalCare->isOverdue())
                                            <span class="badge bg-danger ms-2">OVERDUE</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="row mb-3">
                            @if($antenatalCare->gestational_weeks)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Gestational Weeks</label>
                                    <p class="fs-6"><strong>{{ $antenatalCare->gestational_weeks }} weeks</strong></p>
                                </div>
                            @endif
                            @if($antenatalCare->number_of_fetuses)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Number of Fetuses</label>
                                    <p class="fs-6">{{ $antenatalCare->number_of_fetuses }}</p>
                                </div>
                            @endif
                            @if($antenatalCare->pregnancy_type)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Pregnancy Type</label>
                                    <p class="fs-6">{{ $antenatalCare->pregnancy_type }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Vital Signs -->
            @if($antenatalCare->blood_pressure || $antenatalCare->weight || $antenatalCare->height)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-thermometer"></i> Vital Signs</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            @if($antenatalCare->blood_pressure)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Blood Pressure</label>
                                    <p class="fs-6">{{ $antenatalCare->blood_pressure }} mmHg</p>
                                </div>
                            @endif
                            @if($antenatalCare->weight)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Weight</label>
                                    <p class="fs-6">{{ $antenatalCare->weight }} kg</p>
                                </div>
                            @endif
                            @if($antenatalCare->height)
                                <div class="col-md-4">
                                    <label class="form-label text-muted">Height</label>
                                    <p class="fs-6">{{ $antenatalCare->height }} cm</p>
                                </div>
                            @endif
                        </div>
                        @if($antenatalCare->weight && $antenatalCare->height)
                            <div class="alert alert-info small">
                                <strong>BMI:</strong> {{ $antenatalCare->getBmi() }} kg/m²
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Physical Examination -->
            @if($antenatalCare->abdominal_examination || $antenatalCare->fundal_height || $antenatalCare->fetal_heart_rate)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Physical Examination</h5>
                    </div>
                    <div class="card-body">
                        @if($antenatalCare->abdominal_examination)
                            <div class="mb-3">
                                <label class="form-label text-muted">Abdominal Examination</label>
                                <p>{{ $antenatalCare->abdominal_examination }}</p>
                            </div>
                        @endif
                        <div class="row mb-3">
                            @if($antenatalCare->fundal_height)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Fundal Height</label>
                                    <p class="fs-6">{{ $antenatalCare->fundal_height }} cm</p>
                                </div>
                            @endif
                            @if($antenatalCare->fetal_heart_rate)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Fetal Heart Rate</label>
                                    <p class="fs-6">{{ $antenatalCare->fetal_heart_rate }} bpm</p>
                                </div>
                            @endif
                        </div>
                        @if($antenatalCare->fetal_movement)
                            <div class="mb-3">
                                <label class="form-label text-muted">Fetal Movement</label>
                                <p>{{ $antenatalCare->fetal_movement }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->vaginal_examination)
                            <div class="mb-3">
                                <label class="form-label text-muted">Vaginal Examination</label>
                                <p>{{ $antenatalCare->vaginal_examination }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Investigations -->
            @if($antenatalCare->urine_analysis || $antenatalCare->blood_tests || $antenatalCare->ultrasound_findings)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Investigations</h5>
                    </div>
                    <div class="card-body">
                        @if($antenatalCare->urine_analysis)
                            <div class="mb-3">
                                <label class="form-label text-muted">Urine Analysis</label>
                                <p>{{ $antenatalCare->urine_analysis }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->blood_tests)
                            <div class="mb-3">
                                <label class="form-label text-muted">Blood Tests</label>
                                <p>{{ $antenatalCare->blood_tests }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->ultrasound_findings)
                            <div class="mb-3">
                                <label class="form-label text-muted">Ultrasound Findings</label>
                                <p>{{ $antenatalCare->ultrasound_findings }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Risk Assessment -->
            @if($antenatalCare->risk_factors || $antenatalCare->complications || $antenatalCare->status)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Risk Assessment</h5>
                    </div>
                    <div class="card-body">
                        @if($antenatalCare->status)
                            <div class="mb-3">
                                <label class="form-label text-muted">Overall Status</label>
                                <p>
                                    @switch($antenatalCare->status)
                                        @case('normal')
                                            <span class="badge bg-success fs-6">Normal</span>
                                            @break
                                        @case('complicated')
                                            <span class="badge bg-warning fs-6">Complicated</span>
                                            @break
                                        @case('high_risk')
                                            <span class="badge bg-danger fs-6">High Risk</span>
                                            @break
                                    @endswitch
                                </p>
                            </div>
                        @endif
                        @if($antenatalCare->risk_factors)
                            <div class="mb-3">
                                <label class="form-label text-muted">Risk Factors</label>
                                <p>{{ $antenatalCare->risk_factors }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->complications)
                            <div class="mb-3">
                                <label class="form-label text-muted">Complications</label>
                                <p>{{ $antenatalCare->complications }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Management & Counseling -->
            @if($antenatalCare->management_plan || $antenatalCare->counseling_topics)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Management & Counseling</h5>
                    </div>
                    <div class="card-body">
                        @if($antenatalCare->management_plan)
                            <div class="mb-3">
                                <label class="form-label text-muted">Management Plan</label>
                                <p>{{ $antenatalCare->management_plan }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->counseling_topics)
                            <div class="mb-3">
                                <label class="form-label text-muted">Counseling Topics</label>
                                <p>{{ $antenatalCare->counseling_topics }}</p>
                            </div>
                        @endif
                        @if($antenatalCare->took_supplements)
                            <div class="alert alert-success small">
                                <i class="bi bi-check-circle"></i> Patient is taking supplements (Iron, Folic Acid, Vitamins)
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Clinical Notes -->
            @if($antenatalCare->clinical_notes)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bi bi-file-text"></i> Clinical Notes</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $antenatalCare->clinical_notes }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Record Metadata -->
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Record Details</h5>
                </div>
                <div class="card-body small">
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Created</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar"></i> {{ $antenatalCare->created_at->format('M d, Y H:i') }}
                        </p>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Last Updated</label>
                        <p class="mb-0">
                            <i class="bi bi-calendar"></i> {{ $antenatalCare->updated_at->format('M d, Y H:i') }}
                        </p>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Recorded By</label>
                        <p class="mb-0">
                            <i class="bi bi-person"></i> {{ $antenatalCare->recordedBy->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Record ID</label>
                        <p class="mb-0">
                            <code>{{ $antenatalCare->id }}</code>
                        </p>
                    </div>

                    @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                        <div class="d-grid gap-2">
                            <a href="{{ route('midwife.antenatal.edit', $antenatalCare) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i> Edit Record
                            </a>
                            <form action="{{ route('midwife.antenatal.destroy', $antenatalCare) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i> Delete Record
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
