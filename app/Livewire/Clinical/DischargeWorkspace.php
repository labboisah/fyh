<?php

namespace App\Livewire\Clinical;

use App\Models\Admission;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class DischargeWorkspace extends Component
{
    public Admission $admission;
    public string $reason = '';
    public string $date = '';
    public string $time = '';
    public string $nextAppointmentDate = '';
    public ?string $feedbackMessage = null;
    public string $feedbackType = 'success';

    public function mount(Admission $admission): void
    {
        $this->admission = $admission->load('patientVisit.patient');
        $this->date = now()->toDateString();
        $this->time = now()->format('H:i');
    }

    public function render()
    {
        return view('components.clinical.discharge-workspace');
    }

    public function save(): void
    {
        if ($this->admission->status === 'discharged') {
            $this->feedback('This admission has already been discharged.', 'warning');
            return;
        }

        $validated = $this->validate([
            'reason' => ['required', 'string', 'max:10000'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'nextAppointmentDate' => ['nullable', 'date'],
        ]);

        $this->admission->discharge()->create([
            'reason' => $validated['reason'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'next_appointment_date' => $validated['nextAppointmentDate'] ?: null,
            'discharge_by' => auth()->id(),
        ]);

        $this->admission->update(['status' => 'discharged']);
        $this->admission->patientVisit->update(['status' => 'discharged']);
        $this->admission->patientVisit->visitActivities()->create([
            'activity' => "Patient discharged with reason: {$validated['reason']}",
            'recorded_by' => auth()->id(),
        ]);

        $this->feedback('Admission discharged successfully.');
    }

    private function feedback(string $message, string $type = 'success'): void
    {
        $this->feedbackMessage = $message;
        $this->feedbackType = $type;
    }
}
