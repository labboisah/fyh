<?php

namespace App\Livewire\Reports;

use App\Models\Bill;
use App\Models\BillInvestigation;
use App\Models\BillService;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.live')]
class FinanceReport extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $status = '';
    public string $issuedBy = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public bool $todayOnly = false;
    public string $reportBy = 'services';
    public string $chartBreakdown = 'services';
    public string $chartType = 'bar';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function updatingIssuedBy(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function updatedTodayOnly(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'issuedBy', 'todayOnly']);
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->resetPage();
    }

    public function render()
    {
        $billQuery = $this->filteredBillQuery();
        $paymentQuery = $this->filteredPaymentQuery();

        return view('components.reports.finance-report', [
            'bills' => (clone $billQuery)->paginate(15),
            'users' => User::query()
                ->whereIn('id', Bill::query()->select('issued_by')->whereNotNull('issued_by'))
                ->orderBy('name')
                ->get(['id', 'name']),
            'summary' => $this->summary($billQuery, $paymentQuery),
            'breakdownRows' => $this->breakdownRows(),
            'chartPayload' => $this->chartPayload(),
            'exportUrl' => route('reports.finance.export', $this->exportParameters()),
        ]);
    }

    private function filteredBillQuery()
    {
        [$startDate, $endDate] = $this->dateRange();

        $query = Bill::query()
            ->with(['patientVisit.patient.demographic', 'walkinPatient', 'issuedBy'])
            ->whereBetween('issued_date', [$startDate, $endDate])
            ->latest('issued_date')
            ->latest('created_at');

        if ($this->search !== '') {
            $search = $this->search;

            $query->where(function ($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                    ->orWhere('service_description', 'like', "%{$search}%")
                    ->orWhereHas('walkinPatient', fn ($patient) => $patient->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('patientVisit.patient', fn ($patient) => $patient->where('hospital_number', 'like', "%{$search}%"))
                    ->orWhereHas('patientVisit.patient.demographic', function ($patient) use ($search) {
                        $patient->whereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                            ->orWhere('phone_number', 'like', "%{$search}%");
                    });
            });
        }

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        if ($this->issuedBy !== '') {
            $query->where('issued_by', $this->issuedBy);
        }

        return $query;
    }

    private function filteredPaymentQuery()
    {
        [$startDate, $endDate] = $this->dateRange();

        return Payment::query()
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->when($this->issuedBy !== '', fn ($query) => $query->where('paid_by', $this->issuedBy));
    }

    private function summary($billQuery, $paymentQuery): array
    {
        return [
            'bill_count' => (clone $billQuery)->count(),
            'total_billed' => (float) (clone $billQuery)->sum('amount'),
            'total_due' => (float) (clone $billQuery)->sum('due_amount'),
            'total_collected' => (float) (clone $paymentQuery)->sum('amount'),
            'open_bills' => (clone $billQuery)->whereIn('status', ['pending', 'partial'])->count(),
        ];
    }

    private function breakdownRows()
    {
        return match ($this->reportBy) {
            'users' => $this->userBreakdown(),
            'departments' => $this->departmentBreakdown(),
            'payments' => $this->paymentMethodBreakdown(),
            'statuses' => $this->statusBreakdown(),
            default => $this->serviceBreakdown(),
        };
    }

    private function chartPayload(): array
    {
        $rows = match ($this->chartBreakdown) {
            'users' => $this->userBreakdown(),
            'departments' => $this->departmentBreakdown(),
            'payments' => $this->paymentMethodBreakdown(),
            'statuses' => $this->statusBreakdown(),
            default => $this->serviceBreakdown(),
        };

        return [
            'type' => $this->chartType,
            'labels' => $rows->pluck('label')->values(),
            'values' => $rows->pluck('amount')->map(fn ($amount) => round((float) $amount, 2))->values(),
            'title' => ucfirst($this->chartBreakdown) . ' Breakdown',
        ];
    }

    private function serviceBreakdown()
    {
        [$startDate, $endDate] = $this->dateRange();

        $services = BillService::query()
            ->join('bills', 'bill_services.bill_id', '=', 'bills.id')
            ->join('services', 'bill_services.service_id', '=', 'services.id')
            ->whereBetween('bills.issued_date', [$startDate, $endDate])
            ->when($this->status !== '', fn ($query) => $query->where('bills.status', $this->status))
            ->when($this->issuedBy !== '', fn ($query) => $query->where('bills.issued_by', $this->issuedBy))
            ->selectRaw('services.name as label, SUM(bill_services.subtotal) as amount, SUM(bill_services.quantity) as quantity, COUNT(bill_services.id) as count')
            ->groupBy('services.id', 'services.name')
            ->get();

        $investigations = BillInvestigation::query()
            ->join('bills', 'bill_investigations.bill_id', '=', 'bills.id')
            ->join('investigations', 'bill_investigations.investigation_id', '=', 'investigations.id')
            ->whereBetween('bills.issued_date', [$startDate, $endDate])
            ->when($this->status !== '', fn ($query) => $query->where('bills.status', $this->status))
            ->when($this->issuedBy !== '', fn ($query) => $query->where('bills.issued_by', $this->issuedBy))
            ->selectRaw('investigations.name as label, SUM(bill_investigations.subtotal) as amount, SUM(bill_investigations.quantity) as quantity, COUNT(bill_investigations.id) as count')
            ->groupBy('investigations.id', 'investigations.name')
            ->get();

        return $services->concat($investigations)
            ->groupBy('label')
            ->map(function ($items, $label) {
                return (object) [
                    'label' => $label,
                    'amount' => $items->sum('amount'),
                    'quantity' => $items->sum('quantity'),
                    'count' => $items->sum('count'),
                ];
            })
            ->sortByDesc('amount')
            ->values()
            ->take(15);
    }

    private function userBreakdown()
    {
        [$startDate, $endDate] = $this->dateRange();

        return Bill::query()
            ->leftJoin('users', 'bills.issued_by', '=', 'users.id')
            ->whereBetween('bills.issued_date', [$startDate, $endDate])
            ->when($this->status !== '', fn ($query) => $query->where('bills.status', $this->status))
            ->when($this->issuedBy !== '', fn ($query) => $query->where('bills.issued_by', $this->issuedBy))
            ->selectRaw('COALESCE(users.name, "Unknown") as label, SUM(bills.amount) as amount, SUM(bills.due_amount) as due_amount, COUNT(bills.id) as count')
            ->groupBy('bills.issued_by', 'users.name')
            ->orderByDesc('amount')
            ->get();
    }

    private function departmentBreakdown()
    {
        [$startDate, $endDate] = $this->dateRange();

        $serviceRows = BillService::query()
            ->join('bills', 'bill_services.bill_id', '=', 'bills.id')
            ->join('services', 'bill_services.service_id', '=', 'services.id')
            ->leftJoin('departments', 'services.department_id', '=', 'departments.id')
            ->whereBetween('bills.issued_date', [$startDate, $endDate])
            ->when($this->status !== '', fn ($query) => $query->where('bills.status', $this->status))
            ->when($this->issuedBy !== '', fn ($query) => $query->where('bills.issued_by', $this->issuedBy))
            ->selectRaw('COALESCE(departments.name, "Unassigned") as label, SUM(bill_services.subtotal) as amount, COUNT(bill_services.id) as count')
            ->groupBy('departments.id', 'departments.name')
            ->get();

        return $serviceRows
            ->groupBy('label')
            ->map(fn ($items, $label) => (object) [
                'label' => $label,
                'amount' => $items->sum('amount'),
                'count' => $items->sum('count'),
            ])
            ->sortByDesc('amount')
            ->values();
    }

    private function paymentMethodBreakdown()
    {
        [$startDate, $endDate] = $this->dateRange();

        return Payment::query()
            ->leftJoin('payment_methods', 'payments.payment_method_id', '=', 'payment_methods.id')
            ->where('payments.status', 'completed')
            ->whereBetween('payments.payment_date', [$startDate, $endDate])
            ->when($this->issuedBy !== '', fn ($query) => $query->where('payments.paid_by', $this->issuedBy))
            ->selectRaw('COALESCE(payment_methods.name, "Unknown") as label, SUM(payments.amount) as amount, COUNT(payments.id) as count')
            ->groupBy('payments.payment_method_id', 'payment_methods.name')
            ->orderByDesc('amount')
            ->get();
    }

    private function statusBreakdown()
    {
        [$startDate, $endDate] = $this->dateRange();

        return Bill::query()
            ->whereBetween('issued_date', [$startDate, $endDate])
            ->when($this->issuedBy !== '', fn ($query) => $query->where('issued_by', $this->issuedBy))
            ->selectRaw('status as label, SUM(amount) as amount, COUNT(id) as count')
            ->groupBy('status')
            ->orderByDesc('amount')
            ->get();
    }

    private function dateRange(): array
    {
        if ($this->todayOnly) {
            return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        }

        $start = Carbon::parse($this->dateFrom ?: now()->startOfMonth()->format('Y-m-d'))->startOfDay();
        $end = Carbon::parse($this->dateTo ?: now()->format('Y-m-d'))->endOfDay();

        if ($start->gt($end)) {
            return [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    private function exportParameters(): array
    {
        return array_filter([
            'start_date' => $this->todayOnly ? null : $this->dateFrom,
            'end_date' => $this->todayOnly ? null : $this->dateTo,
            'today' => $this->todayOnly ? 1 : null,
        ]);
    }
}
