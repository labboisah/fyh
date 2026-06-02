@extends('layouts.app')

@section('title', 'Investigations')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigations
    </h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Dashboard
    </a>
</div>
@endsection

@section('content')
    <div class="container">
        <table class="table table-striped datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Hospital Number</th>
                    <th>Investigation</th>
                    <th>Requested At</th>
                    <th>Requested By</th>
                    <th>Payment Status</th>
                    <th>Completed At</th>
                    <th>Performed By</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                
                @foreach (auth()->user()->department->investigationRequests() as $investigationRequest)
                    @if($investigationRequest->bill)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{ $investigationRequest->bill->patientName() ?? 'N/A' }}</td>
                        <td>{{ $investigationRequest->patientVisit->patient->hospital_number ?? 'Walk in Patient'}}</td>
                        <td>{{ $investigationRequest->investigation->name }}</td>
                        <td>{{$investigationRequest->requested_at}}</td>
                        <td>{{$investigationRequest->requestedBy->name}}</td>
                        <td>{{$investigationRequest->bill->status}}</td>
                        <td>{{$investigationRequest->completed_at}}</td>
                        <td>{{$investigationRequest->performedBy->name ?? 'N/A'}}</td>
                        <td class="text-end">
                            @if($investigationRequest->bill->status == 'paid')
                            <a href="{{route('radiology.requests.createResult', $investigationRequest)}}" class="btn btn-outline-primary"><i class="bi bi-save"></i> Save</a>
                            <!-- print button -->
                            @if($investigationRequest->completed_at)
                            <a href="{{route('radiology.requests.show', $investigationRequest)}}" class="btn btn-outline-success"><i class="bi bi-printer"></i>Print</a>
                            <!-- edit button -->
                            <a href="{{route('radiology.requests.editResult', $investigationRequest)}}" class="btn btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>
                            @endif
                            @else
                           <p class="text-muted"> No Payment Recorded</p>
                            @endif
                        </td>
                    </tr>
                    @endif
                @endforeach
                
            </tbody>
        </table>
    </div>
@endsection