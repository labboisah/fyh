@extends('layouts.app')

@section('title', 'Pharmacy Financial Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Pharmacy Financial Report</h1>
            <p class="text-muted mb-0">Bills, payments, and transaction totals for dispensed medicines.</p>
        </div>
        <a href="{{ route('pharmacy.finance.payments') }}" class="btn btn-outline-secondary">
            <i class="bi bi-credit-card-2-front me-1"></i> Payments
        </a>
    </div>

    <form method="GET" action="{{ route('pharmacy.finance.report') }}" class="card shadow-sm mb-3">
        <div class="card-body row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">From</label>
                <input type="date" name="from" value="{{ $from }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label">To</label>
                <input type="date" name="to" value="{{ $to }}" class="form-control">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Transactions</div>
                <div class="h4 mb-0">{{ number_format($transactions->count()) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Bills</div>
                <div class="h4 mb-0">&#8358;{{ number_format($transactions->sum(fn($transaction) => $transaction->bill?->due_amount ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Payments</div>
                <div class="h4 mb-0">&#8358;{{ number_format($transactions->sum(fn($transaction) => $transaction->payment?->amount ?? 0), 2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="border rounded bg-white p-3">
                <div class="text-muted small">Items Dispensed</div>
                <div class="h4 mb-0">{{ number_format($transactions->flatMap->stockTransactionItems->sum('quantity')) }}</div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-striped align-middle mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Bill</th>
                        <th>Receipt</th>
                        <th>Medicines</th>
                        <th>Collected By</th>
                        <th class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at?->format('M d, Y h:i A') }}</td>
                            <td>{{ $transaction->bill?->bill_number ?? 'N/A' }}</td>
                            <td>{{ $transaction->payment?->payment_id ?? 'N/A' }}</td>
                            <td>
                                @foreach($transaction->stockTransactionItems as $item)
                                    <div>
                                        {{ $item->medicineBatch?->medicine?->name ?? 'N/A' }}
                                        <span class="text-muted">x {{ $item->quantity }}</span>
                                    </div>
                                @endforeach
                            </td>
                            <td>{{ $transaction->createdBy?->name ?? 'System' }}</td>
                            <td class="text-end">&#8358;{{ number_format($transaction->payment?->amount ?? $transaction->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No pharmacy finance record found for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
