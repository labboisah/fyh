@if(auth()->user()->hasRole('nurse'))
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="bi bi-vial me-2"></i>Investigation Requests</h5>
    </div>
    <div class="card-body">
        @if($patient->currentVisit()->investigationRequests->where('status', 'Pending')->count() > 0)
            @foreach($patient->currentVisit()->investigationRequests as $request)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>{{ $request->investigation->name }}</span>
                    @if($request->status === 'Pending')
                    <span class="badge bg-warning text-dark">{{ $request->status }}</span>
                    @else
                    <a href="{{ route('nurse.patients.investigations.show', $request) }}" class="btn btn-sm btn-outline-success">View Results</a>
                    @endif
                </div>
            @endforeach
        @else
            <p class="text-muted">No pending investigation requests.</p>
        @endif
    </div>
@endif