
    <div class="card-header bg-info text-white mb-4">
        <h5 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>
    </div>
@if(auth()->user()->hasRole('nurse'))
            
            <div class="card-body">
                <div class="row">
                   
                @foreach($patient->currentVisit()->vitalSignsRequests->where('status', 'Pending') as $vitalSignRequest)
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.vitalsigns.create', $vitalSignRequest) }}" class="btn btn-outline-danger">
                            <i class="bi bi-hospital me-2"></i>Record Vital Signs
                        </a>
                    </div>
                </div>
                @endforeach
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-medical me-2"></i>Referrer Patient to Doctor
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.investigations.create', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-file-medical me-2"></i>Investigation Request
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.payments.create.form', $patient) }}" class="btn btn-outline-warning">
                            <i class="bi bi-cash-coin me-2"></i>Add Nursing Note
                        </a>
                    </div>
                </div>
                <div class="col-md-6">   
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-heart-pulse me-2"></i>Record Observations
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-heart-pulse me-2"></i>Record Drug Chart
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record_officer.vital-signs.request', $patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-heart-pulse me-2"></i>Generate Patient Care Report
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('nurse.patients.history', $patient) }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history me-2"></i>View Patient History
                        </a>
                    </div>
                </div>
            </div>
                <p class="text-muted small mt-3 mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    Follow the workflow: Record Vital Signs → Add Nursing Note → Record Drug Chart → Record Observations → Generate Patient Care Report → Submit to Doctor.
                </p>
            </div>
@elseif(auth()->user()->hasRole('doctor'))
<div class="row">
    <div class="col-md-3">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.admission.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Admit Patient
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Discharge Patient
            </a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Prescribe Medication
            </a>
        </div>
    </div>
</div>
@elseif(auth()->user()->hasRole('accountant')|| auth()->user()->hasRole('record_officer'))

@endif