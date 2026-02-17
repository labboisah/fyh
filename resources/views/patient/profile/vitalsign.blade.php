<!-- display patient vital signs -->
@if(auth()->user()->hasRole('nurse'))
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Vital Signs</h5>
    </div>
    <div class="card-body">
        @if($patient->visits()->exists())
            @foreach($patient->visits as $visit)
            @foreach($visit->vitalSignsRequests as $vitalSignRequest)
            @foreach($vitalSignRequest->patientVisitVitalSigns as $vitalSign)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label text-muted">Date</label>
                        <p class="h6">{{ $vitalSign->visit_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Temperature</label>
                        <p class="h6">{{ $vitalSign->temperature ?? 'N/A' }}°C</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Blood Pressure</label>
                        <p class="h6">{{ $vitalSign->blood_pressure ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Heart Rate</label>
                        <p class="h6">{{ $vitalSign->heart_rate ?? 'N/A' }} bpm</p>
                    </div>
                </div>
            @endforeach
            @endforeach
            @endforeach
        @else
            <p>No vital signs recorded yet.</p>
        @endif
    </div>
@endif