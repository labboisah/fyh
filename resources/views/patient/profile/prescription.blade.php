@if($patient->currentVisit()->prescriptions()->count() > 0)
    @foreach($patient->currentVisit()->prescriptions as $prescription)
    <div class="card-body">
        <h4>Prescription of Medication</h4>
        <p class="text-muted">Prescribe by {{$prescription->prescribedBy()->name ?? ''}} @ {{$prescription->created_at}}</p>
        <hr>
        @foreach($prescription->prescriptionItems as $item)
        <p><b>{{$loop->iteration}}. Medicine : {{$item->medicine->name}}</b></p>
        <p>Dosage : {{$item->dosage}}</p>
        <p>Period : {{$item->period}}</p>
        <p>For : {{$item->duration}} Days</p>
        @endforeach
    </div>
    @endforeach
@else
<p class="text-muted">No Prescription record found for this patient.</p>
@endif