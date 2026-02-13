@extends('layouts.app')

@section('title', 'Patient Details - ' . ($patient->demographic->full_name ?? 'Unknown'))

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-person-vcard text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">{{ $patient->demographic->full_name ?? 'Patient Details' }}</h1>
        <p class="mb-0 text-muted">Hospital Number: <strong class="text-success">{{ $patient->hospital_number }}</strong></p>
    </div>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Patient Information Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Hospital Number</label>
                        <p class="h5"><span class="badge bg-primary">{{ $patient->hospital_number }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Full Name</label>
                        <p class="h5">{{ $patient->demographic->full_name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted">Gender</label>
                        <p class="h6">{{ $patient->demographic->gender ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Date of Birth</label>
                        <p class="h6">{{ $patient->demographic->date_of_birth ? $patient->demographic->date_of_birth->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Age</label>
                        <p class="h6">{{ $patient->demographic->age ?? 'N/A' }} years</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Marital Status</label>
                        <p class="h6">{{ $patient->demographic->marital_status ?? 'N/A' }}</p>
                    </div>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Occupation</label>
                        <p class="h6">{{ $patient->demographic->occupation ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">LGA</label>
                        <p class="h6">{{ $patient->demographic->lga ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone Number</label>
                        <p class="h6"><a href="tel:{{ $patient->demographic->phone_number }}">{{ $patient->demographic->phone_number ?? 'N/A' }}</a></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email</label>
                        <p class="h6"><a href="mailto:{{ $patient->demographic->email }}">{{ $patient->demographic->email ?? 'N/A' }}</a></p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Address</label>
                    <p class="h6">{{ $patient->demographic->address ?? 'N/A' }}</p>
                </div>

                
            </div>
        </div>

        <!-- Next of Kin Card -->
        @if($patient->nextOfKin)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Next of Kin Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Name</label>
                            <p class="h6">{{ $patient->nextOfKin->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Relationship</label>
                            <p class="h6">{{ $patient->nextOfKin->relationship ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Telephone</label>
                            <p class="h6"><a href="tel:{{ $patient->nextOfKin->telephone }}">{{ $patient->nextOfKin->telephone ?? 'N/A' }}</a></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Contact Address</label>
                            <p class="h6">{{ $patient->nextOfKin->contact_address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Workflow Progress Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-arrow-repeat me-2"></i>Previous Visits History</h5>
            </div>
            <div class="card-body">
                <div class="workflow-steps">
                    
                    @if($patient->visits()->where('status', 'completed')->exists())
                        @foreach($patient->visits()->where('status', 'completed')->limit(5)->latest()->get() as $visit)
                            <div class="step completed">
                                <div class="step-marker">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="step-label">Visit on {{ $visit->visit_date->format('M d, Y') }}
                                    <br>
                                    <small class="text-muted">Type: {{ $visit->visit_type }}</small>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="step text->warning">
                            <div class="step-marker">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="step-label ">No previous visits recorded</div>
                        </div>
                    @endif    
                </div>
            </div>
            <style>
                .workflow-steps {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }
                
                .step {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 0.75rem;
                    background: #f8f9fa;
                    border-radius: 6px;
                    border-left: 3px solid #ccc;
                }
                
                .step.completed {
                    background: #e8f8f5;
                    border-left-color: #27AE60;
                }
                
                .step-marker {
                    font-size: 1.2rem;
                    flex-shrink: 0;
                }
                
                .step.completed .step-marker {
                    color: #27AE60;
                }
                
                .step-label {
                    font-size: 0.9rem;
                    font-weight: 500;
                    color: #333;
                }
            </style>
        </div>
        <!-- Investigation Requests -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-vial me-2"></i>Investigation Requests</h5>
            </div>
            <div class="card-body">
                @if($patient->currentVisit()->investigationRequests->where('status', 'Pending')->count() > 0)
                    @foreach($patient->currentVisit()->investigationRequests->where('status', 'Pending') as $request)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>{{ $request->investigation->name }}</span>
                            @if($request->status === 'Pending')
                            <span class="badge bg-warning text-dark">{{ $request->status }}</span>
                            @else
                            <a href="{{ route('nurse.patients.investigations.show', $request) }}" class="btn btn-sm btn-outline-success">View Results</a>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No pending investigation requests.</p>
                @endif
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>
            </div>
            
            <div class="card-body">
                <div class="row">
                   
                @foreach($patient->currentVisit()->vitalSignsRequests->where('status', 'Pending') as $vitalSignRequest)
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.vitalsigns.create', $vitalSignRequest) }}" class="btn btn-outline-danger">
                            <i class="bi bi-hospital me-2"></i>Record Vital Signs
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-medical me-2"></i>Referrer Patient to Doctor
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.investigations.create', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-file-medical me-2"></i>Investigation Request
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.payments.create.form', $patient) }}" class="btn btn-outline-warning">
                            <i class="bi bi-cash-coin me-2"></i>Add Nursing Note
                        </a>
                    </div>
                </div>
                <div class="col-md-6">   
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-heart-pulse me-2"></i>Record Observations
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-heart-pulse me-2"></i>Record Drug Chart
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-heart-pulse me-2"></i>Generate Patient Care Report
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.history', $patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history me-2"></i>View Patient History
                        </a>
                    </div>
                </div>
            </div>
                <p class="text-muted small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Follow the workflow: Record Vital Signs → Add Nursing Note → Record Drug Chart → Record Observations → Generate Patient Care Report → Submit to Doctor.
                </p>
            </div>
        </div>

        <!-- Patient Status Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="bi bi-info-circle text-success me-2"></i>Status Information</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-muted">Registration Date</label>
                    <p class="h6">{{ $patient->registration_date->format('M d, Y \a\t H:i') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted">Patient Type</label>
                    <p>
                        @if($patient->is_walkIn)
                            <span class="badge bg-warning text-dark"><i class="bi bi-person-check me-1"></i>Walk-In Patient</span>
                        @else
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Scheduled Patient</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
