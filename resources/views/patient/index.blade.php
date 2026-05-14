@extends('layouts.app')

@section('content')


    <div class="container">
        <h1>Patients</h1>
        <!-- Patient list content goes here -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Patient List</h5>
                    </div>
                    <div class="card-body">
                        @if($patients->count() > 0)
                            <table class="table table-hover datatable" id="patientsTable">
                                <thead>
                                    <tr>
                                        <th>Hospital #</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($patients as $patient)
                                        <tr>
                                            <td><span class="badge bg-primary">{{ $patient->hospital_number }}</span></td>
                                            <td>{{ $patient->demographic->full_name ?? 'N/A' }}</td>
                                            <td>{{ $patient->demographic->age ?? 'N/A' }}</td>
                                            <td>{{ $patient->demographic->phone_number ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('patient.show', $patient) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>View Profile
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning">No patients found.</div>
                        @endif
                    </div>
                </div>
            </div>
            {{ $patients->links() }}
    </div>
@endsection