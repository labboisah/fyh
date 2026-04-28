@extends('layouts.app')

@section('title', 'Newborn Examinations')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Newborn Examinations for {{ $newborn->newborn_registration_number }}</h1>

    <p><strong>Mother:</strong> {{ $newborn->delivery->patient->full_name }}</p>
    <p><strong>Birth Date/Time:</strong> {{ optional($newborn->birth_date_time)->format('M d, Y H:i') }}</p>

    <a href="{{ route('midwife.newborn-examination.create', $newborn) }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Add Examination</a>

    @if($newborn->examinations->isEmpty())
        <div class="alert alert-info">No examinations recorded for this newborn yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Examination Date/Time</th>
                                <th>Hours After Birth</th>
                                <th>Status</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newborn->examinations as $examination)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $examination->examination_date_time?->format('M d, Y H:i') }}</td>
                                    <td>{{ $examination->hours_after_birth }} hours</td>
                                    <td>
                                        @if($examination->exam_status === 'normal')
                                            <span class="badge bg-success">Normal</span>
                                        @elseif($examination->exam_status === 'needs_follow_up')
                                            <span class="badge bg-warning">Needs Follow-up</span>
                                        @else
                                            <span class="badge bg-danger">Referral Needed</span>
                                        @endif
                                    </td>
                                    <td>{{ $examination->recordedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('midwife.newborn-examination.show', $examination) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('midwife.newborn-examination.edit', $examination) }}" class="btn btn-sm btn-warning">Edit</a>
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