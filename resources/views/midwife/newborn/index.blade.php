@extends('layouts.app')

@section('title', 'Newborn Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0"><i class="bi bi-baby-carriage"></i> Newborns Babies</h1>
        <a href="{{ route('midwife.newborn-management') }}" class="btn btn-primary">
            <i class="bi bi-diagram-3"></i> Direct Newborn Entry
        </a>
    </div>


    @if($deliveries->isEmpty())
        <div class="alert alert-info">No deliveries found. Use direct maternity entry to record a newborn when there is no delivery record in this system.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Admission Date</th>
                                <th>Delivery Type</th>
                                <th>Delivery Status</th>
                                <th>Delivery Summary</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveries as $delivery)
                                <!-- delivery information -->
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $delivery->patient->name() }}</td>
                                    <td>{{ $delivery->patient->demographic->admission_date ?? 'N/A' }}</td>
                                    <td>{{ $delivery->type ?? 'N/A' }}</td>
                                    <td>{{ ucfirst($delivery->status ?? 'N/A') }}</td>
                                    <td>{{ $delivery->summary ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('midwife.newborn.create', $delivery) }}" class="btn btn-sm btn-info"><i class="bi bi-plus-circle"></i> New</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('midwife.delivery.index') }}" class="btn btn-outline-secondary mt-3">Back to Delivery</a>
</div>
@endsection
