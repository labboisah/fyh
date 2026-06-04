@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">Antenatal Care Management</h1>
            <p class="text-muted">Manage antenatal care for female patients</p>
        </div>
        <div class="col-md-4 text-end">
            @if(auth()->user()->hasAnyRole(['midwife', 'administrator']))
                <a href="{{ route('midwife.antenatal.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-clockwise"></i> Refresh
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

    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Antenatal Care Patients</h5>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-info">{{ $requests->count() }} ANC Service Requests</span>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Hospital #</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Phone</th>
                                <th>Antenatal Records</th>
                                <th>Last Record</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $ANCRequest)
                            
                            @php
                                if($ANCRequest->patientVisit){
                                    $patient = $ANCRequest->patientVisit->patient;
                                }else{
                                    $patient = null;
                                } 
                            @endphp
                                @if($patient)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $patient->hospital_number }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $patient->name() ?? 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        {{ $patient->age() }} years
                                    </td>
                                    <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                    <td>
                                        {{ $patient->antenatalCares->count() }}
                                        @if($patient->antenatalCares->count() > 0)
                                            <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->latestAntenatalCare())
                                            <small class="text-muted">{{ $patient->latestAntenatalCare()->created_at->format('M d, Y') }}</small>
                                        @else
                                            <small class="text-muted text-secondary">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($patient->antenatalCares->count() > 0)
                                            @php
                                                $latestStatus = $patient->latestAntenatalCare()->status;
                                            @endphp
                                            @switch($latestStatus)
                                                @case('normal')
                                                    <span class="badge bg-success">Normal</span>
                                                    @break
                                                @case('complicated')
                                                    <span class="badge bg-warning">Complicated</span>
                                                    @break
                                                @case('high_risk')
                                                    <span class="badge bg-danger">High Risk</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">-</span>
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">New</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($patient->payment()['pending'] == 0)
                                                <a href="{{ route('midwife.antenatal.create', $patient) }}" class="btn btn-outline-primary" title="New Record">
                                                    <i class="bi bi-plus-circle"></i> New 
                                                </a>
                                                <a href="{{ route('midwife.antenatal.patient-records', $patient) }}" class="btn btn-outline-info" title="View Records">
                                                    <i class="bi bi-file-text"></i> Records
                                                </a>
                                                <a href="{{ route('midwife.patient.progress', $patient) }}" class="btn btn-outline-success" title="View Progress">
                                                   <i class="bi bi-graph-up"></i> Track Progress
                                                </a>
                                            @else
                                                <span class="text-muted">Payment not recorded</span>    
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @else
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="bi bi-exclamation-triangle"></i> No visit information available for this ANC request. reffer patient to record to register visit and ANC details.
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No female patients of reproductive age found in the system.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
