

<div class="container-fluid">

    <h4 class="mb-4">Pharmacy Dashboard</h4>

    <div class="row g-3">

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white">
                <div class="card-body">
                    <h6>Prescriptions Today</h6>
                    <h3>{{ $today_prescriptions ?? 0 }}</h3>
                    <i class="bi bi-file-medical fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-warning text-dark">
                <div class="card-body">
                    <h6>Pending Prescriptions</h6>
                    <h3>{{ $pending_prescriptions ?? 0 }}</h3>
                    <i class="bi bi-hourglass-split fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-success text-white">
                <div class="card-body">
                    <h6>Dispensed Today</h6>
                    <h3>{{ $dispensed_today ?? 0 }}</h3>
                    <i class="bi bi-capsule-pill fs-2"></i>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-danger text-white">
                <div class="card-body">
                    <h6>Low Stock Medicines</h6>
                    <h3>{{ $low_stock ?? 0 }}</h3>
                    <i class="bi bi-exclamation-triangle fs-2"></i>
                </div>
            </div>
        </div>

    </div>


    <div class="row mt-4">

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                Recent Prescriptions
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Medicine</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($prescriptions ?? [] as $prescription)
                            <tr>
                                <td>{{ $prescription->patient->name }}</td>
                                <td>{{ $prescription->medicine->name }}</td>
                                <td>{{ $prescription->doctor->name }}</td>
                                <td>{{ $prescription->created_at->format('d M Y') }}</td>

                                <td>
                                <a href="{{ route('pharmacy.dispense',$prescription->id) }}"
                                class="btn btn-sm btn-primary">

                                <i class="bi bi-capsule"></i> Dispense
                                </a>
                                </td>
                            </tr>
                            @empty

                            <tr>
                                <td colspan="5" class="text-center">No prescriptions available</td>
                            </tr>

                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                Expiry Alerts
                </div>
                <div class="card-body">
                    @forelse($expiring_medicines ?? [] as $drug)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span>{{ $drug->name }}</span>
                            <span class="badge bg-danger">
                            {{ $drug->expiry_date }}
                            </span>
                        </div>
                    @empty
                    <p class="text-muted">No expiry alerts</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>