<ol>
    @foreach($patient->currentVisit()->visitActivities()->latest()->get() as $activity)
        <li class="mb-2">
            <div class="d-flex align-items-center gap-3">
                
                <div>
                    <p class="mb-0">{{ $activity->activity }}</p>
                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </li>
    @endforeach
</ol>