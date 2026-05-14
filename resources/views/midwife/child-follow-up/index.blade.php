@extends('layouts.app')

@section('title', 'Child Follow-up Records')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Child Follow-up Records Registration</h1>

    

    @if($newborns->isEmpty())
        <div class="alert alert-info">No follow-up records for this newborn yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Birth Order</th>
                                <th>Gender</th>
                                <th>General Condition</th>
                                <th>Mother Name</th>
                                <th>Birth Date/Time</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newborns as $newborn)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $newborn->birth_order }}</td>
                                        <td>{{ ucwords($newborn->sex) }}</td>
                                        <td>{{ $newborn->general_condition }}</td>
                                        <td>{{ $newborn->patient->name() ?? 'N/A' }}</td>
                                        <td>{{ $newborn->birth_date_time?->format('M d, Y H:i') }}</td>
                                        <td>{{ $newborn->recordedBy->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('midwife.child-follow-up.create', $newborn) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-plus"></i> New</a>
                                        <a href="{{ route('midwife.child-follow-up.record', $newborn) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i> Record</a>
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