@extends('layouts.app')

@section('title', 'Patient List')

@section('header')
<div class="d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-list-ul text-success" style="font-size: 2rem;"></i>
        <div>
            <h1 class="h3 mb-1">All Patients</h1>
            <p class="mb-0 text-muted">Total Registered: <strong class="text-success">{{ $patients->total() }} patients</strong></p>
        </div>
    </div>
    <a href="{{ route('record_officer.patients.register.form') }}" class="btn btn-success">
        <i class="bi bi-plus-circle me-2"></i>Register New Patient
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-people-fill text-success me-2"></i>Registered Patients
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 datatable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash me-2"></i>Hospital Number</th>
                                <th><i class="bi bi-person me-2"></i>Patient Name</th>
                                <th><i class="bi bi-gender-ambiguous me-2"></i>Gender</th>
                                <th><i class="bi bi-telephone me-2"></i>Phone Number</th>
                                <th><i class="bi bi-calendar-check me-2"></i>Registration Date</th>
                                <th class="text-center"><i class="bi bi-tag me-2"></i>Status</th>
                                <th class="text-center no-export">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                                <tr class="align-middle">
                                    <td>
                                        <span class="badge bg-primary">{{ $patient->hospital_number }}</span>
                                    </td>
                                    <td class="fw-500">{{ $patient->demographic->full_name ?? 'N/A' }}</td>
                                    <td>{{ $patient->demographic->gender ?? 'N/A' }}</td>
                                    <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                    <td>{{ $patient->registration_date->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        @if($patient->is_walkIn)
                                            <span class="badge bg-warning text-dark"><i class="bi bi-person-check me-1"></i>Walk-In</span>
                                        @else
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Registered</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route('record_officer.patients.edit.form', $patient) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No patients registered yet.</p>
                                            <a href="{{ route('record_officer.patients.register.form') }}" class="btn btn-success btn-sm">
                                                <i class="bi bi-plus-circle me-1"></i>Register First Patient
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($patients->hasPages())
                <div class="card-footer bg-light">
                    {{ $patients->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
