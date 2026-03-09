@extends('layouts.app')
@section('header')
<div class="d-flex align-items-center gap-3">
    <i class="bi bi-person-vcard text-success" style="font-size: 2rem;"></i>
    <div>
        <h1 class="h3 mb-1">{{ $patient->demographic->full_name ?? 'Patient Details' }}</h1>
        <p class="mb-0 text-muted">
            Hospital Number:
            <strong class="text-success">{{ $patient->hospital_number }}</strong>
        </p>
    </div>
</div>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-3">
                <div class="card-body shadow p-4">
                    <h2>Prescribe Medication</h2>
                    <form action="{{ route('patient.prescription.store', $patient) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-2">
                            <label for="ward_id">Medicine Type</label>
                            <select name="medicine_type_id" id="medicine_type_id" class="form-control" required>
                                <option value="">Select Type of Medicine</option>
                                @foreach(App\Models\MedicineType::all() as $type)
                                <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="medicine_id">Medicine</label>
                            <select name="medicine_id" id="medicine_id" class="form-control" required>
                                <option value="">Select Medicine</option>
                            </select>
                        </div>

                        <input type="text" id="other_medicine"
                        name="other_medicine"
                        class="form-control mt-2"
                        placeholder="Enter medicine name"
                        style="display:none;">

                        <div class="form-group mb-2">
                            <label for="ward_id">Route</label>
                            <select name="route_id" id="route_id" class="form-control" required>
                                <option value="">Select Route of Medication</option>
                                @foreach(App\Models\Route::all() as $route)
                                <option value="{{$route->id}}">{{$route->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-2">
                            <label for="date">Dosage</label>
                            <input type="text" class="form-control" name="dosage" required placeholder="Pls, specify value in g, mm, or ml">
                        </div>
                        <div class="form-group mb-2">
                            <label for="period">Period</label>
                            <select name="period" id="period" class="form-control">
                                @foreach([1,2,3,4,5,6,8,12] as $hour)
                                <option value="{{$hour}} hourly">{{$hour}} Hourly</option>
                                @endforeach

                                @foreach([1,2,3,4,5,6,7] as $day)
                                <option value="{{$day}} daily">{{$day}} Daily</option>
                                @endforeach

                                @foreach([1,2,3,4] as $week)
                                <option value="{{$week}} weekly">{{$week}} Weekly</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="time">Duration (Days)</label>
                            <input type="number" class="form-control" name="duration">
                        </div>

                        <button type="submit" class="btn btn-primary">Save Prescription</button>
                    </form>
                </div>
            </div>
        </div>
        
    </div>

    {{-- AJAX SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const medicineTypeSelect = document.getElementById('medicine_type_id');
    const medicineSelect = document.getElementById('medicine_id');
    const otherMedicineInput = document.getElementById('other_medicine');

    const ajaxBaseUrl = "{{ url('/ajax/medicines') }}";

    // Load medicines when type changes
    medicineTypeSelect.addEventListener('change', function () {

        const medicineTypeId = this.value;

        // Reset select
        medicineSelect.innerHTML = '<option value="">Select Medicine</option>';
        otherMedicineInput.style.display = 'none';

        if (!medicineTypeId) return;

        fetch(`${ajaxBaseUrl}/${medicineTypeId}`, {
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

            if (!data.length) {
                medicineSelect.innerHTML =
                    '<option disabled>No medicines found</option>';
                return;
            }

            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                medicineSelect.appendChild(option);
            });

            // Add OTHER option
            const otherOption = document.createElement('option');
            otherOption.value = 'other';
            otherOption.textContent = 'Other (Specify)';
            medicineSelect.appendChild(otherOption);

        })
        .catch(error => {

            console.error(error);

            medicineSelect.innerHTML =
                '<option disabled>Error loading medicines</option>';

        });
    });

    // Show text input when "Other" is selected
    medicineSelect.addEventListener('change', function () {

        if (this.value === 'other') {
            otherMedicineInput.style.display = 'block';
        } else {
            otherMedicineInput.style.display = 'none';
        }

    });

});
</script>
@endsection