<!-- display patient vital signs -->

    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-heart-pulse me-2"></i>Continuation Sheet</h5>
    </div>
    <div class="card-body">
       
        @foreach($patient->visits as $visit)
            @if($visit->continuations()->count() > 0)
            @foreach($visit->continuations as $continuation)
                <div class="row mb-3">
                    <p class="mb-0 text-muted">
                        Written By:
                    <strong class="text-success">{{ $continuation->writtenBy->name ?? ''}}</strong>
                    </p>
                    <p class="h6">{{ $continuation->note }}</p>
                    <hr>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Time</label>
                        <p class="h6">{{ $continuation->time }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted">Date</label>
                        <p class="h6">{{ date('M d, Y', strtotime($continuation->date)) }}</p>
                    </div>
                
                </div>
            @endforeach
            @else
            <div class="alert alert-warning">No Continuation Sheet Recorded</div>
            @endif
            @endforeach
        
    </div>
