<?php

namespace App\Livewire\Clinical;

use App\Livewire\Clinical\Concerns\ManagesClinicalVisit;
use App\Models\Bill;
use App\Models\Investigation;
use App\Models\InvestigationRequest;
use App\Models\InvestigationType;
use App\Models\Patient;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class InvestigationRequestWorkspace extends Component
{
    use ManagesClinicalVisit;

    public ?int $editingRequestId = null;
    public string $clinicalDiagnoses = '';
    public array $rows = [
        ['type_id' => '', 'investigation_id' => '', 'specimen' => ''],
    ];

    public function mount(Patient $patient): void
    {
        $this->patient = $patient;
    }

    public function render()
    {
        $investigations = Investigation::with('investigationType')->orderBy('name')->get();

        return view('components.clinical.investigation-request-workspace', [
            'types' => InvestigationType::with('investigations')->where('is_active', true)->orderBy('name')->get(),
            'investigations' => $investigations,
            'recentRequests' => $this->currentVisit()->investigationRequests()
                ->with(['investigation.investigationType', 'bill'])
                ->latest()
                ->limit(10)
                ->get(),
        ]);
    }

    public function addRow(): void
    {
        $this->rows[] = ['type_id' => '', 'investigation_id' => '', 'specimen' => ''];
    }

    public function removeRow(int $index): void
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'clinicalDiagnoses' => ['required', 'string', 'max:5000'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.type_id' => ['required', 'integer', 'exists:investigation_types,id'],
            'rows.*.investigation_id' => ['required', 'integer', 'exists:investigations,id'],
            'rows.*.specimen' => ['nullable', 'string', 'max:255'],
        ]);

        $visit = $this->currentVisit();

        if ($this->editingRequestId) {
            if ($this->updateRequest($validated['rows'][0], $validated['clinicalDiagnoses'])) {
                $this->resetRequestForm();
                $this->feedback('Investigation request and bill updated successfully.');
            }
            return;
        }

        foreach ($validated['rows'] as $row) {
            $investigation = Investigation::with('investigationType.department')->findOrFail($row['investigation_id']);

            if ((int) $investigation->investigation_type_id !== (int) $row['type_id']) {
                $this->feedback('One investigation does not belong to the selected investigation type.', 'warning');
                return;
            }

            $request = $visit->investigationRequests()->create([
                'investigation_id' => $investigation->id,
                'requested_by' => auth()->id(),
                'clinical_diagnoses' => $validated['clinicalDiagnoses'],
                'requested_at' => now(),
                'specimen' => $row['specimen'] ?: null,
            ]);

            InvestigationRequest::updateLabNumber($request->id, $investigation->id);

            $amount = (float) ($investigation->price ?? 0);
            $bill = $visit->bills()->create([
                'amount' => $amount,
                'due_amount' => $amount,
                'service_description' => 'Investigation: ' . $investigation->name,
                'status' => 'pending',
                'issued_by' => auth()->id(),
                'issued_date' => now(),
                'bill_number' => Bill::generateBillNumber(),
                'due_date' => now()->addDays(2)->toDateString(),
                'department_id' => $investigation->investigationType?->department_id,
            ]);

            $request->update(['bill_id' => $bill->id]);
            $bill->billInvestigations()->create([
                'investigation_id' => $investigation->id,
                'unit_price' => $amount,
                'quantity' => 1,
                'subtotal' => $amount,
            ]);

            $this->logActivity("Investigation request created for {$investigation->name}");
        }

        $this->resetRequestForm();
        $this->feedback('Investigation request and bill created successfully.');
    }

    public function editRequest(int $id): void
    {
        $request = $this->currentVisit()->investigationRequests()->with('investigation')->findOrFail($id);
        $this->editingRequestId = $request->id;
        $this->clinicalDiagnoses = (string) $request->clinical_diagnoses;
        $this->rows = [[
            'type_id' => (string) $request->investigation?->investigation_type_id,
            'investigation_id' => (string) $request->investigation_id,
            'specimen' => (string) $request->specimen,
        ]];
    }

    public function cancelEdit(): void
    {
        $this->resetRequestForm();
    }

    private function updateRequest(array $row, string $clinicalDiagnoses): bool
    {
        $request = $this->currentVisit()->investigationRequests()->with('bill.billInvestigations')->findOrFail($this->editingRequestId);
        $investigation = Investigation::with('investigationType.department')->findOrFail($row['investigation_id']);

        if ((int) $investigation->investigation_type_id !== (int) $row['type_id']) {
            $this->feedback('The investigation does not belong to the selected investigation type.', 'warning');
            return false;
        }

        $request->update([
            'investigation_id' => $investigation->id,
            'clinical_diagnoses' => $clinicalDiagnoses,
            'specimen' => $row['specimen'] ?: null,
        ]);

        $amount = (float) ($investigation->price ?? 0);
        $bill = $request->bill;
        if ($bill) {
            $bill->update([
                'amount' => $amount,
                'due_amount' => $amount,
                'service_description' => 'Investigation: ' . $investigation->name,
                'department_id' => $investigation->investigationType?->department_id,
            ]);

            $billInvestigation = $bill->billInvestigations()->first();
            if ($billInvestigation) {
                $billInvestigation->update([
                    'investigation_id' => $investigation->id,
                    'unit_price' => $amount,
                    'quantity' => 1,
                    'subtotal' => $amount,
                ]);
            }
        }

        $this->logActivity("Investigation request updated for {$investigation->name}");
        return true;
    }

    private function resetRequestForm(): void
    {
        $this->editingRequestId = null;
        $this->rows = [['type_id' => '', 'investigation_id' => '', 'specimen' => '']];
        $this->clinicalDiagnoses = '';
        $this->resetValidation();
    }
}
