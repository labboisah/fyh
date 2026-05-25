<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\Ward;
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
     * Return patient details for a hospital number.
     */
    public function patientDetailsByHospitalNumber(Request $request)
    {
        $hospitalNumber = $request->query('hospital_number');

        if (!$hospitalNumber) {
            return response()->json(['found' => false]);
        }

        $patient = Patient::with('demographic')
            ->where('hospital_number', 'like', "%{$hospitalNumber}%")
            ->orderByRaw('LENGTH(hospital_number) ASC')
            ->first();

        if (!$patient) {
            return response()->json(['found' => false]);
        }

        return response()->json([
            'found' => true,
            'hospital_number' => $patient->hospital_number,
            'name' => $patient->demographic?->full_name,
            'phone' => $patient->demographic?->phone_number,
            'email' => $patient->demographic?->email,
            'address' => $patient->demographic?->address,
        ]);
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
            'discount' => 'required|numeric|min:0|max:100',
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
            return is_array($investigation) && !empty($investigation['id']);
        })->map(function ($investigation) {
            return ['id' => $investigation['id']];
        })->values()->all();

        if (empty($services) && empty($selectedInvestigations)) {
            return back()->withErrors(['error' => 'Select at least one service or investigation.'])->withInput();
        }

        // Ensure either patient_visit_id or walkin details are provided
        if (empty($validated['hospital_number']) && empty($validated['walkin_name'])) {
            return back()->withErrors(['error' => 'Either Enter Hospital Number or provide walk-in patient details.']);
        }
        $visit = null;
        $issued_by = Auth::id();
        $status = 'pending';
        $walkinId = null;
        $patient = Patient::where('hospital_number', $validated['hospital_number'])->first();
        $discount = $validated['discount'] ?? 0;

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
            // send department service request
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
            // send department service request
            if(!$visit){
                $visit= $walkinPatient;
            }

            $visit->departmentServiceRequests()->create([
                'requested_by'=>auth()->user()->id,
                'department_id'=>$svc->department_id ?? null,
                'service_id'=>$svc->id,
                'status'=>'pending'
            ]);
            // log activities

            
            // if service is labour admit a patient

            if($patient && ($svc->id == 13 || $svc->id == 14)){
                // admit the patient
                $visit = $patient->currentVisit();
                if(!$visit){
                    $visit = $patient->patientVisits()->create([
                        'visit_date' => now(),
                        'visit_type' => 'Labour',
                        'created_by' => $issued_by,
                        'reason_for_visit' => 'labour bill creation',
                    ]);
                }

                $ward = Ward::find(2);

                $visit->admissions()->create([
                    'date' => now(),
                    'time' => now()->toTimeString(),
                    'note' => $svc->name,
                    'bed_id' => $ward->getAvailableBed()->id ?? null,
                    'status' => 'Registered',
                    'admitted_by' => auth()->user()->id,
                ]);

                // generate another bill for bed space bill for the patient apply discount here
                $bedDiscountAmount = round($ward->price * ($discount / 100), 2);
                $bedBillAmount = max(0, $ward->price - $bedDiscountAmount);
                Bill::create([
                    'patient_visit_id' => $visit->id ?? null,
                    'walkin_id' => $walkinId ?? null,
                    'bill_number' => Bill::generateBillNumber(),
                    'service_description' => 'Bed space charge for ' . $svc->name,
                    'amount' => $ward->price,
                    'due_amount' => $bedBillAmount,
                    'discount' => $discount,
                    'issued_by' => auth()->user()->id,
                    'status' => 'pending',
                    'issued_date' => now(),
                    'due_date' => now()->addDays(7),
                ]);

            }

            // create bill for bed space charges
            
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

        
        $discountAmount = round($totalAmount * ($discount / 100), 2);
        $billAmount = max(0, $totalAmount - $discountAmount);

        $bill = null;

        DB::transaction(function () use (&$bill, $visit, $walkinId, $descriptionParts, $totalAmount, $billAmount, $discount, $issued_by, $status, $validated, $billServices, $billInvestigations, $services, $selectedInvestigations) {
            $bill = Bill::create([
                'patient_visit_id' => $visit->id ?? null,
                'walkin_id' => $walkinId ?? null,
                'bill_number' => Bill::generateBillNumber(),
                'service_description' => implode(' & ', $descriptionParts) ?: 'Bill items',
                'amount' => $totalAmount,
                'due_amount' => $billAmount,
                'discount' => $discount,
                'issued_by' => $issued_by,
                'status' => $status,
                'issued_date' => $validated['issued_date'],
                'due_date' => $validated['due_date'],
            ]);

            foreach ($billServices as $serviceId => $servicePayload) {
                $bill->services()->attach($serviceId, $servicePayload);
            }

            foreach ($billInvestigations as $investigationId => $investigationPayload) {
                $bill->investigations()->attach($investigationId, $investigationPayload);
            }

            $this->createInvestigationRequests($bill, $services, $selectedInvestigations);
        });



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
        $bill->refreshRequestPaymentStatuses();

        if ($totalPaid >= $bill->due_amount) {
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

        $investigations = collect($investigations)
            ->filter(fn($investigation) => is_array($investigation) && !empty($investigation['id']))
            ->map(fn($investigation) => ['id' => $investigation['id']])
            ->values()
            ->all();

        if (empty($investigations)) {
            $investigations = $bill->investigations()->pluck('investigations.id')->map(fn($id) => ['id' => $id])->values()->all();
        }

        foreach ($services as $serviceData) {
            $service = Service::findOrFail($serviceData['id']);

            if ($service) {
                ServiceRequest::create([
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
        // bills created by the accountant today
        $bills = auth()->user()->bills()->whereDate('issued_date', Carbon::today())->with(['patientVisit.patient', 'walkinPatient'])->latest()->paginate(25);

        return view('accountant.bills.index', compact('bills'));
    }

    /**
     * Display a specific bill.
     */
    public function showBill(Bill $bill)
    {
        $bill->load(['patientVisit.patient', 'walkinPatient', 'issuedBy', 'payments', 'services', 'investigations']);
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
            'discount' => 'required|numeric|min:0|max:100',
            'issued_date' => 'required|date',
            'due_date' => 'required|date|after:issued_date',
            'status' => 'required|in:pending,paid,partial,cancelled',
        ]);

        $validated['due_amount'] = round($validated['amount'] * (1 - ($validated['discount'] / 100)), 2);
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
                    $bill->refreshRequestPaymentStatuses();

                    if ($totalPaid >= $bill->due_amount) {
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


}
