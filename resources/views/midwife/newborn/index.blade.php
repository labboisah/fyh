@extends('layouts.app')

@section('title', 'Newborn Management')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-baby-carriage"></i> Newborns for Delivery {{ $delivery->id }}</h1>

    <p><strong>Mother:</strong> {{ $delivery->patient->full_name }}</p>

    <a href="{{ route('midwife.newborn.create', $delivery) }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Add Newborn</a>

    @if($delivery->newborns->isEmpty())
        <div class="alert alert-info">No newborns registered for this delivery yet.</div>
    @else
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Registry #</th>
                                <th>Sex</th>
                                <th>Birth Weight (g)</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($delivery->newborns as $newborn)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $newborn->newborn_registration_number }}</td>
                                    <td>{{ ucfirst($newborn->sex) }}</td>
                                    <td>{{ $newborn->birth_weight }}</td>
                                    <td>{{ ucfirst($newborn->status) }}</td>
                                    <td>
                                        <a href="{{ route('midwife.newborn.show', $newborn) }}" class="btn btn-sm btn-info">View</a>
                                        <a href="{{ route('midwife.newborn.edit', $newborn) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <a href="{{ route('midwife.delivery.show', $delivery) }}" class="btn btn-outline-secondary mt-3">Back to Delivery</a>
</div>
@endsection