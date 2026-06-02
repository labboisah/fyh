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
                <table class="table table-striped datatable" data-ajax="{{ route('accountant.bills.index') }}" data-order='[[6,"desc"]]' data-refresh="15000">
                    <thead>
                        <tr>
                            <th>Bill Number</th>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Due Amount</th>
                            <th>Issued Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DataTables will populate rows via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
