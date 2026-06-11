<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Revenue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentReportController extends Controller
{
    public function export(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);

        $payments = Payment::query()
            ->with(['bill.patientVisit.patient.demographic', 'bill.walkinPatient', 'paymentMethod', 'recordedBy'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('method'), fn ($query) => $query->where('payment_method_id', $request->input('method')))
            ->when($request->filled('recorded_by'), fn ($query) => $query->where('paid_by', $request->input('recorded_by')))
            ->latest('payment_date')
            ->get();

        $filename = 'payment-report-' . now()->format('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, [
            'Payment ID',
            'Bill Number',
            'Patient',
            'Hospital Number',
            'Amount',
            'Payment Method',
            'Status',
            'Reference Number',
            'Insurance Provider',
            'Recorded By',
            'Payment Date',
        ]);

        foreach ($payments as $payment) {
            $patientName = $payment->bill?->walkinPatient?->name
                ?? $payment->bill?->patientVisit?->patient?->name()
                ?? 'Unknown';

            fputcsv($handle, [
                $payment->payment_id,
                $payment->bill?->bill_number,
                $patientName,
                $payment->bill?->patientVisit?->patient?->hospital_number,
                number_format($payment->amount, 2),
                $payment->paymentMethod?->name,
                ucfirst($payment->status),
                $payment->reference_number,
                $payment->insurance_provider,
                $payment->recordedBy?->name,
                $payment->payment_date?->format('Y-m-d H:i:s'),
            ]);
        }

        rewind($handle);

        return response(stream_get_contents($handle), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

    public function pdf(Request $request)
    {
        [$startDate, $endDate] = $this->dateRange($request);

        $payments = Payment::query()
            ->with(['bill.patientVisit.patient.demographic', 'bill.walkinPatient', 'paymentMethod', 'recordedBy'])
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->when($request->filled('method'), fn ($query) => $query->where('payment_method_id', $request->input('method')))
            ->when($request->filled('recorded_by'), fn ($query) => $query->where('paid_by', $request->input('recorded_by')))
            ->latest('payment_date')
            ->get();

        $revenues = Revenue::query()
            ->with(['category', 'department', 'createdBy'])
            ->whereBetween('revenue_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($request->filled('recorded_by'), fn ($query) => $query->where('created_by', $request->input('recorded_by')))
            ->latest('revenue_date')
            ->get();

        $expenses = Expense::query()
            ->with(['category', 'department', 'createdBy'])
            ->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->when($request->filled('recorded_by'), fn ($query) => $query->where('created_by', $request->input('recorded_by')))
            ->latest('expense_date')
            ->get();

        $completedAmount = $payments->where('status', 'completed')->sum('amount');

        $summary = [
            'payment_count' => $payments->count(),
            'completed_amount' => $completedAmount,
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
            'reversed_amount' => $payments->where('status', 'reversed')->sum('amount'),
            'total_revenue' => $revenues->sum('amount'),
            'total_expense' => $expenses->sum('amount'),
            'net_position' => $completedAmount + $revenues->sum('amount') - $expenses->sum('amount'),
        ];

        $hospital = $this->hospitalHeaderData();
        $generatedBy = $request->user();

        $pdf = Pdf::loadView('reports.pdf.payment-report', compact('payments', 'revenues', 'expenses', 'summary', 'startDate', 'endDate', 'hospital', 'generatedBy'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('payment-report-' . now()->format('Y-m-d') . '.pdf');
    }

    private function dateRange(Request $request): array
    {
        if ($request->boolean('today', false)) {
            return [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()];
        }

        $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
        $endDate = Carbon::parse($request->input('end_date', now()->format('Y-m-d')))->endOfDay();

        if ($startDate->gt($endDate)) {
            return [$endDate->copy()->startOfDay(), $startDate->copy()->endOfDay()];
        }

        return [$startDate, $endDate];
    }

    private function hospitalHeaderData(): array
    {
        return [
            'name' => strtoupper(config('app.title', config('app.name', 'FAYHOS'))),
            'address' => strtoupper(config('app.address', '')),
            'logo' => public_path('images/logo.png'),
        ];
    }
}
