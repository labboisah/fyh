<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\DrugChart;
use App\Models\Patient;
use App\Models\PrescriptionItem;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class DrugChartWorkspace extends Component
{
    use ManagesClinicalVisit;

    public string $prescriptionItemId = '';
    public string $dosage = '';
    public string $comment = '';
    public ?int $editingId = null;

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function render()
    {
        $items = PrescriptionItem::with(['medicine', 'route'])
            ->whereHas('prescription', fn ($query) => $query->where('patient_visit_id', $this->currentVisit()->id))
            ->latest()
            ->get();

        return view('components.clinical.drug-chart-workspace', [
            'items' => $items,
            'recent' => $this->currentVisit()->prescriptions()
                ->with('prescriptionItems.drugCharts.medicine')
                ->latest()
                ->get()
                ->flatMap(fn ($prescription) => $prescription->prescriptionItems->flatMap->drugCharts)
                ->take(10),
        ]);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'prescriptionItemId' => ['required', 'integer', 'exists:prescription_items,id'],
            'dosage' => ['required', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:255'],
        ]);

        $item = PrescriptionItem::with('medicine', 'prescription')
            ->whereHas('prescription', fn ($query) => $query->where('patient_visit_id', $this->currentVisit()->id))
            ->findOrFail($validated['prescriptionItemId']);

        $payload = [
            'dosage' => $validated['dosage'],
            'medicine_id' => $item->medicine_id,
            'route_id' => $item->route_id,
            'comment' => $validated['comment'] ?? null,
        ];

        if ($this->editingId) {
            $chart = DrugChart::whereHas('prescriptionItem.prescription', fn ($query) => $query->where('patient_visit_id', $this->currentVisit()->id))
                ->findOrFail($this->editingId);
            $chart->update($payload + ['prescription_item_id' => $item->id]);
            $this->logActivity("Drug chart entry updated for medicine: {$item->medicine?->name}");
        } else {
            $item->drugCharts()->create($payload + [
                'time' => now()->format('h:i:s A'),
                'dispensed_by' => auth()->id(),
            ]);
            $this->logActivity("Drug chart entry recorded for medicine: {$item->medicine?->name}");
        }

        $this->editingId = null;
        $this->reset(['prescriptionItemId', 'dosage', 'comment']);
        $this->feedback('Drug chart saved successfully.');
    }

    public function edit(int $id): void
    {
        $chart = DrugChart::whereHas('prescriptionItem.prescription', fn ($query) => $query->where('patient_visit_id', $this->currentVisit()->id))
            ->findOrFail($id);

        $this->editingId = $chart->id;
        $this->prescriptionItemId = (string) $chart->prescription_item_id;
        $this->dosage = (string) $chart->dosage;
        $this->comment = (string) $chart->comment;
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
        $this->reset(['prescriptionItemId', 'dosage', 'comment']);
        $this->resetValidation();
    }
}
