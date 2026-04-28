@extends('layouts.app')

@section('title', 'New Delivery Record')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4"><i class="bi bi-plus-square"></i> Register Delivery for {{ $patient->full_name }}</h1>

    <form action="{{ route('midwife.delivery.store', $patient) }}" method="POST">
        @csrf

        <div class="row g-3">
            <div class="col-md-4">
                <label for="delivery_date_time" class="form-label">Delivery Date & Time</label>
                <input type="datetime-local" id="delivery_date_time" name="delivery_date_time" class="form-control @error('delivery_date_time') is-invalid @enderror" value="{{ old('delivery_date_time') }}" required>
                @error('delivery_date_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="delivery_tone" class="form-label">Uterine Tone</label>
                <select id="delivery_tone" name="delivery_tone" class="form-select @error('delivery_tone') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="good" {{ old('delivery_tone') == 'good' ? 'selected' : '' }}>Good</option>
                    <option value="fair" {{ old('delivery_tone') == 'fair' ? 'selected' : '' }}>Fair</option>
                    <option value="poor" {{ old('delivery_tone') == 'poor' ? 'selected' : '' }}>Poor</option>
                </select>
                @error('delivery_tone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="delivery_type" class="form-label">Delivery Type</label>
                <select id="delivery_type" name="delivery_type" class="form-select @error('delivery_type') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="vaginal" {{ old('delivery_type')=='vaginal' ? 'selected' : '' }}>Vaginal</option>
                    <option value="cesarean" {{ old('delivery_type')=='cesarean' ? 'selected' : '' }}>Cesarean</option>
                    <option value="assisted" {{ old('delivery_type')=='assisted' ? 'selected' : '' }}>Assisted</option>
                </select>
                @error('delivery_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="blood_loss_ml" class="form-label">Blood Loss (ml)</label>
                <input type="number" id="blood_loss_ml" name="blood_loss_ml" class="form-control @error('blood_loss_ml') is-invalid @enderror" value="{{ old('blood_loss_ml') }}" min="0" max="5000" required>
                @error('blood_loss_ml')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="uterine_involution" class="form-label">Uterine Involution</label>
                <input type="text" id="uterine_involution" name="uterine_involution" class="form-control @error('uterine_involution') is-invalid @enderror" value="{{ old('uterine_involution') }}" required>
                @error('uterine_involution')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="perineal_tear" class="form-label">Perineal Tear</label>
                <select id="perineal_tear" name="perineal_tear" class="form-select @error('perineal_tear') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="none" {{ old('perineal_tear')=='none' ? 'selected' : '' }}>None</option>
                    <option value="1st degree" {{ old('perineal_tear')=='1st degree' ? 'selected' : '' }}>1st degree</option>
                    <option value="2nd degree" {{ old('perineal_tear')=='2nd degree' ? 'selected' : '' }}>2nd degree</option>
                    <option value="3rd degree" {{ old('perineal_tear')=='3rd degree' ? 'selected' : '' }}>3rd degree</option>
                    <option value="4th degree" {{ old('perineal_tear')=='4th degree' ? 'selected' : '' }}>4th degree</option>
                </select>
                @error('perineal_tear')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-3">
                <label for="checked_by" class="form-label">Checked By</label>
                <input type="text" id="checked_by" name="checked_by" class="form-control @error('checked_by') is-invalid @enderror" value="{{ old('checked_by') }}" required>
                @error('checked_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-4">
                <label for="delivery_status" class="form-label">Delivery Status</label>
                <select id="delivery_status" name="delivery_status" class="form-select @error('delivery_status') is-invalid @enderror" required>
                    <option value="">Choose...</option>
                    <option value="successful" {{ old('delivery_status') == 'successful' ? 'selected' : '' }}>Successful</option>
                    <option value="complicated" {{ old('delivery_status') == 'complicated' ? 'selected' : '' }}>Complicated</option>
                    <option value="failed" {{ old('delivery_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('delivery_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <label for="notes" class="form-label">Notes</label>
                <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                <a href="{{ route('midwife.delivery.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection