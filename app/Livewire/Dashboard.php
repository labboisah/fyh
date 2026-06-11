<?php

namespace App\Livewire;

use App\Models\AuditLog;
use App\Models\Bill;
use App\Models\InvestigationRequest;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\PatientVisit;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\WalkinPatient;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.live')]
class Dashboard extends Component
{
    public function render()
    {
        $user = auth()->user();

        return view('components.dashboard', [
            'user' => $user,
            'cards' => $this->cards($user),
            'quickActions' => $this->quickActions($user),
            'recentActivities' => $this->recentActivities($user->id),
            'lastUpdated' => now(),
        ]);
    }

    private function cards($user): Collection
    {
        $cards = collect();

        if ($user->hasRole('record')) {
            $cards = $cards->merge([
                $this->card('Total Patients', Patient::count(), 'bi-people-fill', 'text-primary', 'All registered patient records'),
                $this->card('Registered Today', Patient::whereDate('created_at', today())->count(), 'bi-person-plus-fill', 'text-success', 'New records today'),
                $this->card('Today Visits', PatientVisit::whereDate('visit_date', today())->count(), 'bi-stethoscope', 'text-warning', 'Visits opened today'),
                $this->card('Walk-in Patients', WalkinPatient::count(), 'bi-person-check', 'text-info', 'Walk-in records'),
            ]);
        }

        if ($user->hasRole('accountant')) {
            $cards = $cards->merge([
                $this->card('My Bills', Bill::where('issued_by', $user->id)->count(), 'bi-receipt', 'text-primary', 'Bills issued by you'),
                $this->card('My Open Bills', Bill::where('issued_by', $user->id)->whereIn('status', ['pending', 'partial'])->count(), 'bi-exclamation-circle', 'text-warning', 'Pending or partial bills'),
                $this->card('Collected Today', number_format($this->paymentsByUser($user->id)->whereBetween('payment_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->sum('amount'), 2), 'bi-cash-coin', 'text-success', 'Your completed payments today'),
                $this->card('My Payments', Payment::where('paid_by', $user->id)->count(), 'bi-credit-card-2-front', 'text-info', 'Payments recorded by you'),
            ]);
        }

        if ($user->hasAnyRole(['doctor', 'nurse'])) {
            $cards = $cards->merge([
                $this->card('Department Requests', $this->departmentServiceRequests($user)->count(), 'bi-clipboard-pulse', 'text-primary', 'Requests in your department'),
                $this->card('Pending Requests', $this->departmentServiceRequests($user)->where('status', 'Pending')->count(), 'bi-hourglass-split', 'text-warning', 'Awaiting action'),
                $this->card('Completed Today', $this->departmentServiceRequests($user)->where('status', 'Completed')->whereDate('completed_at', today())->count(), 'bi-check-circle', 'text-success', 'Completed today'),
            ]);
        }

        if ($user->hasAnyRole(['lab_technician', 'lab_scientist', 'radiologist'])) {
            $cards = $cards->merge([
                $this->card('Investigation Requests', $this->departmentInvestigationRequests($user)->count(), 'bi-vial', 'text-primary', 'Department investigation work'),
                $this->card('Pending Investigations', $this->departmentInvestigationRequests($user)->where('status', 'Pending')->count(), 'bi-hourglass-split', 'text-warning', 'Awaiting result'),
                $this->card('Completed Today', $this->departmentInvestigationRequests($user)->where('status', 'Completed')->whereDate('completed_at', today())->count(), 'bi-check-circle', 'text-success', 'Completed today'),
            ]);
        }

        if ($user->hasRole('midwife')) {
            $midwife = $user->getMidwifeData();

            $cards = $cards->merge([
                $this->card('Antenatal Today', $midwife['antenatal_today'] ?? 0, 'bi-heart-pulse-fill', 'text-primary', 'ANC records today'),
                $this->card('Labour In Progress', $midwife['labour_in_progress'] ?? 0, 'bi-activity', 'text-warning', 'Active labour cases'),
                $this->card('Deliveries Today', $midwife['delivery_today'] ?? 0, 'bi-hospital-fill', 'text-success', 'Deliveries recorded today'),
                $this->card('Newborn Today', $midwife['newborn_today'] ?? 0, 'bi-bandaid-fill', 'text-info', 'Newborn records today'),
            ]);
        }

        if ($user->hasRole('pharmacist')) {
            $cards = $cards->merge([
                $this->card('Medicines', Medicine::count(), 'bi-capsule', 'text-primary', 'Configured medicines'),
                $this->card('Transactions Today', $this->safeCount('stock_transactions', 'created_at'), 'bi-arrow-left-right', 'text-success', 'Stock activity today'),
                $this->card('Dispenses Today', $this->safeCount('pharmacy_dispenses', 'created_at'), 'bi-file-medical', 'text-warning', 'Dispenses today'),
            ]);
        }

        if ($user->hasRole('administrator')) {
            $cards = $cards->merge([
                $this->card('Patients', Patient::count(), 'bi-people-fill', 'text-primary', 'All patient records'),
                $this->card('Active Visits', PatientVisit::where('status', 'Active')->count(), 'bi-clipboard-pulse', 'text-success', 'Currently active visits'),
                $this->card('Bills Today', Bill::whereBetween('issued_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->count(), 'bi-receipt', 'text-warning', 'Bills issued today'),
                $this->card('Payments Today', Payment::whereBetween('payment_date', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->count(), 'bi-cash-coin', 'text-info', 'Payments recorded today'),
            ]);
        }

        if ($cards->isEmpty()) {
            $cards->push($this->card('My Activities Today', AuditLog::where('actor_id', $user->id)->whereDate('created_at', today())->count(), 'bi-activity', 'text-success', 'Actions recorded today'));
        }

        return $cards;
    }

    private function quickActions($user): Collection
    {
        $actions = collect([
            ['label' => 'My Activities', 'description' => 'Review your recorded work', 'icon' => 'bi-activity', 'route' => route('reports.my-activities.index'), 'class' => 'btn-outline-success'],
        ]);

        if ($user->hasRole('record')) {
            $actions = $actions->merge([
                ['label' => 'Register Patient', 'description' => 'Add new patient', 'icon' => 'bi-person-plus', 'route' => route('record.patients.register.form'), 'class' => 'btn-outline-primary'],
                ['label' => 'Patients', 'description' => 'Browse records', 'icon' => 'bi-people', 'route' => route('record.patients.index'), 'class' => 'btn-outline-info'],
            ]);
        }

        if ($user->hasRole('accountant')) {
            $actions = $actions->merge([
                ['label' => 'Bills', 'description' => 'Manage billing', 'icon' => 'bi-receipt', 'route' => route('accountant.bills.index'), 'class' => 'btn-outline-primary'],
                ['label' => 'Payments', 'description' => 'Review payments', 'icon' => 'bi-credit-card', 'route' => route('accountant.payments.index'), 'class' => 'btn-outline-success'],
                ['label' => 'My Billing Report', 'description' => 'Your bill report', 'icon' => 'bi-file-earmark-text', 'route' => route('reports.finance.index'), 'class' => 'btn-outline-secondary'],
            ]);
        }

        if ($user->hasRole('administrator')) {
            $actions = $actions->merge([
                ['label' => 'Admin Panel', 'description' => 'Live admin dashboard', 'icon' => 'bi-speedometer2', 'route' => route('admin.index'), 'class' => 'btn-outline-success'],
                ['label' => 'Users', 'description' => 'Manage access', 'icon' => 'bi-people-fill', 'route' => route('admin.users.index'), 'class' => 'btn-outline-primary'],
            ]);
        }

        return $actions;
    }

    private function recentActivities(int $userId)
    {
        return AuditLog::where('actor_id', $userId)
            ->latest()
            ->limit(10)
            ->get();
    }

    private function card(string $label, mixed $value, string $icon, string $iconClass, string $description): array
    {
        return compact('label', 'value', 'icon', 'iconClass', 'description');
    }

    private function paymentsByUser(int $userId)
    {
        return Payment::where('paid_by', $userId)->where('status', 'completed');
    }

    private function departmentServiceRequests($user)
    {
        return ServiceRequest::query()
            ->whereHas('service', fn ($query) => $query->where('department_id', $user->department_id));
    }

    private function departmentInvestigationRequests($user)
    {
        return InvestigationRequest::query()
            ->whereHas('investigation.investigationType', fn ($query) => $query->where('department_id', $user->department_id));
    }

    private function safeCount(string $table, string $dateColumn): int
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, $dateColumn)) {
            return 0;
        }

        return DB::table($table)->whereDate($dateColumn, today())->count();
    }
}
