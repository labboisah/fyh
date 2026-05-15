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

    @if(count($labours) == 0)
         <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No completed labour records found in the system. Please ensure patients have completed labours to manage deliveries.
        </div>
    @else
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Labours Eligible for Delivery Management</h5>
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
                            @foreach($labours as $labour)
                                <tr>
                                    <td>{{ $labour->patient->hospital_number }}</td>
                                    <td>{{ $labour->patient->name() }}</td>
                                    <td>{{ $labour->patient->age() }} years</td>
                                    <td>{{ $labour->patient->demographic->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $labour->patient->deliveries->count() }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('midwife.delivery.create', $labour) }}" class="btn btn-outline-primary">
                                                <i class="bi bi-plus-circle"></i> New
                                            </a>
                                            @if($labour->patient->deliveries->isNotEmpty())
                                                <a href="{{ route('midwife.delivery.patient-records', $labour->patient) }}" class="btn btn-outline-info">
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
            <div class="card-footer text-muted">Total: <strong>{{ $labours->count() }}</strong> labours</div>
        </div>
    @endif
    <a href="{{ route('midwife.labour.index') }}" class="btn btn-outline-secondary mt-3">Back to Labour</a>

</div>
@endsection