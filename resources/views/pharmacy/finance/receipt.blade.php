@extends('layouts.app')

@section('title', 'Pharmacy Receipt')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <a href="{{ route('pharmacy.finance.payments') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Payments
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer me-1"></i> Print
                </button>
            </div>

            <div class="card shadow-sm" id="pharmacy-receipt">
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Pharmacy Payment Receipt</h5>
                            <small>Receipt: {{ $payment->payment_id }}</small>
                        </div>
                        <i class="bi bi-capsule-pill fs-1"></i>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <div class="text-muted small">Bill Number</div>
                            <div class="fw-semibold">{{ $transaction->bill?->bill_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Payment Method</div>
                            <div class="fw-semibold">{{ $payment->paymentMethod?->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Reference</div>
                            <div class="fw-semibold">{{ $payment->reference_number ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Date</div>
                            <div class="fw-semibold">{{ $payment->payment_date?->format('M d, Y h:i A') }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Collected By</div>
                            <div class="fw-semibold">{{ $payment->recordedBy?->name ?? 'System' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">Status</div>
                            <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Qty</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <tr>
                                        <td>{{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}</td>
                                        <td>{{ $item->medicineBatch?->batch_number ?? 'N/A' }}</td>
                                        <td class="text-end">&#8358;{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">&#8358;{{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Amount Paid</th>
                                    <th class="text-end">&#8358;{{ number_format($payment->amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="text-center text-muted small mt-4">
                        <p class="mb-1">This receipt is proof of pharmacy payment.</p>
                        <p class="mb-0">{{ config('app.title') }} | {{ config('app.address') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .d-print-none,
        .admin-sidebar,
        .hospital-navbar {
            display: none !important;
        }

        #pharmacy-receipt {
            box-shadow: none !important;
        }

        @page {
            size: A4 portrait;
            margin: 12mm;
        }
    }
</style>
@endsection
