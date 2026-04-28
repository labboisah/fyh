@extends('layouts.app')

@section('title', 'Postnatal Examinations')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Postnatal Examinations for {{ $delivery->patient->full_name }}</h1>

    <p><strong>Delivery Date/Time:</strong> {{ optional($delivery->delivery_date_time)->format('M d, Y H:i') }}</p>
    <p><strong>Delivery Type:</strong> {{ $delivery->delivery_type }}</p>

    <a href="{{ route('midwife.postnatal-examination.create', $delivery) }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Add Postnatal Examination</a>

    @if($delivery->postnatalExaminations->isEmpty())
        <div class="alert alert-info">No postnatal examinations recorded for this delivery yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Examination Date/Time</th>
                                <th>Hours Post Delivery</th>
                                <th>Recovery Status</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->postnatalExaminations as $examination)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $examination->examination_date_time?->format('M d, Y H:i') }}</td>
                                    <td>{{ $examination->hours_post_delivery }} hours</td>
                                    <td>
                                        @if($examination->recovery_status === 'normal')
                                            <span class="badge bg-success">Normal</span>
                                        @elseif($examination->recovery_status === 'needs_attention')
                                            <span class="badge bg-warning">Needs Attention</span>
                                        @else
                                            <span class="badge bg-danger">Needs Referral</span>
                                        @endif
                                    </td>
                                    <td>{{ $examination->recordedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('midwife.postnatal-examination.show', $examination) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('midwife.postnatal-examination.edit', $examination) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('midwife.postnatal-examination.destroy', $examination) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this postnatal examination?')">Delete</button>
                                        </form>
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