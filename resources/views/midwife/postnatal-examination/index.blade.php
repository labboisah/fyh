@extends('layouts.app')

@section('title', 'Postnatal Examinations')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Postnatal Examinations Registration</h1>

    @if($deliveries->isEmpty())
        <div class="alert alert-info">No deliveries found with postnatal examinations.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Patient Name</th>
                                <th>Delivery Date</th>
                                <th>Delivery Type</th>
                                <th>Delivery Status</th>
                                <th>Delivery Summary</th>
                                <th>Delivered By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deliveries as $delivery)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $delivery->patient->name() ?? 'N/A' }}</td>
                                    <td>{{ optional($delivery->delivery_date_time)->format('M d, Y H:i') }}</td>
                                    <td>{{ $delivery->delivery_type ?? 'N/A' }}</td>
                                    <td>{{ $delivery->delivery_status ?? 'N/A' }}</td>
                                    <td>{{ $delivery->delivery_summary ?? 'N/A' }}</td>
                                    <td>{{ $delivery->deliveredBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('midwife.postnatal-examination.create', $delivery) }}" class="btn btn-sm btn-info"><i class="bi bi-plus-circle"></i> New</a>
                                        <a href="{{ route('midwife.postnatal-examination.record', $delivery) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Records</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('midwife.delivery.show', $delivery) }}" class="btn btn-outline-secondary mt-3">Back to Delivery</a>
</div>
@endsection