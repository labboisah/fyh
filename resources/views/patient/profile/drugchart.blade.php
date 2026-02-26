@if($patient->currentVisit()->prescriptions()->count() > 0)
@foreach($patient->currentVisit()->prescriptions as $prescription)
@foreach($prescription->prescriptionItems as $pItem)
<div class="row">
    <div class="col-md-5">
        <div class="row mb-3">
            <p class="mb-0 text-muted">
                Drug:
            <strong class="text-success">{{$pItem->medicine->name}}</strong>
            </p>
            <hr>
            <div class="col-md-3">
                <label class="form-label text-muted">Prescribe By</label>
                <p class="h6">{{ $prescription->prescribedBy->name ?? ''}}</p>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted">Dosage</label>
                <p class="h6">{{ $pItem->dosage }}</p>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted">Period</label>
                <p class="h6">{{ $pItem->period }}</p>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted">Duration</label>
                <p class="h6">{{ $pItem->duration }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <p class="text-muted text-center">{{$pItem->medicine->name}} Drug Chart</p>
        @if($pItem->drugCharts()->count() > 0)
           <table class="table">
            <thead>
                <tr>
                    <th>Dispense By</th>
                    <th>Dosage</th>
                    <th>Mode of Administration</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pItem->drugCharts as $drugChart)
                <tr>
                    <td>{{ $drugChart->dispensedBy->name ?? ''}}</td>
                    <td>{{ $pItem->dosage }}</td>
                    <td>{{ $drugChart->mode_of_administration }}</td>
                    <td>{{ $drugChart->time }}</td>
                    <td>{{ $drugChart->created_at->format('d M, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
           </table>
            
                
            
        @else
            <div class="alert alert-warning"> No Drug Chart found</div>
        @endif
    </div>
</div>

@endforeach
@endforeach
@else
<div class="alert alert-warning">No prescription of medication in the last visit of this patient</div>
@endif