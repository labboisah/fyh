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
    public ?int $editingId = null;

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

        if ($this->editingId) {
            $this->currentVisit()->continuations()->findOrFail($this->editingId)->update([
                'note' => $validated['notes'],
            ]);
            $this->logActivity('Continuation note updated');
        } else {
            $this->currentVisit()->continuations()->create([
                'note' => $validated['notes'],
                'written_by' => auth()->id(),
                'date' => now(),
                'time' => now()->format('h:i:s A'),
            ]);
            $this->logActivity('Continuation note recorded');
        }

        $this->editingId = null;
        $this->notes = '';
        $this->feedback('Continuation note saved successfully.');
    }

    public function edit(int $id): void
    {
        $note = $this->currentVisit()->continuations()->findOrFail($id);
        $this->editingId = $note->id;
        $this->notes = $note->note;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->notes = '';
        $this->resetValidation();
    }
}
