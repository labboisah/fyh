<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Medicine;
use App\Models\MedicineType;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Route as MedicineRoute;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class PrescriptionWorkspace extends Component
{
    use ManagesClinicalVisit;

    public ?int $prescriptionId = null;
    public string $medicineId = '';
    public string $medicineTypeId = '';
    public string $otherMedicine = '';
    public string $routeId = '';
    public string $dosage = '';
    public string $period = '';
    public string $duration = '';

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
        $this->prescriptionId = $this->currentVisit()
            ->prescriptions()
            ->where('prescribe_by', auth()->id())
            ->where('status', 'active')
            ->latest()
            ->value('id');
    }

    public function render()
    {
        return view('components.clinical.prescription-workspace', [
            'medicines' => Medicine::orderBy('name')->get(),
            'medicineTypes' => MedicineType::orderBy('name')->get(),
            'routes' => MedicineRoute::orderBy('name')->get(),
            'prescription' => $this->prescription(),
        ]);
    }

    public function addItem(): void
    {
        $validated = $this->validate([
            'medicineId' => ['nullable', 'integer', 'exists:medicines,id'],
            'medicineTypeId' => ['nullable', 'integer', 'exists:medicine_types,id'],
            'otherMedicine' => ['nullable', 'string', 'max:255'],
            'routeId' => ['required', 'integer', 'exists:routes,id'],
            'dosage' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'duration' => ['required', 'string', 'max:255'],
        ]);

        $medicine = trim($validated['otherMedicine']) !== ''
            ? Medicine::firstOrCreate([
                'name' => $validated['otherMedicine'],
            ], [
                'medicine_type_id' => $validated['medicineTypeId'] ?: null,
            ])
            : Medicine::find($validated['medicineId']);

        if (! $medicine) {
            $this->feedback('Select a medicine or enter a new medicine name.', 'warning');
            return;
        }

        $prescription = $this->prescription() ?: $this->currentVisit()->prescriptions()->create([
            'prescribe_by' => auth()->id(),
            'status' => 'active',
        ]);

        $this->prescriptionId = $prescription->id;

        $prescription->prescriptionItems()->create([
            'medicine_id' => $medicine->id,
            'route_id' => (int) $validated['routeId'],
            'dosage' => $validated['dosage'],
            'period' => $validated['period'],
            'duration' => $validated['duration'],
        ]);

        $this->logActivity("Prescription item added for medicine: {$medicine->name}");
        $this->resetItemForm();
        $this->feedback('Medicine added to prescription.');
    }

    public function removeItem(int $itemId): void
    {
        $prescription = $this->prescription();
        $item = $prescription?->prescriptionItems()->findOrFail($itemId);
        $item?->delete();
        $this->feedback('Medicine removed from prescription.', 'danger');
    }

    public function submitPrescription(): void
    {
        $prescription = $this->prescription();

        if (! $prescription || $prescription->prescriptionItems()->count() === 0) {
            $this->feedback('Add at least one medicine before submitting to pharmacy.', 'warning');
            return;
        }

        $prescription->update(['status' => 'submitted']);
        $this->logActivity('Prescription submitted to pharmacy');
        $this->feedback('Prescription submitted to pharmacy.');
    }

    private function prescription(): ?Prescription
    {
        return $this->prescriptionId
            ? Prescription::with(['prescriptionItems.medicine', 'prescriptionItems.route'])->find($this->prescriptionId)
            : null;
    }

    private function resetItemForm(): void
    {
        $this->reset(['medicineId', 'medicineTypeId', 'otherMedicine', 'routeId', 'dosage', 'period', 'duration']);
        $this->resetValidation();
    }
}
