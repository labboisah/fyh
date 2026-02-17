<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BillService;
use Illuminate\Support\Facades\DB;


class AccountantController extends Controller
{
    /**
     * Accountant dashboard with financial overview.
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Financial metrics
        $totalBills = Bill::sum('amount');
        $paidBills = Bill::where('status', 'paid')->sum('amount');
        $partialBills = Bill::where('status', 'partial')->sum('amount');
        $pendingBills = Bill::where('status', 'pending')->sum('amount');

        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $todayPayments = Payment::where('status', 'completed')
            ->whereDate('payment_date', $today)
            ->sum('amount');
        $monthlyPayments = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$thisMonth, now()])
            ->sum('amount');

        // Outstanding bills
        $outstandingBills = Bill::whereIn('status', ['pending', 'partial'])->count();

        // Recent transactions
        $recentPayments = Payment::with(['bill', 'patient', 'recordedBy'])
            ->latest('payment_date')
            ->limit(5)
            ->get();

        $recentBills = Bill::with(['patient', 'issuedBy'])
            ->latest('issued_date')
            ->limit(5)
            ->get();

        return view('accountant.dashboard', compact(
            'totalBills',
            'paidBills',
            'partialBills',
            'pendingBills',
            'totalPayments',
            'todayPayments',
            'monthlyPayments',
            'outstandingBills',
            'recentPayments',
            'recentBills'
        ));
    }

    /**
     * Show form for creating a new bill.
     */
    public function createBill(Request $request, Patient $patient)
    {
       
        $services = Service::active()->get()->groupBy('category');
        
        // Pre-select patient if provided from quick action
       
        return view('accountant.bills.create', compact('patient', 'services'));
    }

    /**
     * Store a newly created bill.
     */
    public function storeBill(Request $request)
    {
        $validated = $request->validate([
            'patient_visit_id' => 'required|exists:patient_visits,id',
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:services,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after:issued_date',
        ]);

        $issued_by = Auth::id();
        $status = 'pending';

        // Calculate total amount from services
        $totalAmount = 0;
        $billServices = [];

        foreach ($validated['services'] as $service) {
            $svc = Service::findOrFail($service['id']);
            $totalAmount += $svc->price;

            // Allow same service to be added multiple times - each adds to billServices
            if (isset($billServices[$svc->id])) {
                // If service already exists, increase the subtotal
                $billServices[$svc->id]['quantity'] += 1;
                $billServices[$svc->id]['subtotal'] += $svc->price;
            } else {
                $billServices[$svc->id] = [
                    'quantity' => 1,
                    'unit_price' => $svc->price,
                    'subtotal' => $svc->price,
                ];
            }
        }

        // Create bill
        $bill = Bill::create([
            'patient_visit_id' => $validated['patient_visit_id'],
            'bill_number' => Bill::generateBillNumber(),
            'service_description' => 'Multiple services',
            'amount' => $totalAmount,
            'issued_by' => $issued_by,
            'status' => $status,
            'issued_date' => $validated['issued_date'],
            'due_date' => $validated['due_date'],
        ]);

        // Attach services to bill
        $bill->services()->attach($billServices);

        return redirect()->route('accountant.bills.show', $bill)
            ->with('success', 'Bill created successfully.');
    }

    /**
     * Display all bills.
     */
    public function listBills()
    {
        $bills = Bill::with(['patient', 'issuedBy'])
            ->latest('issued_date')
            ->paginate(25);

        return view('accountant.bills.index', compact('bills'));
    }

    /**
     * Display a specific bill.
     */
    public function showBill(Bill $bill)
    {
        $bill->load(['patientVisit', 'issuedBy', 'payments']);
        return view('accountant.bills.show', compact('bill'));
    }

    /**
     * Show form for editing a bill.
     */
    public function editBill(Bill $bill)
    {
        $patients = Patient::orderBy('hospital_number')->get();
        return view('accountant.bills.edit', compact('bill', 'patients'));
    }

