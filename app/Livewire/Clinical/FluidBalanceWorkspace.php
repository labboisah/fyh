<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class FluidBalanceWorkspace extends Component
{
    use ManagesClinicalVisit;

    public array $form = [];

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->form = ['date' => now()->toDateString(), 'time' => now()->format('H:i')];
    }

    public function render()
    {
        $admission = $this->currentVisit()->confirmAdmission();

        return view('components.clinical.fluid-balance-workspace', [
            'admission' => $admission,
            'recent' => $admission ? $admission->fluidBalances()->latest()->limit(10)->get() : collect(),
        ]);
    }

    public function save(): void
    {
        $admission = $this->currentVisit()->confirmAdmission();

        if (! $admission) {
            $this->feedback('A confirmed admission is required before recording fluid balance.', 'warning');
            return;
        }

        $validated = $this->validate([
            'form.date' => ['required', 'date'],
            'form.time' => ['required'],
            'form.type_in' => ['nullable', 'string', 'max:255'],
            'form.tube_in' => ['nullable', 'string', 'max:255'],
            'form.oral' => ['nullable', 'numeric', 'min:0'],
            'form.iv' => ['nullable', 'numeric', 'min:0'],
            'form.type_out' => ['nullable', 'string', 'max:255'],
            'form.tube_out' => ['nullable', 'string', 'max:255'],
            'form.urine' => ['nullable', 'numeric', 'min:0'],
            'form.faces' => ['nullable', 'numeric', 'min:0'],
        ])['form'];

        $admission->fluidBalances()->create($validated + ['recorded_by' => auth()->id()]);
        $this->logActivity('Fluid balance chart updated');
        $this->form = ['date' => now()->toDateString(), 'time' => now()->format('H:i')];
        $this->feedback('Fluid balance recorded successfully.');
    }
}
