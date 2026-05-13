@extends('layouts.app')

@section('title', 'Newborn Examinations')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Newborn Examinations Registration</h1>

    @if($newborns->isEmpty())
        <div class="alert alert-info">No examinations recorded for any newborn yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <!-- information about newborn  -->
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Mother Name</th>
                                <th>Gender</th>
                                <th>Birth Date</th>
                                <th>Birth Order</th>
                                <th>Birth Length</th>
                                <th>Birth Weight</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newborns as $newborn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $newborn->delivery->patient->name() }}</td>
                                    <td>{{ ucwords($newborn->sex) }}</td>
                                    <td>{{ $newborn->birth_date_time }}</td>
                                    <td>{{ $newborn->birth_order }}</td>
                                    <td>{{ $newborn->birth_length }}</td>
                                    <td>{{ $newborn->birth_weight }}</td>
                                    <td>{{ $newborn->status }}</td>
                                    <td>
                                        <a href="{{ route('midwife.newborn-examination.create', $newborn) }}" class="btn btn-sm btn-primary">Add Examination Record</a>
                                        <a href="{{ route('midwife.newborn-examination.record', $newborn) }}" class="btn btn-sm btn-info">View Examination Records</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('midwife.newborn.show', $newborn) }}" class="btn btn-outline-secondary mt-3">Back to Newborn</a>
</div>
@endsection
                                         