@extends('layouts.app')

@section('title', 'Newborn Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3"><i class="bi bi-eye"></i> Newborn Details</h1>
            <p class="text-muted">Registration #: {{ $newborn->newborn_registration_number }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Delivery ID:</strong> {{ $newborn->delivery_id }}</p>
            <p><strong>Mother:</strong> {{ $newborn->delivery->patient->full_name }}</p>
            <p><strong>Sex:</strong> {{ ucfirst($newborn->sex) }}</p>
            <p><strong>Birth Order:</strong> {{ $newborn->birth_order }}</p>
            <p><strong>Birth Date/Time:</strong> {{ optional($newborn->birth_date_time)->format('M d, Y H:i') }}</p>
            <p><strong>Weight:</strong> {{ $newborn->birth_weight }} g</p>
            <p><strong>Length:</strong> {{ $newborn->birth_length }} cm</p>
            <p><strong>Head circumference:</strong> {{ $newborn->head_circumference }} cm</p>
            <p><strong>Apgar 1 minute:</strong> {{ $newborn->apgar_score_1_minute }}</p>
            <p><strong>Apgar 5 minutes:</strong> {{ $newborn->apgar_score_5_minutes }}</p>
            <p><strong>Status:</strong> {{ ucfirst($newborn->status) }}</p>
            <p><strong>Notes:</strong> {{ $newborn->delivery_notes }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('midwife.newborn.edit', $newborn) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('midwife.newborn.destroy', $newborn) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
            </form>
            <a href="{{ route('midwife.newborn-examination.index', $newborn) }}" class="btn btn-info btn-sm">Examinations</a>
            <a href="{{ route('midwife.child-follow-up.index', $newborn) }}" class="btn btn-success btn-sm">Child Follow-ups</a>
            <a href="{{ route('midwife.newborn.index', $newborn->delivery) }}" class="btn btn-outline-secondary btn-sm">Back</a>
        </div>
    </div>
</div>
@endsection