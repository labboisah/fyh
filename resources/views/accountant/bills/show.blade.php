@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Bill Details</h1>
                <div>
                    @if($bill->status !== 'paid')
                        <a href="{{ route('accountant.payments.create', $bill) }}" class="btn btn-success btn-sm">
                            <i class="bi bi-cash-coin"></i> Record Payment
                        </a>
                    @endif
                    <a href="{{ route('accountant.bills.edit', $bill) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('accountant.bills.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">{{ $bill->bill_number }}</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Bill Number</p>
                            <p class="fw-bold">{{ $bill->bill_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Status</p>
                            <p>
                                @if($bill->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($bill->status === 'partial')
                                    <span class="badge bg-warning">Partial</span>
                                @elseif($bill->status === 'pending')
                                    <span class="badge bg-danger">Pending</span>
                                @else
                                    <span class="badge bg-secondary">Cancelled</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Patient</p>
                            <p class="fw-bold">{{ $bill->patientVisit->patient->name }}</p>
                            <p class="text-muted small">Hospital #: {{ $bill->patientVisit->patient->hospital_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Issued By</p>
                            <p class="fw-bold">{{ $bill->issuedBy->name }}</p>
                            <p class="text-muted small">{{ $bill->issuedBy->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Issued Date</p>
                            <p class="fw-bold">{{ $bill->issued_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Due Date</p>
                            <p class="fw-bold">{{ $bill->due_date->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Amount Due</p>
                            <h4 class="text-primary">{{ number_format($bill->amount, 2) }}</h4>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Total Paid</p>
                            <h4 class="text-success">{{ number_format($bill->totalPaid(), 2) }}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Balance Due</p>
                            <h4 class="text-danger">{{ number_format($bill->balance, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Services Breakdown --}}
            @if($bill->services->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Services & Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Service/Item</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->services as $service)
                                        <tr>
                                            <td>
                                                <strong>{{ $service->name }}</strong><br>
                                                <small class="text-muted">{{ $service->description }}</small>
                                            </td>
                                            <td class="text-end">{{ number_format($service->pivot->unit_price, 2) }}</td>
                                            <td class="text-end">{{ $service->pivot->quantity }}</td>
                                            <td class="text-end fw-bold">{{ number_format($service->pivot->subtotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold border-top">
                                        <td colspan="3" class="text-end">Total:</td>
                                        <td class="text-end">{{ number_format($bill->amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Payments History --}}
            @if($bill->payments->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Payment History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment ID</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Date</th>
                                        <th>Recorded By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bill->payments as $payment)
                                        <tr>
                                            <td><strong>{{ $payment->payment_id }}</strong></td>
                                            <td>{{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->payment_method }}</td>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td>{{ $payment->recordedBy->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
