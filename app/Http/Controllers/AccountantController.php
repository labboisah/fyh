<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\Investigation;
use App\Models\InvestigationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BillService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\WalkinPatient;
use App\Models\ServiceRequest;


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
    public function createBill(Request $request)
    {
        $services = Service::active()->get()->groupBy('category');
        $investigations = Investigation::with('investigationType')->get()
            ->groupBy(fn ($investigation) => $investigation->investigationType?->name ?? 'Other');
        

        // Pre-select patient if provided from quick action
        return view('accountant.bills.create', compact('services', 'investigations'));
    }

    /**
     * Show form for creating a walk-in bill.
     */
    public function createWalkinBill()
    {
        $services = Service::active()->get()->groupBy('category');
        $patients = Patient::with('demographic')->latest()->limit(100)->get();
        return view('accountant.bills.create_walkin', compact('services', 'patients'));
    }

    /**
     * Store a newly created bill.
     */
    public function storeBill(Request $request)
    {
        $validated = $request->validate([
            'hospital_number' => 'nullable|string|max:100',
            'walkin_name' => 'nullable|string|max:255',
            'walkin_phone' => 'nullable|string|max:20',
            'walkin_email' => 'nullable|email|max:255',
            'services' => 'nullable|array',
            'services.*.id' => 'nullable|exists:services,id',
            'investigations' => 'nullable|array',
            'investigations.*.id' => 'nullable|exists:investigations,id',
            'issued_date' => 'required|date',
            'due_date' => 'required|date',
        ]);

        $services = collect($validated['services'] ?? [])->filter(function ($service) {
            return !empty($service['id']);
        })->values()->all();

        $selectedInvestigations = collect($validated['investigations'] ?? [])->filter(function ($investigation) {
            return !empty($investigation['id']);
        })->values()->all();

        if (empty($services) && empty($selectedInvestigations)) {
            return back()->withErrors(['error' => 'Select at least one service or investigation.'])->withInput();
        }

        // Ensure either patient_visit_id or walkin details are provided
        if (empty($validated['hospital_number']) && empty($validated['walkin_name'])) {
            return back()->withErrors(['error' => 'Either Enter Hospital Number or provide walk-in patient details.']);
        }

        $issued_by = Auth::id();
        $status = 'pending';
        $walkinId = null;
        $patient = Patient::where('hospital_number', $validated['hospital_number'])->first();
        
        if ($patient) {
            $visit = $patient->currentVisit();
            if (!$visit) {
                $visit = $patient->patientVisits()->create([
                    'visit_date' => now(),
                    'visit_type' => 'Walk-in',
                    'created_by' => $issued_by,
                    'reason_for_visit' => 'Walk-in bill creation',
                ]);
            }
        }else if (!empty($validated['walkin_name'])) {
            $walkinPatient = WalkinPatient::create([
                'name' => $validated['walkin_name'],
                'phone_number' => $validated['walkin_phone'] ?? 'something',
                'address' => $validated['walkin_email'] ?? null,
            ]);
            $walkinId = $walkinPatient->id;
        }

        // Calculate total amount from services and investigations
        $totalAmount = 0;
        $billServices = [];
        $billInvestigations = [];

        foreach ($services as $service) {
            $svc = Service::findOrFail($service['id']);
            $totalAmount += $svc->price;

            // Allow same service to be added multiple times - each adds to billServices
            if (isset($billServices[$svc->id])) {
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

        $investigationNames = [];

        foreach ($selectedInvestigations as $investigationData) {
            $investigation = Investigation::findOrFail($investigationData['id']);
            $totalAmount += $investigation->price;
            $investigationNames[] = $investigation->name;

            if (isset($billInvestigations[$investigation->id])) {
                $billInvestigations[$investigation->id]['quantity'] += 1;
                $billInvestigations[$investigation->id]['subtotal'] += $investigation->price;
            } else {
                $billInvestigations[$investigation->id] = [
                    'quantity' => 1,
                    'unit_price' => $investigation->price,
                    'subtotal' => $investigation->price,
                ];
            }
        }

        $descriptionParts = [];
        if (!empty($services)) {
            $descriptionParts[] = 'Services';
        }
        if (!empty($selectedInvestigations)) {
            $descriptionParts[] = 'Investigations';
        }

        // Create bill
        $bill = Bill::create([
            'patient_visit_id' => $visit->id ?? null,
            'walkin_id' => $walkinId ?? null,
            'bill_number' => Bill::generateBillNumber(),
            'service_description' => implode(' & ', $descriptionParts) ?: 'Bill items',
            'amount' => $totalAmount,
            'issued_by' => $issued_by,
            'status' => $status,
            'issued_date' => $validated['issued_date'],
            'due_date' => $validated['due_date'],
        ]);

        // Attach services to bill
        $bill->services()->attach($billServices);

        $bill->investigations()->attach($billInvestigations);

        // Create investigation requests for lab/radiology services and selected investigations
        $this->createInvestigationRequests($bill, $services, $selectedInvestigations);



        return redirect()->route('accountant.bills.show', $bill)
            ->with('success', 'Bill created successfully.');
    }

            
   

    public function createPaymentForBill(Bill $bill) {
        return view('accountant.bills.payments.create', compact('bill'));
    }

    public function storePaymentForBill(Request $request, Bill $bill) {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method_id' => 'required',
            'payment_date' => 'required|date',
        ]);

        $data = [];
        $data['paid_by'] = Auth::id();
        $data['status'] = 'completed';
        $data['payment_id'] = Payment::generatePaymentID();
        $data['payment_date'] = date('d M, Y');
        $data['bill_id'] = $bill->id;
        $data['payment_method_id'] = $validated['payment_method_id'];
        $data['amount'] = $validated['amount'];
        
        $payment = Payment::create($data);

        // Update bill status
        $totalPaid = $bill->totalPaid();

        $settlementAmount = $totalPaid;

        
        // fetch each service in the bill and update their payment status when payable by the pain amount
        foreach ($bill->serviceRequests as $serviceRequest) {
            if ($settlementAmount >= $serviceRequest->service->price) {
                $serviceRequest->update(['payment_status' => 'paid']);
                $settlementAmount -= $serviceRequest->service->price;
            } else {
                if($settlementAmount > 0){
                    $serviceRequest->update(['payment_status' => 'partial']);
                    $settlementAmount = 0;
                }else{
                    $serviceRequest->update(['payment_status' => 'pending']);
                }
            }
        }

        // fetch each investigation in the bill and update their payment status when payable by the pain amount
        foreach ($bill->investigationRequests as $investigationRequest) {
            if ($settlementAmount >= $investigationRequest->investigation->price) {
                $investigationRequest->update(['payment_status' => 'paid']);
                $settlementAmount -= $investigationRequest->investigation->price;
            } else {
                if($settlementAmount > 0){
                    $investigationRequest->update(['payment_status' => 'partial']);
                    $settlementAmount = 0;
                }else{
                    $investigationRequest->update(['payment_status' => 'pending']);
                }
            }
        }

        

        if ($totalPaid >= $bill->amount) {
            $bill->update(['status' => 'paid']);
        } else {
            $bill->update(['status' => 'partial']);
        }

        // Load relationships for receipt
        $payment->load(['bill', 'recordedBy']);

        return redirect()->route('accountant.payments.receipt', $payment)
            ->with('success', 'Payment recorded successfully. Payment ID: ' . $payment->payment_id);
    }

    public function verifyBill() {
        return view('accountant.bills.payments.verify');
    }

    public function verifyBillNow(Request $request) {
        $validated = $request->validate([
            'bill_no' => 'required|string|exists:bills,bill_number',
        ]);

        $bill = Bill::where('bill_number', $validated['bill_no'])->first();
        if(!$bill){
            return back()->withErrors(['error' => 'Bill not found with the provided bill number.'])->withInput();
        }

        if($bill->status == 'paid'){
            return back()->withSuccess(['error' => 'This bill has already been fully paid.'])->withInput();
        }

         // Redirect to payment creation form with bill details 

        return redirect()->route('accountant.bills.payments.create', ['bill' => $bill]);
    }

    /**
     * Create investigation requests for lab and radiology services
     */
    private function createInvestigationRequests(Bill $bill, array $services, array $investigations = [])
    {
        $investigationCategories = ['Laboratory', 'Imaging'];

        foreach ($services as $serviceData) {
            $service = Service::findOrFail($serviceData['id']);

            if ($service) {
                $serviceRequest = ServiceRequest::create([
                    'service_id' => $service->id,
                    'patient_visit_id' => $bill->patient_visit_id,
                    'walkin_id' => $bill->walkin_id,
                    'bill_id' => $bill->id,
                    'requested_by' => $bill->issued_by,
                    'requested_at' => now(),
                    'status' => 'pending',
                    'priority' => 'normal',
                    'clinical_diagnoses' => 'Requested via billing system',
                ]);
            }
           
        }

        foreach ($investigations as $investigationData) {
            $investigation = Investigation::findOrFail($investigationData['id']);

            $investigationRequest = InvestigationRequest::create([
                'investigation_id' => $investigation->id,
                'patient_visit_id' => $bill->patient_visit_id,
                'walkin_id' => $bill->walkin_id,
                'bill_id' => $bill->id,
                'requested_by' => $bill->issued_by,
                'requested_at' => now(),
                'status' => 'pending',
                'clinical_diagnoses' => 'Requested via billing system',
            ]);

            if (!$bill->investigation_request_id) {
                $bill->update(['investigation_request_id' => $investigationRequest->id]);
            }
        }
    }

    /**
     * Display all bills.
     */
    public function listBills()
    {
        $bills = Bill::with(['patientVisit', 'walkinPatient', 'issuedBy'])
            ->latest('issued_date')
            ->paginate(25);

        return view('accountant.bills.index', compact('bills'));
    }

    /**
     * Display a specific bill.
     */
    public function showBill(Bill $bill)
    {
        $bill->load(['patientVisit.patient', 'walkinPatient', 'issuedBy', 'payments', 'services']);
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
    public function createPayment(Request $request, Patient $patient = null)
    {
        
       
    

        // Pre-select patient if patient_id is provided (from quick action)
        $paymentMethods = PaymentMethod::all();
        
        return view('accountant.payments.create', compact('patient', 'paymentMethods'));
    }


    /**
     * Store a newly recorded payment.
     */
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required',
            'insurance_provider' => 'nullable|string|max:100',
            'payment_date' => 'required|date',
        ]);
        $patient = Patient::find($request->patient_id);

        $payableAmout = $request->amount;
        foreach($patient->patientVisits as $visit){
            foreach($visit->bills as $bill){
                if($bill->getBalanceAttribute() > 0 && $payableAmout > 0){
                    $data = [];
                    $data['paid_by'] = Auth::id();
                    $data['status'] = 'completed';
                    $data['payment_id'] = Payment::generatePaymentID();
                    $data['payment_date'] = date('d M, Y');
                    $data['bill_id'] = $bill->id;
                    $data['payment_method_id'] = $request->payment_method;
                    
                    $billPendingPayment = $bill->getBalanceAttribute();

                    if($payableAmout > $billPendingPayment){
                        $data['amount'] = $billPendingPayment;
                        $payableAmout -= $billPendingPayment;
                    }else{
                        $data['amount'] = $payableAmout;
                    }
                    

                    $payment = Payment::firstOrCreate($data);

                    // Update bill status if bill_id provided
                    
                    $totalPaid = $bill->totalPaid();

                    if ($totalPaid >= $bill->amount) {
                        $bill->update(['status' => 'paid']);
                    } else {
                        $bill->update(['status' => 'partial']);
                    }
                    
                }
            }
        }
        // Load relationships for receipt
        
        $payment->load(['bill', 'recordedBy']);

        return redirect()->route('accountant.payments.receipt', $payment)
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
            ->selectRaw('insurance_provider, SUM(amount) as total')
            ->groupBy('insurance_provider')
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
