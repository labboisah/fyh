@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Payment Receipt Header --}}
    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <strong>Payment Successful!</strong> Your payment has been recorded successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>

    {{-- Receipt --}}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm" id="receipt">
                {{-- Header --}}
                <div class="card-header bg-primary text-white p-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Payment Receipt</h5>
                            <small>Payment ID: <strong>{{ $payment->payment_id }}</strong></small>
                        </div>
                        <div class="col-auto text-end">
                            <i class="bi bi-receipt" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>

                {{-- Receipt Content --}}
                <div class="card-body p-4">
                    {{-- Patient Information --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Patient Name</p>
                            <p class="fw-bold">{{ strtoupper($payment->bill->patientVisit->patient->demographic->first_name) }} {{ strtoupper($payment->bill->patientVisit->patient->demographic->last_name) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Hospital Number</p>
                            <p class="fw-bold">{{ $payment->bill->patientVisit->patient->hospital_number }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Payment Details --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Payment Date</p>
                            <p class="fw-bold">{{ $payment->payment_date->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Payment Status</p>
                            <span class="badge bg-success fs-6">{{ ucfirst($payment->status) }}</span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Payment Method</p>
                            <p class="fw-bold">{{ $payment->payment_method }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Reference Number</p>
                            <p class="fw-bold">{{ $payment->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($payment->insurance_provider)
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted small mb-1">Insurance Provider</p>
                                <p class="fw-bold">{{ $payment->insurance_provider }}</p>
                            </div>
                        </div>
                    @endif

                    <hr>

                    {{-- Services/Items Paid For --}}
                    @if($payment->bill && $payment->bill->services->count() > 0)
                        <div class="mb-4">
                            <p class="text-muted small mb-2">Services & Items Paid For</p>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payment->bill->services as $service)
                                            <tr>
                                                <td>
                                                    <strong>{{ $service->name }}</strong><br>
                                                    <small class="text-muted">{{ $service->code }}</small>
                                                </td>
                                                <td class="text-end">{{ number_format($service->pivot->unit_price, 2) }}</td>
                                                <td class="text-end">{{ $service->pivot->quantity }}</td>
                                                <td class="text-end fw-bold">{{ number_format($service->pivot->subtotal, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>
                    @endif

                    {{-- Payment Amount Section --}}
                    <div class="row mb-4">
                        <div class="col-md-8 text-end">
                            <p class="text-muted small mb-1">Amount Paid</p>
                        </div>
                        <div class="col-md-4">
                            <h4 class="text-success mb-0">{{ number_format($payment->amount, 2) }}</h4>
                        </div>
                    </div>

                    {{-- Bill Status --}}
                    @if($payment->bill)
                        <div class="alert alert-info small mb-3">
                            <strong>Bill Status:</strong> 
                            {{ $payment->bill->balance > 0 ? 
                                'Partially Paid - Balance Due: ' . number_format($payment->bill->balance, 2) : 
                                'Fully Paid' }}
                        </div>
                    @endif

                    {{-- Footer Info --}}
                    <div class="text-center text-muted small mt-4 pt-3 border-top">
                        <p class="mb-1">This receipt is proof of payment. Please keep it for your records.</p>
                        <p class="mb-0">Recorded by: <strong>{{ $payment->recordedBy->name }}</strong></p>
                        <p class="mb-0">Receipt Date: <strong>{{ now()->format('M d, Y h:i A') }}</strong></p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card-footer bg-light p-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <button onclick="window.print()" class="btn btn-outline-primary w-100">
                                <i class="bi bi-printer"></i> Print Receipt
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('accountant.payments.index') }}" class="btn btn-primary w-100">
                                <i class="bi bi-arrow-left"></i> Back to Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Links --}}
            <div class="mt-3 text-center">
                @if($payment->bill)
                    <a href="{{ route('accountant.bills.show', $payment->bill) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-file-earmark"></i> View Full Bill
                    </a>
                @endif
                <a href="{{ route('accountant.patient-payment-history', $payment->bill->patientVisit->patient) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-history"></i> Payment History
                </a>
            </div>
        </div>
    </div>
</div>

<style media="print">
    .alert, .btn, .card-footer, .mt-3 {
        display: none;
    }
    
    #receipt {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
    
    body {
        background: white !important;
    }
</style>
@endsection
