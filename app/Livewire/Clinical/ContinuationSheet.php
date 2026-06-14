<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class ContinuationSheet extends Component
{
    use ManagesClinicalVisit;

    public string $notes = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function render()
    {
        return view('components.clinical.continuation-sheet', [
            'recent' => $this->currentVisit()->continuations()->latest()->limit(10)->get(),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'notes' => ['required', 'string', 'max:10000'],
        ]);

        $this->currentVisit()->continuations()->create([
            'note' => $validated['notes'],
            'written_by' => auth()->id(),
            'date' => now(),
            'time' => now()->format('h:i:s A'),
        ]);

        $this->logActivity('Continuation sheet updated');
        $this->notes = '';
        $this->feedback('Continuation note recorded successfully.');
    }
}
