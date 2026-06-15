<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Continuation Sheet</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="row g-3">
        <div class="col-lg-5"><div class="card border-0 shadow-sm"><div class="card-body">
            <form wire:submit.prevent="save">
                <label class="form-label">Clinical Note</label>
                <textarea rows="10" class="form-control @error('notes') is-invalid @enderror" wire:model="notes"></textarea>
                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div class="d-flex gap-2 mt-3">
                    <button class="btn btn-success">{{ $editingId ? 'Update Note' : 'Save Note' }}</button>
                    @if($editingId)
                        <button type="button" class="btn btn-outline-secondary" wire:click="cancelEdit">Cancel</button>
                    @endif
                </div>
            </form>
        </div></div></div>
        <div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="list-group list-group-flush">
            @forelse($recent as $note)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between gap-2">
                        <div class="small text-muted">{{ $note->created_at }}</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $note->id }})">Edit</button>
                    </div>
                    {{ $note->note }}
                </div>
            @empty
                <div class="list-group-item text-muted">No continuation notes yet.</div>
            @endforelse
        </div></div></div>
    </div>
</div>
