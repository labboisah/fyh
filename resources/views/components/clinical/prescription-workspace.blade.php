<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Prescription</h1>
            <p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p>
        </div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>

    @include('components.clinical._feedback')

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Add Medicine</h2></div>
                <div class="card-body">
                    <form wire:submit.prevent="addItem">
                        <div class="mb-3">
                            <label class="form-label">Medicine</label>
                            <select class="form-select @error('medicineId') is-invalid @enderror" wire:model="medicineId">
                                <option value="">Select medicine</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                            @error('medicineId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Other Medicine</label>
                            <input type="text" class="form-control" wire:model="otherMedicine">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Medicine Type</label>
                            <select class="form-select" wire:model="medicineTypeId">
                                <option value="">Select type</option>
                                @foreach($medicineTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Route</label>
                            <select class="form-select @error('routeId') is-invalid @enderror" wire:model="routeId">
                                <option value="">Select route</option>
                                @foreach($routes as $route)
                                    <option value="{{ $route->id }}">{{ $route->name }}</option>
                                @endforeach
                            </select>
                            @error('routeId') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label">Dosage</label>
                                <input class="form-control @error('dosage') is-invalid @enderror" wire:model="dosage">
                                @error('dosage') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Period</label>
                                <input class="form-control @error('period') is-invalid @enderror" wire:model="period">
                                @error('period') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Duration</label>
                                <input class="form-control @error('duration') is-invalid @enderror" wire:model="duration">
                                @error('duration') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <button class="btn btn-success mt-3" type="submit">Add Medicine</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Prescription Items</h2>
                    <button class="btn btn-primary btn-sm" wire:click="submitPrescription">Submit to Pharmacy</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Medicine</th><th>Route</th><th>Dosage</th><th>Period</th><th>Duration</th><th></th></tr>
                        </thead>
                        <tbody>
                            @forelse($prescription?->prescriptionItems ?? [] as $item)
                                <tr wire:key="prescription-item-{{ $item->id }}">
                                    <td>{{ $item->medicine?->name }}</td>
                                    <td>{{ $item->route?->name }}</td>
                                    <td>{{ $item->dosage }}</td>
                                    <td>{{ $item->period }}</td>
                                    <td>{{ $item->duration }}</td>
                                    <td class="text-end"><button class="btn btn-sm btn-outline-danger" wire:click="removeItem({{ $item->id }})">Remove</button></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">No medicine added yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
