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
                    <table class="table table-bordered table-striped datatable" data-order='[[8,"desc"]]'>
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
                            @foreach ($requestGroups as $group)
                            <tr>
                                <td>{{ $group->lab_no ?? '' }}</td>
                                <td>{{ $group->requested_by }}</td>
                                <td>{{ $group->patient_name }}</td>
                                <td>{{ $group->investigations }}</td>
                                <td>{{ $group->completed_at }}</td>
                                <td>{{ $group->performed_by ?: 'N/A' }}</td>
                                <td>{{ $group->status }}</td>
                                <td>{{ $group->clinical_notes }}</td>
                                <td>{{ $group->requested_at }}</td>
                                <td>
                                    @if($group->group_id && $group->has_pending_results)
                                        <a href="{{ route('lab.requests.results.create', ['groupType' => $group->group_type, 'groupId' => $group->group_id]) }}" class="btn btn-sm btn-outline-success mb-1">
                                            <i class="bi bi-send me-1"></i> Send Combined Result
                                        </a>
                                    @elseif($group->has_completed_results)
                                        <a href="{{ route('lab.requests.results.show', ['groupType' => $group->group_type, 'groupId' => $group->group_id]) }}" class="btn btn-sm btn-outline-info mb-1">
                                            <i class="bi bi-eye me-1"></i> View Combined Results
                                        </a>
                                    @else
                                        {{ $group->payment_status }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
          
        </div>
    </div>
</div>

@endsection