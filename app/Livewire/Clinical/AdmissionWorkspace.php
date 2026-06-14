<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\Ward;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class AdmissionWorkspace extends Component
{
    use ManagesClinicalVisit;

    public string $wardId = '';
    public string $bedId = '';
    public string $date = '';
    public string $time = '';
    public string $days = '1';
    public string $note = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->date = now()->toDateString();
        $this->time = now()->format('H:i');
    }

    public function render()
    {
        $ward = $this->wardId ? Ward::find($this->wardId) : null;
        $days = max(1, (int) $this->days);

        return view('components.clinical.admission-workspace', [
            'wards' => Ward::withCount(['beds'])->orderBy('name')->get(),
            'beds' => $this->wardId
                ? Bed::where('ward_id', $this->wardId)->where('status', 'vacant')->orderBy('bed_no')->get()
                : collect(),
            'estimatedAmount' => $ward ? ((float) $ward->price * $days) : 0,
            'admissions' => $this->currentVisit()->admissions()->with('bed.ward')->latest()->get(),
        ]);
    }

    public function updatedWardId(): void
    {
        $this->bedId = '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'wardId' => ['required', 'integer', 'exists:wards,id'],
            'bedId' => ['required', 'integer', 'exists:beds,id'],
            'date' => ['required', 'date'],
            'time' => ['required'],
            'days' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:5000'],
        ]);

        $bed = Bed::with('ward')->where('ward_id', $validated['wardId'])->where('status', 'vacant')->findOrFail($validated['bedId']);
        $visit = $this->currentVisit();

        $admission = $visit->admissions()->create([
            'date' => $validated['date'],
            'time' => $validated['time'],
            'bed_id' => $bed->id,
            'note' => $validated['note'],
            'admitted_by' => auth()->id(),
        ]);

        $bed->update(['status' => 'occupied']);
        $visit->generateBedSpaceBill($admission, $bed, (int) $validated['days']);
        $this->logActivity("Patient admitted to bed {$bed->bed_no} for {$validated['days']} days");

        $this->reset(['wardId', 'bedId', 'note']);
        $this->days = '1';
        $this->date = now()->toDateString();
        $this->time = now()->format('H:i');
        $this->feedback('Admission registered and bed bill created.');
    }
}
