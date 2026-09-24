<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h1 class="h4 mb-1">Drug Chart</h1><p class="text-muted mb-0">{{ $patient->name() }} | {{ $patient->hospital_number }}</p></div>
        <a href="{{ route('patient.show', $patient) }}" class="btn btn-outline-secondary">Back</a>
    </div>
    @include('components.clinical._feedback')
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h2 class="h6 mb-0">Search Prescribed Medicines</h2></div>
                <div class="card-body">
                    <input class="form-control" wire:model.live.debounce.300ms="prescriptionSearch" placeholder="Search prescribed medicine, generic name, or manufacturer">
                    <div class="list-group mt-2" style="max-height: 260px; overflow-y: auto;">
                        @forelse($items as $item)
                            @php($itemKey = (string) $item->id)
                            <label class="list-group-item d-flex align-items-start gap-2">
                                <input class="form-check-input mt-1" type="checkbox" wire:click="toggleItem({{ $item->id }})" @checked(isset($selectedItems[$itemKey]))>
                                <span class="flex-grow-1">
                                    <span class="d-block fw-semibold">{{ $item->medicine?->name }}</span>
                                    <small class="text-muted">Prescribed: {{ $item->dosage }} | {{ $item->route?->name }}</small>
                                </span>
                            </label>
                        @empty
                            <div class="text-muted small py-2">No active prescribed medicines found.</div>
                        @endforelse
                    </div>
                    <div class="small text-muted mt-2">Select one or more prescribed medicines to prepare their drug chart entries.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h6 mb-0">Drug Chart Review</h2>
                    <span class="badge bg-primary">{{ count($selectedItems) }} selected</span>
                </div>
                <div class="card-body">
                    <div class="row g-2 mb-3">
                        <div class="col-md-6"><label class="form-label">Date</label><input type="date" class="form-control" wire:model="date"></div>
                        <div class="col-md-6"><label class="form-label">Time</label><input type="time" class="form-control" wire:model="time"></div>
                    </div>
                    @forelse($selectedItems as $key => $selectedItem)
                        <div class="border rounded p-3 mb-2" wire:key="drug-review-{{ $key }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $selectedItem['medicine'] }}</strong>
                                <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeSelectedItem('{{ $key }}')">Remove</button>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-5"><label class="form-label small">Dosage Given</label><input class="form-control form-control-sm" wire:model="selectedItems.{{ $key }}.dosage"></div>
                                <div class="col-md-7"><label class="form-label small">Comment</label><input class="form-control form-control-sm" wire:model="selectedItems.{{ $key }}.comment"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4">Select prescribed medicines to review.</div>
                    @endforelse
                    <button type="button" class="btn btn-success w-100" wire:click="save" @disabled(!$selectedItems)>Save Drug Chart Entries</button>
                    @if($editingId)
                        <button type="button" class="btn btn-outline-secondary w-100 mt-2" wire:click="cancelEdit">Cancel Edit</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-12"><div class="card border-0 shadow-sm"><div class="table-responsive">
            <table class="table table-hover mb-0"><thead class="table-light"><tr><th>Date</th><th>Time</th><th>Medicine</th><th>Dosage</th><th>Comment</th><th></th></tr></thead><tbody>
                @forelse($recent as $chart)
                    <tr>
                        <td>{{ date('Y-m-d', strtotime($chart->date)) }}</td>
                        <td>{{ date('h:i:s A', strtotime($chart->time)) }}</td>
                        <td>{{ $chart->medicine?->name }}</td>
                        <td>{{ $chart->dosage }}</td>
                        <td>{{ $chart->comment }}</td>
                        <td class="text-end"><button type="button" class="btn btn-sm btn-outline-primary" wire:click="edit({{ $chart->id }})">Edit</button></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No drug chart entries yet.</td></tr>
                @endforelse
            </tbody></table>
        </div></div></div>
    </div>
</div>
