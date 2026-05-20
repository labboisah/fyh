@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigation Request
    </h1>
    
</div>
@endsection
@section('content')  
<div class="container"> 
    <div class="row">
        <div class="col-md-12">
           
                <div class="card-body shadow p-4">
                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>Lab No</th>
                                <th>Request By</th>
                                <th>Patient Name</th>
                                <th>Investigation</th>
                                <th>Completed At</th>
                                <th>Performed By</th>
                                <th>Status</th>
                                <th>Clinical Notes</th>
                                <th>Requested At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (auth()->user()->department->investigationRequests() as $investigationRequest)
                            
                            <tr>
                                <td>{{ $investigationRequest->lab_no ?? ''}}</td>
                                <td>{{ $investigationRequest->requestedBy->name }}</td>
                                @if($investigationRequest->patientVisit)
                                <td>{{ $investigationRequest->patientVisit->patient->demographic->full_name }}</td>
                                @else
                                <td>{{ strtoupper($investigationRequest->bill->walkinPatient->name) ?? 'Walkin Patient' }}</td>
                                @endif
                                <td>{{ $investigationRequest->investigation->name }}</td>
                                <td>{{ $investigationRequest->completed_at }}</td>
                                <td>{{ $investigationRequest->performedBy ? $investigationRequest->performedBy->name : 'N/A' }}</td>
                                <td>{{ $investigationRequest->status }}</span></td>
                                <td>{{ $investigationRequest->clinical_diagnoses }}</td>
                                <td>{{ $investigationRequest->created_at }}</td>
                                <td>
                                    @if($investigationRequest->payment_status == 'paid')
                                        @if($investigationRequest->status !== 'Completed')
                                        <a href="{{ route('lab.requests.results.create', $investigationRequest) }}" class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-send me-1"></i> Send Result
                                        </a>
                                        @else
                                        <a href="#" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-pencil me-1"></i> Edit Result
                                        </a>
                                        <a href="{{ route('lab.requests.results.show', $investigationRequest) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-pencil me-1"></i> View Result
                                        </a>
                                        @endif
                                    @else
                                        Payment not recorded
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <!-- More rows can be added here -->
                        </tbody>
                    </table>
                </div>
          
        </div>
    </div>
</div>

@endsection