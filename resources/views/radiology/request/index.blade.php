@extends('layouts.app')

@section('title', 'Investigations')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigations
    </h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Dashboard
    </a>
</div>
@endsection

@section('content')
    <div class="container">
        <table class="table table-striped datatable" data-ajax="{{ route('radiology.requests.index') }}" data-order='[[4,"desc"]]' data-refresh="15000">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient Name</th>
                    <th>Hospital Number</th>
                    <th>Investigation</th>
                    <th>Requested At</th>
                    <th>Requested By</th>
                    <th>Payment Status</th>
                    <th>Completed At</th>
                    <th>Performed By</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- DataTables will populate rows via AJAX -->
            </tbody>
        </table>
    </div>
@endsection