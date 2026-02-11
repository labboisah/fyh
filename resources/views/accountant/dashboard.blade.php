
<div class="container-fluid">
    {{-- Financial Overview Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Bills Issued</p>
                            <h5 class="mb-0">0.00</h5>
                        </div>
                        <i class="bi bi-file-earmark-text text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Paid</p>
                            <h5 class="mb-0 text-success">0.00</h5>
                        </div>
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Pending Payment</p>
                            <h5 class="mb-0 text-warning">0.00</h5>
                        </div>
                        <i class="bi bi-hourglass-split text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Outstanding Bills</p>
                            <h5 class="mb-0 text-danger">0.00</h5>
                        </div>
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today & Monthly Revenue --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Today's Revenue</p>
                    <h4 class="text-success mb-0">0.00</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">This Month's Revenue</p>
                    <h4 class="text-success mb-0">0.00</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h4 class="text-success mb-0">0.00</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('accountant.bills.create') }}" class="btn btn-primary">
                <i class="bi bi-file-earmark-plus"></i> Create New Bill
            </a>
            <a href="{{ route('accountant.payments.create') }}" class="btn btn-success">
                <i class="bi bi-cash-coin"></i> Record Payment
            </a>
            <a href="{{ route('accountant.bills.index') }}" class="btn btn-info">
                <i class="bi bi-file-text"></i> View All Bills
            </a>
            <a href="{{ route('accountant.payments.index') }}" class="btn btn-info">
                <i class="bi bi-receipt"></i> View All Payments
            </a>
            <a href="{{ route('accountant.reports.financial') }}" class="btn btn-warning">
                <i class="bi bi-bar-chart"></i> Financial Report
            </a>
        </div>
    </div>

    <div class="row">
        {{-- Recent Payments --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Payments</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse(App\Models\Payment::latest()->limit(10)->get() as $payment)
                        <div class="pb-3 border-bottom last:border-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 fw-bold">{{ $payment->patient->name }}</p>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-receipt"></i> {{ $payment->payment_id }}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-calendar"></i> {{ $payment->payment_date->format('M d, Y') }}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <p class=4mb-1 fw-bold text-success">{{ number_format($payment->amount, 2) }}</p>
                                    <span class="badge bg-success">{{ ucfirst($payment->status) }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No payments recorded yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Bills --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recent Bills</h5>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse(App\Models\Bill::latest()->limit(10)->get() as $bill)
                        <div class="pb-3 border-bottom last:border-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1 fw-bold">{{ $bill->patient->name }}</p>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-file-earmark"></i> {{ $bill->bill_number }}
                                    </p>
                                    <p class="mb-0 text-muted small">{{ Str::limit($bill->service_description, 30) }}</p>
                                </div>
                                <div class="text-end">
                                4   <p class="mb-1 fw-bold">{{ number_format($bill->amount, 2) }}</p>
                                    <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'partial' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($bill->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center py-4">No bills created yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

