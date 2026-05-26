@extends('layouts.app')

@section('content')
    @php 
    $bills = \App\Models\Bill::with('patientVisit','walkinPatient')->latest()->get();
    @endphp
    <div class="container">
        <h1>Bills Management</h1>
        <!-- Add your bill management content here -->
        @if(count($bills) == 0)
            <div class="alert alert-info">No bills found.</div>
        @else
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Bill Number</th>
                                    <th>Patient Name</th>
                                    <th>Service Description</th>
                                    <th>Total Amount</th>
                                    <th>Discount</th>
                                    <th>Due Amount</th>
                                    <th>Status</th>
                                    <th>Consistency</th>
                                    <th>Issued By</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $bill)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $bill->bill_number }}</td>
                                        <td>{{ $bill->patientName() ?? 'N/A' }}</td>
                                        <td>{{ $bill->service_description }}</td>
                                        <td>{{ number_format($bill->amount, 2) }}</td>
                                        <td>{{ number_format($bill->discount, 2) }}</td>
                                        <td>{{ number_format($bill->due_amount, 2) }}</td>
                                        
                                        <!-- consistency Status -->
                                        <td>
                                            @if($bill->status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($bill->status === 'partial')
                                                <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-danger">Unpaid</span>
                                            @endif
                                        <td>
                                            @if(!$bill->isAmountConsistent())
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Inconsistent
                                                </span>
                                            @else
                                                <span class="text-success">
                                                    <i class="fas fa-check-circle"></i> Consistent
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $bill->issuedBy->name ?? 'N/A' }}</td>
                                        <td>{{ $bill->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <!-- Add action buttons like view, edit, delete here -->
                                            <a href="{{ route('admin.bills.show', $bill) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                            <!-- You can add more actions like edit or delete if needed -->
                                             <a href="{{ route('admin.bills.edit', $bill) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                             <a href="{{ route('admin.bills.delete', $bill) }}" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection