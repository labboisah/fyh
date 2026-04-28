@extends('layouts.app')

@section('title', 'Edit Newborn Record')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-pencil-square"></i> Edit Newborn Record</h1>

    <form action="{{ route('midwife.newborn.update', $newborn) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-4">
                <label for="sex" class="form-label">Sex</label>
                <select id="sex" name="sex" class="form-select @error('sex') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="male" {{ old('sex', $newborn->sex) == 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ old('sex', $newborn->sex) == 'female' ? 'selected' : '' }}>Female</option>
                </select>
                @error('sex')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="birth_weight" class="form-label">Birth Weight (g)</label>
                <input type="number" id="birth_weight" name="birth_weight" class="form-control @error('birth_weight') is-invalid @enderror" value="{{ old('birth_weight', $newborn->birth_weight) }}" min="500" max="6000" required>
                @error('birth_weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="birth_length" class="form-label">Birth Length (cm)</label>
                <input type="number" id="birth_length" name="birth_length" class="form-control @error('birth_length') is-invalid @enderror" value="{{ old('birth_length', $newborn->birth_length) }}" min="25" max="70" required>
                @error('birth_length')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="birth_order" class="form-label">Birth Order</label>
                <input type="number" id="birth_order" name="birth_order" class="form-control @error('birth_order') is-invalid @enderror" value="{{ old('birth_order', $newborn->birth_order) }}" min="1" max="8" required>
                @error('birth_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="alive" {{ old('status', $newborn->status) == 'alive' ? 'selected' : '' }}>Alive</option>
                    <option value="stillborn" {{ old('status', $newborn->status) == 'stillborn' ? 'selected' : '' }}>Stillborn</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="birth_date_time" class="form-label">Birth Date & Time</label>
                <input type="datetime-local" id="birth_date_time" name="birth_date_time" class="form-control @error('birth_date_time') is-invalid @enderror" value="{{ old('birth_date_time', $newborn->birth_date_time?->format('Y-m-d\TH:i')) }}" required>
                @error('birth_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="delivery_notes" class="form-label">Delivery Notes</label>
                <textarea id="delivery_notes" name="delivery_notes" rows="3" class="form-control @error('delivery_notes') is-invalid @enderror">{{ old('delivery_notes', $newborn->delivery_notes) }}</textarea>
                @error('delivery_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('midwife.newborn.show', $newborn) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection