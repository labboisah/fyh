@extends('layouts.app')

@section('title', 'Delivery Details')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-eye"></i> Delivery Details</h1>

    <div class="card mb-4">
        <div class="card-header">Delivery Record</div>
        <div class="card-body">
            <p><strong>Patient:</strong> {{ $delivery->patient->full_name }}</p>
            <p><strong>Delivery Date / Time:</strong> {{ optional($delivery->delivery_date_time)->format('M d, Y H:i') }}</p>
            <p><strong>Delivery Type:</strong> {{ ucfirst($delivery->delivery_type) }}</p>
            <p><strong>Uterine Tone:</strong> {{ ucfirst($delivery->delivery_tone) }}</p>
            <p><strong>Blood Loss (ml):</strong> {{ $delivery->blood_loss_ml }}</p>
            <p><strong>Perineal Tear:</strong> {{ $delivery->perineal_tear }}</p>
            <p><strong>Checked By:</strong> {{ $delivery->checked_by }}</p>
            <p><strong>Notes:</strong> {{ $delivery->notes }}</p>
            <p><strong>Status:</strong> {{ ucfirst($delivery->status) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('midwife.delivery.edit', $delivery) }}" class="btn btn-warning btn-sm">Edit</a>
            <form action="{{ route('midwife.delivery.destroy', $delivery) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
            <a href="{{ route('midwife.newborn.index', $delivery) }}" class="btn btn-info btn-sm">Newborns</a>
            <a href="{{ route('midwife.postnatal-examination.index', $delivery) }}" class="btn btn-success btn-sm">Postnatal Examinations</a>
        </div>
    </div>
</div>
@endsection