@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Bills Management</h1>
        <div class="dropdown">
            <a class="btn btn-primary" href="{{ route('accountant.bills.create') }}">
                <i class="bi bi-plus-lg me-2"></i> New Bill
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Bills</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>Bill Number</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Issued Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bills as $bill)
                            <tr>
                                <td>
                                    <strong>{{ $bill->bill_number }}</strong>
                                </td>
                                <td>
                                    @if($bill->walkinPatient)
                                        {{ $bill->walkinPatient->name }}
                                        <br><small class="text-muted"><span class="badge bg-warning text-dark">Walk-In</span></small>
                                    @else
                                        {{ $bill->patientVisit->patient->name() ?? 'N/A' }}
                                    @endif
                                </td>
                                <td>{{ Str::limit($bill->service_description, 30) }}</td>
                                <td class="fw-bold">{{ number_format($bill->amount, 2) }}</td>
                                <td>{{ $bill->issued_date->format('M d, Y') }}</td>
                                <td>{{ $bill->due_date->format('M d, Y') }}</td>
                                <td>
                                    @if($bill->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($bill->status === 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @elseif($bill->status === 'pending')
                                        <span class="badge bg-danger">Pending</span>
                                    @else
                                        <span class="badge bg-secondary">Cancelled</span>
                                    @endif
                                </td>
                                <td class="no-export">
                                    <a href="{{ route('accountant.bills.show', $bill) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('accountant.bills.edit', $bill) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('accountant.bills.delete', $bill) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No bills found. <a href="{{ route('accountant.bills.create') }}">Create one now</a>
                                </td>
                            </tr>
                        @endforelse
                        <!-- total -->
                        <tr class="fw-bold">
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                            <td class="text-end"></td>
                            <td>{{ number_format($bills->sum('amount'), 2) }}</td>
                            <td colspan="4"></td>
                            <td colspan="4"></td>
                            <td colspan="4"></td>
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($bills->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $bills->links() }}
        </div>
    @endif
</div>
@endsection
