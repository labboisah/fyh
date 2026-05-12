@extends('layouts.app')

@section('title', 'Labour Management')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0">
                <i class="bi bi-hospital"></i> Labour Management
            </h1>
            <small class="text-muted">Manage patient labour records and monitor progress</small>
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

    @if(count($patients) == 0)
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No female patients of reproductive age (13-55 years) found in the system.
        </div>
    @else
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">Labour Management</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-hash"></i> Hospital #</th>
                                <th><i class="bi bi-person"></i> Name</th>
                                <th><i class="bi bi-calendar"></i> Age</th>
                                <th><i class="bi bi-telephone"></i> Phone</th>
                                <th><i class="bi bi-gender"></i> Gender</th>
                                <th><i class="bi bi-file-text"></i> Labour Records</th>
                                <th><i class="bi bi-clock-history"></i> Last Record</th>
                                <th><i class="bi bi-gear"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $patient->hospital_number }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $patient->name() }}</strong>
                                    </td>
                                    <td>
                                        {{ $patient->age() }} years
                                    </td>
                                    <td>
                                        {{ $patient->demographic->phone_number ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{$patient->demographic->gender ?? 'N/A'}}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $patient->labours->count() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($patient->labours->isNotEmpty())
                                            {{ $patient->labours->first()->admission->date }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($patient->payment()['pending'] == 0)
                                                <a href="{{ route('midwife.labour.create', $patient) }}"
                                                    class="btn btn-outline-primary"
                                                    title="Create new labour record">
                                                        <i class="bi bi-plus-circle"></i> New
                                                    </a>
                                            @else
                                            Patient has pending payment of <span class="badge bg-danger my-1">{{ number_format($patient->payment()['pending'], 2) }}</span> Naira
                                            @endif
                                            @if($patient->labours->isNotEmpty())
                                                <a href="{{ route('midwife.labour.patient-records', $patient) }}"
                                                   class="btn btn-outline-info"
                                                   title="View all labour records">
                                                    <i class="bi bi-file-earmark-text"></i> Records
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No eligible female patients found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-muted">
                Total: <strong>{{ count($patients) }}</strong> patients eligible for labour management
            </div>
        </div>
    @endif
</div>
@endsection
