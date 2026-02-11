@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Financial Report</h1>
    </div>

    {{-- Date Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('accountant.reports.financial') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $startDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $endDate->format('Y-m-d') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('accountant.reports.financial.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Export CSV
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Total Revenue</p>
                    <h3 class="text-success">{{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Bills Generated</p>
                    <h3 class="text-primary">{{ number_format($totalBillsGenerated, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Outstanding Amount</p>
                    <h3 class="text-danger">{{ number_format($totalOutstanding, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">Collection Rate</p>
                    <h3 class="text-info">
                        {{ $totalBillsGenerated > 0 ? round(($totalRevenue / $totalBillsGenerated) * 100, 2) : 0 }}%
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Revenue by Payment Method --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Revenue by Payment Method</h5>
                </div>
                <div class="card-body">
                    @if($revenueByMethod->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment Method</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revenueByMethod as $method)
                                        <tr>
                                            <td>{{ $method->payment_method }}</td>
                                            <td class="text-end fw-bold">{{ number_format($method->total, 2) }}</td>
                                            <td class="text-end">{{ round(($method->total / $totalRevenue) * 100, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold border-top">
                                        <td>Total</td>
                                        <td class="text-end">{{ number_format($totalRevenue, 2) }}</td>
                                        <td class="text-end">100%</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No revenue data for this period</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Insurance Claims Summary --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Insurance Claims Summary</h5>
                </div>
                <div class="card-body">
                    @if($insuranceClaims->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Provider</th>
                                        <th>Method</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($insuranceClaims as $claim)
                                        <tr>
                                            <td><strong>{{ $claim->insurance_provider }}</strong></td>
                                            <td>{{ $claim->payment_method }}</td>
                                            <td class="text-end fw-bold">{{ number_format($claim->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No insurance claims for this period</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Top Patients by Revenue --}}
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Top 20 Patients by Revenue</h5>
                </div>
                <div class="card-body">
                    @if($revenueByPatient->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient Name</th>
                                        <th>Hospital Number</th>
                                        <th class="text-end">Total Paid</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revenueByPatient as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->patient->name }}</td>
                                            <td>{{ $item->patient->hospital_number }}</td>
                                            <td class="text-end fw-bold">{{ number_format($item->total, 2) }}</td>
                                            <td class="text-end">{{ round(($item->total / $totalRevenue) * 100, 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No patient revenue data for this period</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
