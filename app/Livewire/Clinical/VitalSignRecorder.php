<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Patient;
use App\Models\VitalSign;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class VitalSignRecorder extends Component
{
    use ManagesClinicalVisit;

    public array $form = [];

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->form = ['recorded_date' => now()->format('Y-m-d\TH:i')];
    }

    public function render()
    {
        return view('components.clinical.vital-sign-recorder', [
            'recent' => $this->currentVisit()->vitalSigns()->latest('recorded_date')->limit(10)->get(),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'form.body_temperature' => ['required', 'numeric', 'between:35,42'],
            'form.blood_pressure_systolic' => ['required', 'numeric', 'between:50,250'],
            'form.blood_pressure_diastolic' => ['required', 'numeric', 'between:30,150'],
            'form.heart_rate' => ['required', 'numeric', 'between:30,200'],
            'form.respiratory_rate' => ['required', 'numeric', 'between:10,50'],
            'form.oxygen_saturation' => ['required', 'numeric', 'between:50,100'],
            'form.blood_glucose' => ['nullable', 'numeric', 'min:0'],
            'form.weight' => ['nullable', 'numeric', 'min:0'],
            'form.height' => ['nullable', 'numeric', 'min:0'],
            'form.notes' => ['nullable', 'string', 'max:1000'],
            'form.recorded_date' => ['required', 'date'],
        ])['form'];

        $validated['patient_visit_id'] = $this->currentVisit()->id;
        $validated['recorded_by'] = auth()->id();

        VitalSign::create($validated);
        $this->logActivity('Vital signs recorded');
        $this->form = ['recorded_date' => now()->format('Y-m-d\TH:i')];
        $this->feedback('Vital signs recorded successfully.');
    }
}
