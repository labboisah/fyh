<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function export(Request $request)
    {
        if ($request->boolean('today', false)) {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } else {
            $startDate = Carbon::parse($request->input('start_date', now()->startOfMonth()->format('Y-m-d')))->startOfDay();
            $endDate = Carbon::parse($request->input('end_date', now()->format('Y-m-d')))->endOfDay();
        }

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
}