    /**
     * Update a bill.
     */
    public function updateBill(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'service_description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0.01',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after:issued_date',
            'status' => 'required|in:pending,paid,partial,cancelled',
        ]);

        $bill->update($validated);

        return redirect()->route('accountant.bills.show', $bill)
            ->with('success', 'Bill updated successfully.');
    }

    /**
     * Delete a bill.
     */
    public function deleteBill(Bill $bill)
    {
        $bill->delete();
        return redirect()->route('accountant.bills.index')
            ->with('success', 'Bill deleted successfully.');
    }

    /**
     * Show form for recording a payment.
     */
    public function createPayment(Request $request, Bill $bill = null)
    {
        
       
        $paymentMethods = ['Cash', 'Card', 'Bank Transfer', 'NHIS', 'Private Insurance'];

        

        // Pre-select patient if patient_id is provided (from quick action)
        
        
        return view('accountant.payments.create', compact('bill', 'paymentMethods'));
    }


    /**
     * Store a newly recorded payment.
     */
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:Cash,Card,Bank Transfer,NHIS,Private Insurance',
            'insurance_provider' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
        ]);

        $validated['paid_by'] = Auth::id();
        $validated['status'] = 'completed';
        $validated['payment_id'] = Payment::generatePaymentID();
        $validated['bill_id'] = $request->input('bill_id'); // Optional, if payment is linked to a bill

        $payment = Payment::create($validated);

        // Update bill status if bill_id provided
        if ($validated['bill_id']) {
            $bill = Bill::find($validated['bill_id']);
            $totalPaid = $bill->totalPaid() + $validated['amount'];

            if ($totalPaid >= $bill->amount) {
                $bill->update(['status' => 'paid']);
            } else {
                $bill->update(['status' => 'partial']);
            }
        }

        // Load relationships for receipt
        $payment->load(['bill', 'recordedBy']);

        return redirect()->route('accountant.payment-receipt', $payment)
            ->with('success', 'Payment recorded successfully. Payment ID: ' . $payment->payment_id);
    }

    /**
     * Display payment receipt.
     */
    public function paymentReceipt(Payment $payment)
    {
        $payment->load(['bill.services', 'patient', 'recordedBy']);
        return view('accountant.payments.receipt', compact('payment'));
    }

    /**
     * Display payment history.
     */
    public function listPayments()
    {
        $payments = Payment::with(['bill', 'patient', 'recordedBy'])
            ->latest('payment_date')
            ->paginate(25);

        return view('accountant.payments.index', compact('payments'));
    }

    /**
     * Display payment history for a specific patient.
     */
    public function patientPaymentHistory(Patient $patient)
    {
        $visits = $patient->patientVisits()
            ->with('bills')
            ->latest('visit_date')
            ->paginate(15);

        return view('accountant.payments.patient-history', compact('visits', 'patient'));
    }

    /**
     * Show insurance billing management.
     */
    public function insuranceBilling()
    {
        $insurancePayments = Payment::where('payment_method', 'NHIS')
            ->orWhere('payment_method', 'Private Insurance')
            ->with(['patient', 'bill', 'recordedBy'])
            ->latest('payment_date')
            ->paginate(25);

        // Insurance statistics
        $nhisPayments = Payment::where('payment_method', 'NHIS')
            ->where('status', 'completed')
            ->sum('amount');

        $privateInsurancePayments = Payment::where('payment_method', 'Private Insurance')
            ->where('status', 'completed')
            ->sum('amount');

        return view('accountant.billing.insurance', compact(
            'insurancePayments',
            'nhisPayments',
            'privateInsurancePayments'
        ));
    }

    /**
     * Generate financial report.
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        // Revenue by payment method
        $revenueByMethod = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Revenue by patient
        $revenueByPatient = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->with('patient')
            ->selectRaw('patient_id, SUM(amount) as total')
            ->groupBy('patient_id')
            ->orderByRaw('total DESC')
            ->limit(20)
            ->get();

        // Insurance claims
        $insuranceClaims = Payment::whereIn('payment_method', ['NHIS', 'Private Insurance'])
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('insurance_provider, payment_method, SUM(amount) as total')
            ->groupBy('insurance_provider', 'payment_method')
            ->get();

        // Overall statistics
        $totalRevenue = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        $totalBillsGenerated = Bill::whereBetween('issued_date', [$startDate, $endDate])
            ->sum('amount');

        $totalOutstanding = Bill::whereIn('status', ['pending', 'partial'])
            ->sum('amount');

        return view('accountant.reports.financial', compact(
            'revenueByMethod',
            'revenueByPatient',
            'insuranceClaims',
            'totalRevenue',
            'totalBillsGenerated',
            'totalOutstanding',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Export financial report as CSV.
     */
    public function exportFinancialReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', now());

        $payments = Payment::where('status', 'completed')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->with(['patient', 'bill', 'recordedBy'])
            ->get();

        $filename = 'financial-report-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        // Add header
        fputcsv($handle, [
            'Payment ID',
            'Patient',
            'Amount',
            'Payment Method',
            'Insurance Provider',
            'Reference No.',
            'Paid By',
            'Payment Date'
        ]);

        // Add data
        foreach ($payments as $payment) {
            fputcsv($handle, [
                $payment->payment_id,
                $payment->patient->name,
                $payment->amount,
                $payment->payment_method,
                $payment->insurance_provider ?? '-',
                $payment->reference_number ?? '-',
                $payment->recordedBy->name,
                $payment->payment_date->format('Y-m-d'),
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
