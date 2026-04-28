@extends('layouts.app')

@section('title', 'Child Follow-up Records')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-clipboard-check"></i> Child Follow-up Records for {{ $newborn->newborn_registration_number }}</h1>

    <p><strong>Mother:</strong> {{ $newborn->patient->full_name }}</p>
    <p><strong>Birth Date/Time:</strong> {{ optional($newborn->birth_date_time)->format('M d, Y H:i') }}</p>

    <a href="{{ route('midwife.child-follow-up.create', $newborn) }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Add Follow-up</a>

    @if($newborn->childFollowUps->isEmpty())
        <div class="alert alert-info">No follow-up records for this newborn yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Follow-up Date</th>
                                <th>Days of Life</th>
                                <th>Period</th>
                                <th>Location</th>
                                <th>Health Status</th>
                                <th>Weight</th>
                                <th>Recorded By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($newborn->childFollowUps as $followUp)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $followUp->follow_up_date_time?->format('M d, Y H:i') }}</td>
                                    <td>{{ $followUp->days_of_life }} days</td>
                                    <td>
                                        @switch($followUp->follow_up_period)
                                            @case('day_3')
                                                <span class="badge bg-info">Day 3</span>
                                                @break
                                            @case('day_7')
                                                <span class="badge bg-info">Day 7</span>
                                                @break
                                            @case('day_10')
                                                <span class="badge bg-info">Day 10</span>
                                                @break
                                            @case('day_14')
                                                <span class="badge bg-info">Day 14</span>
                                                @break
                                            @case('6weeks')
                                                <span class="badge bg-primary">6 Weeks</span>
                                                @break
                                            @case('3months')
                                                <span class="badge bg-primary">3 Months</span>
                                                @break
                                            @case('6months')
                                                <span class="badge bg-primary">6 Months</span>
                                                @break
                                            @case('year1')
                                                <span class="badge bg-success">1 Year</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @switch($followUp->location)
                                            @case('home')
                                                <span class="badge bg-light text-dark">Home</span>
                                                @break
                                            @case('clinic')
                                                <span class="badge bg-info">Clinic</span>
                                                @break
                                            @case('hospital')
                                                <span class="badge bg-warning">Hospital</span>
                                                @break
                                            @case('other')
                                                <span class="badge bg-secondary">Other</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($followUp->health_status === 'normal')
                                            <span class="badge bg-success">Normal</span>
                                        @elseif($followUp->health_status === 'at_risk')
                                            <span class="badge bg-warning">At Risk</span>
                                        @elseif($followUp->health_status === 'needs_referral')
                                            <span class="badge bg-danger">Needs Referral</span>
                                        @else
                                            <span class="badge bg-dark">Referred</span>
                                        @endif
                                    </td>
                                    <td>{{ $followUp->weight ? $followUp->weight . ' kg' : 'N/A' }}</td>
                                    <td>{{ $followUp->recordedBy->name ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('midwife.child-follow-up.show', $followUp) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('midwife.child-follow-up.edit', $followUp) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form method="POST" action="{{ route('midwife.child-follow-up.destroy', $followUp) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this follow-up record?')">Delete</button>
                                        </form>
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