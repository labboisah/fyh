@if($patient->currentVisit()->prescriptions()->count() > 0)
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Prescription of Medication</h4>
        @if(auth()->user()->hasRole('doctor'))
            <a href="{{ route('patient.prescription.create', $patient) }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-pencil-square me-1"></i> Manage
            </a>
        @endif
    </div>
    @foreach($patient->currentVisit()->prescriptions as $prescription)
    <div class="card-body">
        <p class="text-muted">Prescribed by {{ $prescription->prescribedBy?->name ?? 'N/A' }} @ {{ $prescription->created_at }}</p>
        <p><b>Treatment / Infection / Disease:</b> {{ $prescription->treatment_diagnosis ?? 'N/A' }}</p>
        <hr>
        @foreach($prescription->prescriptionItems as $item)
        <p><b>{{$loop->iteration}}. Medicine : {{$item->medicine->name}}</b></p>
        <p>Company : {{ $item->medicine->manufacturer ?? 'N/A' }}</p>
        <p>Availability : {{ $item->medicine->availabilityLabel() }}</p>
        <p>Amount : &#8358;{{ number_format($item->medicine->latestSellingPrice(), 2) }}</p>
        <p>Dosage : {{$item->dosage}}</p>
        <p>Period : {{$item->period}}</p>
        <p>For : {{$item->duration}} Days</p>
        @endforeach
        <p class="fw-bold">Prescription Amount: &#8358;{{ number_format($prescription->prescriptionItems->sum(fn($item) => $item->medicine?->latestSellingPrice() ?? 0), 2) }}</p>
        @if(auth()->user()->hasRole('doctor'))
            <a href="{{ route('patient.prescription.create', $patient) }}" class="btn btn-sm btn-outline-primary">Edit in Prescription</a>
        @endif
    </div>
    @endforeach
@else
@if(auth()->user()->hasRole('doctor'))
    <a href="{{ route('patient.prescription.create', $patient) }}" class="btn btn-sm btn-outline-primary mb-2">
        <i class="bi bi-plus-circle me-1"></i> Create Prescription
    </a>
@endif
<p class="text-muted">No Prescription record found for this patient.</p>
@endif
