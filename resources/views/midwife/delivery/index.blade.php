@extends('layouts.app')

@section('title', 'Delivery Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-basket"></i> Delivery Management
            </h1>
            <small class="text-muted">Manage patient delivery records and newborn registrations</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($patients->isEmpty())
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No female patients of reproductive age (13-55 years) found in the system.
        </div>
    @else
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Patients Eligible for Delivery Management</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Hospital #</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Phone</th>
                                <th>Previous Delivery Records</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td>{{ $patient->hospital_number }}</td>
                                    <td>{{ $patient->full_name }}</td>
                                    <td>{{ now()->diffInYears($patient->demographic->date_of_birth) }} years</td>
                                    <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $patient->deliveries->count() }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('midwife.delivery.create', $patient) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-plus-circle"></i> New
                                            </a>
                                            @if($patient->deliveries->isNotEmpty())
                                                <a href="{{ route('midwife.delivery.patient-records', $patient) }}" class="btn btn-outline-info">
                                                    <i class="bi bi-file-earmark-text"></i> Records
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">Total: <strong>{{ $patients->count() }}</strong> patients</div>
        </div>
    @endif
</div>
@endsection