@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Payment History - {{ $patient->name }}</h1>
            <p class="text-muted mb-0">Hospital #: {{ $patient->hospital_number }}</p>
        </div>
        <a href="{{ route('accountant.payments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">Payment Records</h5>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Payment ID</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Insurance Provider</th>
                                <th>Reference #</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td><strong>{{ $payment->payment_id }}</strong></td>
                                    <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ $payment->insurance_provider ?? '-' }}</td>
                                    <td>{{ $payment->reference_number ?? '-' }}</td>
                                    <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($payment->status === 'failed')
                                            <span class="badge bg-danger">Failed</span>
                                        @else
                                            <span class="badge bg-secondary">Reversed</span>
                                        @endif
                                    </td>
                                    <td>{{ $payment->recordedBy->name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Summary --}}
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <p class="text-muted small mb-1">Total Paid</p>
                        <h4 class="text-success">{{ number_format($payments->sum('amount'), 2) }}</h4>
                    </div>
                    <div class="col-md-3">
                        <p class="text-muted small mb-1">Number of Payments</p>
                        <h4>{{ $payments->total() }}</h4>
                    </div>
                </div>
            @else
                <p class="text-muted text-center py-4">No payments recorded for this patient yet.</p>
            @endif
        </div>
    </div>

    {{-- Pagination --}}
    @if($payments->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection
