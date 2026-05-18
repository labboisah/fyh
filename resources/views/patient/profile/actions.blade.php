
    <div class="card-header bg-info text-white mb-4">
        <h5 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>Quick Actions</h5>
    </div>
    <div class="card-body">
        @if(auth()->user()->hasRole('nurse'))
            <div class="row">
                <div class="col-md-2">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('patient.vitalsign.create', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-hospital me-2"></i>Record Vital Signs
                        </a>
                    </div>
                </div>
                
                <div class="col-md-2">   
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('patient.observation.record', $patient) }}" class="btn btn-outline-danger">
                            <i class="bi bi-heart-pulse me-2"></i>Record Observations
                        </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('patient.drugchart.record', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-heart-pulse me-2"></i>Record Drug Chart
                        </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('patient.fluidbalance.record', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-heart-pulse me-2"></i>Record Fluid Balance
                        </a>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('record.payments.create.form', $patient) }}" class="btn btn-outline-warning">
                            <i class="bi bi-cash-coin me-2"></i>Record Nursing Note
                        </a>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                            <i class="bi bi-file-earmark-medical me-2"></i>Referrer Patient to Doctor
                        </a>
                    </div>
                </div>
                
               
            </div>
            
        </div>
@endif
<div class="row">
    <div class="col-md-8">
        <div class="row">
        @if(auth()->user()->hasRole('record'))
            <div class="col-md-2">
                <div class="d-grid gap-2">
                    <a href="{{ route('record.patients.edit.form', $patient) }}" class="btn btn-outline-info">
                        <i class="bi bi-pencil-square me-2"></i>Edit Patient Information
                    </a>
                </div>
            </div>
            
            <div class="col-md-2">
                <div class="d-grid gap-2">
                    <a href="{{ route('record.patients.index', $patient) }}" class="btn btn-outline-success">
                        <i class="bi bi-eye me-2"></i>View All Patients
                    </a>
                </div> 
            </div>
            <div class="col-md-2">
                <div class="d-grid gap-2">
                    <a href="{{ route('record.visits.create.form', $patient) }}" class="btn btn-outline-success">
                        <i class="bi bi-hospital me-2"></i>Record Visit
                    </a>
                </div> 
            </div>
        @endif

        @if(auth()->user()->hasRole('accountant'))
            <div class="col-md-2">    
                <div class="d-grid gap-2">
                    <a href="{{ route('accountant.bills.create', $patient) }}" class="btn btn-outline-success">
                        <i class="bi bi-file-earmark-medical me-2"></i>Generate Bill
                    </a>
                </div>
            </div>
            @if($patient->payment()['pending'] > 0)
         
            <div class="col-md-2">
                <div class="d-grid gap-2">
                    <a href="{{ route('accountant.payments.create', $patient) }}" class="btn btn-outline-success">
                        <i class="bi bi-cash-coin me-2"></i>Record Payment of N{{number_format($patient->payment()['pending'],2 )}}
                    </a>
                </div>
            </div>
            @endif
        @endif  
        </div>
    </div>
    @if(auth()->user()->hasRole('record') || auth()->user()->hasRole('accountant'))
    <div class="col-md-4">
        <div class="workflow-steps">
            <div class="step completed">
                <div class="step-marker">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="step-label">Patient Registration</div>
            </div>
            
            <div class="step @if($patient->currentVisit() && $patient->currentVisit()->exists()) completed @endif">
                <div class="step-marker">
                    @if($patient->currentVisit() && $patient->currentVisit()->exists())
                        <i class="bi bi-check-circle"></i>
                    @else
                        <i class="bi bi-circle"></i>
                    @endif
                </div>
                <div class="step-label">
                    Record Visit
                </div>
            </div>
            
            <div class="step @if($patient->currentVisit() && $patient->currentVisit()->bills()->exists()) completed @endif">
                <div class="step-marker">
                    @if($patient->currentVisit() && $patient->payment()['pending'] <= 0)
                        <i class="bi bi-check-circle"></i>
                    @else
                        <i class="bi bi-circle"></i>
                    @endif
                </div>
                <div class="step-label">
                    Generate Bill
                </div>
            </div>
            <!--  -->
            <div class="step @if($patient->currentVisit() && $patient->currentVisit()->bills()->exists() && $patient->payment()['pending'] <= 0) completed @endif">
                <div class="step-marker">
                    @if($patient->currentVisit() && $patient->currentVisit()->bills()->exists() && $patient->payment()['pending'] <= 0)
                        <i class="bi bi-check-circle"></i>
                    @else
                        <i class="bi bi-circle"></i>
                    @endif
                </div>
                <div class="step-label">
                    Record Payment
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@if(auth()->user()->hasRole('doctor') || auth()->user()->hasRole('nurse'))
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.investigation.create', $patient) }}" class="btn btn-outline-danger">
                <i class="bi bi-file-medical me-2"></i>Send Investigation Request
            </a>
        </div>
    </div>
@endif

@if(auth()->user()->hasRole('doctor'))
<div class="row">
    @if($patient->currentVisit()->admissions()->count() == 0)
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.admission.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Admit Patient
            </a>
        </div>
    </div>
    @endif
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.continuation.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-pencil me-2"></i>Record Continuation Sheet
            </a>
        </div>
    </div>
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.admission.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-pencil me-2"></i>Referred Patient
            </a>
        </div>
    </div>
   
    @if($admission = $patient->currentVisit()->confirmAdmission())
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.discharge.create', $admission) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Discharge Patient
            </a>
        </div>
    </div>
    @endif
    <div class="col-md-2">
        <div class="d-grid gap-2 mb-3">
            <a href="{{ route('patient.prescription.create', $patient) }}" class="btn btn-outline-success">
                <i class="bi bi-file-earmark-medical me-2"></i>Prescribe Medication
            </a>
        </div>
    </div>
</div>
@endif

</div>