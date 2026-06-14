<?php

namespace App\Livewire\Accountant;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.live')]
class PaymentWorkspace extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $status = '';
    public string $method = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 15;

    public ?int $editingPaymentId = null;
    public string $billNumber = '';
    public ?int $billId = null;
    public string $paymentId = '';
    public string $amount = '';
    public string $paymentMethodId = '';
    public string $paymentDate = '';
    public string $paymentStatus = 'completed';
    public string $referenceNumber = '';
    public string $insuranceProvider = '';
    public string $notes = '';

    protected array $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'method' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'perPage' => ['except' => 15],
    ];

    public function mount(): void
    {
        $this->dateFrom = today()->toDateString();
        $this->dateTo = today()->toDateString();
        $this->paymentDate = now()->format('Y-m-d\TH:i');
        $this->paymentMethodId = (string) (PaymentMethod::where('is_active', true)->orderBy('name')->value('id') ?? '');
    }

    public function render()
    {
        $query = $this->filteredPaymentsQuery();
        $summaryQuery = clone $query;

        return view('components.accountant.payment-workspace', [
            'payments' => $query->paginate($this->perPage),
            'paymentMethods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
            'selectedBill' => $this->selectedBill(),
            'summary' => [
                'count' => (clone $summaryQuery)->count(),
                'completedAmount' => (clone $summaryQuery)->where('status', 'completed')->sum('amount'),
                'pendingCount' => (clone $summaryQuery)->where('status', 'pending')->count(),
                'reversedCount' => (clone $summaryQuery)->where('status', 'reversed')->count(),
            ],
        ]);
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStatus(): void
    {
        $this->resetPage();
    }

    public function updatedMethod(): void
    {
        $this->resetPage();
    }

    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedBillNumber(): void
    {
        $this->billId = null;
        $this->resetErrorBag('billNumber');

        $bill = $this->selectedBill();

        if ($bill) {
            $this->billId = $bill->id;

            if ($this->amount === '') {
                $this->amount = (string) max(0, (float) $bill->balance);
            }
        }
    }

    public function refreshList(): void
    {
        // Livewire polling re-renders the table while the page is visible.
    }

    public function save(): void
    {
        $validated = $this->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paymentMethodId' => ['required', 'exists:payment_methods,id'],
            'paymentDate' => ['required', 'date'],
            'paymentStatus' => ['required', 'in:pending,completed,failed,reversed'],
            'referenceNumber' => ['nullable', 'string', 'max:255'],
            'insuranceProvider' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($this->editingPaymentId) {
            $payment = Payment::where('paid_by', Auth::id())->findOrFail($this->editingPaymentId);
            $bill = $payment->bill;
        } else {
            $this->validate([
                'billNumber' => ['required', 'string', 'exists:bills,bill_number'],
            ]);

            $bill = $this->selectedBill();

            if (! $bill) {
                $this->addError('billNumber', 'No bill was found with this bill number.');
                return;
            }

            if ($bill->status === 'paid') {
                $this->addError('billNumber', 'This bill is already fully paid.');
                return;
            }
        }

        $oldPayment = $this->editingPaymentId
            ? Payment::where('paid_by', Auth::id())->findOrFail($this->editingPaymentId)
            : null;

        $balanceLimit = (float) $bill->balance + (float) ($oldPayment?->amount ?? 0);

        if ($validated['paymentStatus'] === 'completed' && (float) $validated['amount'] > $balanceLimit) {
            $this->addError('amount', 'Payment amount cannot be greater than the bill balance of ' . number_format($balanceLimit, 2) . '.');
            return;
        }

        DB::transaction(function () use ($validated, $bill) {
            $payload = [
                'amount' => $validated['amount'],
                'payment_method_id' => $validated['paymentMethodId'],
                'payment_date' => $validated['paymentDate'],
                'status' => $validated['paymentStatus'],
                'reference_number' => $validated['referenceNumber'],
                'insurance_provider' => $validated['insuranceProvider'],
                'notes' => $validated['notes'],
            ];

            if ($this->editingPaymentId) {
                $payment = Payment::where('paid_by', Auth::id())->findOrFail($this->editingPaymentId);
                $payment->update($payload);
            } else {
                $payment = Payment::create(array_merge($payload, [
                    'bill_id' => $bill->id,
                    'patient_id' => $bill->patientVisit?->patient_id,
                    'payment_id' => Payment::generatePaymentID(),
                    'paid_by' => Auth::id(),
                ]));
            }

            $this->refreshBillStatus($payment->bill_id);
        });

        $message = $this->editingPaymentId ? 'Payment updated successfully.' : 'Payment recorded successfully.';

        $this->resetForm();
        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function edit(int $paymentId): void
    {
        $payment = Payment::where('paid_by', Auth::id())->findOrFail($paymentId);

        $this->editingPaymentId = $payment->id;
        $this->billId = $payment->bill_id;
        $this->billNumber = $payment->bill?->bill_number ?? '';
        $this->paymentId = $payment->payment_id;
        $this->amount = (string) $payment->amount;
        $this->paymentMethodId = (string) $payment->payment_method_id;
        $this->paymentDate = $payment->payment_date?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
        $this->paymentStatus = $payment->status;
        $this->referenceNumber = $payment->reference_number ?? '';
        $this->insuranceProvider = $payment->insurance_provider ?? '';
        $this->notes = $payment->notes ?? '';
        $this->resetValidation();
    }

    public function reverse(int $paymentId): void
    {
        $payment = Payment::where('paid_by', Auth::id())->findOrFail($paymentId);

        if ($payment->status === 'reversed') {
            $this->dispatch('toast', message: 'This payment is already reversed.', type: 'warning');
            return;
        }

        $payment->update([
            'status' => 'reversed',
            'notes' => trim(($payment->notes ? $payment->notes . "\n" : '') . 'Reversed by ' . Auth::user()->name . ' on ' . now()->format('Y-m-d H:i')),
        ]);

        $this->refreshBillStatus($payment->bill_id);
        $this->dispatch('toast', message: 'Payment reversed successfully.', type: 'warning');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'method']);
        $this->dateFrom = today()->toDateString();
        $this->dateTo = today()->toDateString();
        $this->perPage = 15;
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingPaymentId',
            'billId',
            'billNumber',
            'paymentId',
            'amount',
            'paymentMethodId',
            'paymentDate',
            'paymentStatus',
            'referenceNumber',
            'insuranceProvider',
            'notes',
        ]);

        $this->paymentStatus = 'completed';
        $this->paymentDate = now()->format('Y-m-d\TH:i');
        $this->paymentMethodId = (string) (PaymentMethod::where('is_active', true)->orderBy('name')->value('id') ?? '');
        $this->resetValidation();
    }

    private function filteredPaymentsQuery(): Builder
    {
        return Payment::query()
            ->with(['bill.patientVisit.patient.demographic', 'bill.walkinPatient', 'paymentMethod', 'recordedBy'])
            ->where('paid_by', Auth::id())
            ->when(trim($this->search) !== '', function (Builder $query) {
                $search = trim($this->search);

                $query->where(function (Builder $query) use ($search) {
                    $query
                        ->where('payment_id', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%")
                        ->orWhereHas('bill', fn (Builder $bill) => $bill->where('bill_number', 'like', "%{$search}%"))
                        ->orWhereHas('bill.walkinPatient', fn (Builder $patient) => $patient->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('bill.patientVisit.patient', fn (Builder $patient) => $patient->where('hospital_number', 'like', "%{$search}%"))
                        ->orWhereHas('bill.patientVisit.patient.demographic', function (Builder $patient) use ($search) {
                            $patient
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('phone_number', 'like', "%{$search}%");
                        });
                });
            })
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->method !== '', fn (Builder $query) => $query->where('payment_method_id', $this->method))
            ->when($this->dateFrom !== '', fn (Builder $query) => $query->whereDate('payment_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn (Builder $query) => $query->whereDate('payment_date', '<=', $this->dateTo))
            ->latest('payment_date')
            ->latest('created_at');
    }

    private function selectedBill(): ?Bill
    {
        if (trim($this->billNumber) === '') {
            return null;
        }

        return Bill::with(['patientVisit.patient.demographic', 'walkinPatient', 'payments'])
            ->where('bill_number', trim($this->billNumber))
            ->first();
    }

    private function refreshBillStatus(?int $billId): void
    {
        if (! $billId) {
            return;
        }

        $bill = Bill::find($billId);

        if (! $bill) {
            return;
        }

        $totalPaid = $bill->payments()->where('status', 'completed')->sum('amount');
        $status = 'pending';

        if ($totalPaid >= (float) $bill->due_amount) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partial';
        }

        $bill->update(['status' => $status]);
        $bill->refreshRequestPaymentStatuses();
    }
}
