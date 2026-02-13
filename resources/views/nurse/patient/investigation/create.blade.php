@extends('layouts.app')

@section('title', 'Send Investigation Request - ' . ($patient->demographic->full_name ?? 'Patient'))

@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-heart-pulse text-danger" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">Send Investigation Request</h1>
        <p class="mb-0 text-muted">
            Patient: <strong>{{ $patient->demographic->full_name ?? 'Unknown' }}</strong>
        </p>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">Investigation Request Form</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('nurse.patients.investigations.store', $patient) }}" method="POST">
                    @csrf

                    {{-- Investigation Type --}}
                    <div class="mb-3">
                        <label for="investigation_type" class="form-label">
                            Investigation Type
                        </label>

                        <select name="investigation_type"
                                id="investigation_type"
                                class="form-select"
                                required>

                            <option value="" disabled selected>
                                Select investigation type
                            </option>

                            @foreach(App\Models\InvestigationType::all() as $type)
                                <option value="{{ $type->id }}"
                                    {{ old('investigation_type') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Investigation --}}
                    <div class="mb-3">
                        <label for="investigation" class="form-label">
                            Investigation
                        </label>

                        <select name="investigation"
                                id="investigation"
                                class="form-select"
                                required>
                            <option value="" disabled selected>
                                Select investigation
                            </option>
                        </select>
                    </div>
                    {{-- Specimen --}}
                    <div class="mb-3">
                        <label for="specimen" class="form-label">
                            Specimen Details (if applicable)
                        </label>
                        <input type="text"
                               name="specimen"
                               id="specimen"
                               value="{{ old('specimen') }}"
                               class="form-control"
                               placeholder="e.g. Blood, Urine, etc.">
                    </div>
                    {{-- Clinical Diagnoses --}}
                    
                    <div class="mb-3">
                        <label for="clinical_diagnoses" class="form-label">
                            Clinical Diagnoses / Additional Information
                        </label>
                        <textarea name="clinical_diagnoses"
                                  id="clinical_diagnoses"
                                  class="form-control"
                                  rows="4"
                                  placeholder="Enter any additional information...">{{ old('clinical_diagnoses') }}</textarea>
                    </div>

                    

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('nurse.patients.show', $patient) }}"
                           class="btn btn-secondary">
                            <i class="bi bi-x-lg me-1"></i> Cancel
                        </a>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Send Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- AJAX SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const typeSelect = document.getElementById('investigation_type');
    const investigationSelect = document.getElementById('investigation');

    const ajaxBaseUrl = "{{ url('/ajax/investigations') }}";

    typeSelect.addEventListener('change', function () {

        const typeId = this.value;

        investigationSelect.innerHTML =
            '<option value="" disabled selected>Loading...</option>';

        if (!typeId) return;

        fetch(`${ajaxBaseUrl}/${typeId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network error');
            }
            return response.json();
        })
        .then(data => {

            investigationSelect.innerHTML =
                '<option value="" disabled selected>Select investigation</option>';

            if (data.length === 0) {
                investigationSelect.innerHTML =
                    '<option disabled>No investigations found</option>';
                return;
            }

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                investigationSelect.appendChild(option);
            });
        })
        .catch(error => {

            console.error('Error:', error);

            investigationSelect.innerHTML =
                '<option disabled>Error loading investigations</option>';
        });
    });
});
</script>
@endsection
