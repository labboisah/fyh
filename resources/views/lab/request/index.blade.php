@extends('layouts.app')

@section('header')
    <div class="d-flex justify-content-between align-items-center">
    <h1 class="h3 d-flex align-items-center mb-0">
        <i class="bi bi-clipboard2-data me-2 text-primary"></i>
        Manage Investigation Request
    </h1>
    
</div>
@endsection
@section('content')  
<div class="container"> 
    <div class="row">
        <div class="col-md-12">
           
                <div class="card-body shadow p-4">
                    <table class="table table-bordered table-striped datatable" data-ajax="{{ route('lab.requests.index') }}" data-order='[[8,"desc"]]' data-refresh="15000">
                        <thead>
                            <tr>
                                <th>Lab No</th>
                                <th>Request By</th>
                                <th>Patient Name</th>
                                <th>Investigation</th>
                                <th>Completed At</th>
                                <th>Performed By</th>
                                <th>Status</th>
                                <th>Clinical Notes</th>
                                <th>Requested At</th>
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