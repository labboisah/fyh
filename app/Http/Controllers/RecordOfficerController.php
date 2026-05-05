<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientDemographic;
use App\Models\NextOfKin;
use App\Models\PatientVisit;
use App\Models\Service;
use App\Models\PatientAdmission;
use App\Models\PatientReferral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecordOfficerController extends Controller
{
    /**
     * Dashboard
     */
    public function dashboard()
    {
        $totalPatients = Patient::count();
        $totalAppointments = Appointment::where('appointment_date', '>=', now()->startOfDay())->count();
        $recentPatients = Patient::with('demographic')
            ->latest('registration_date')
            ->limit(10)
            ->get();
        $upcomingAppointments = Appointment::with('patient.demographic')
            ->where('status', '!=', 'Cancelled')
            ->where('appointment_date', '>=', now()->startOfDay())
            ->orderBy('appointment_date')
            ->limit(5)
            ->get();

        return view('record.dashboard', compact(
            'totalPatients',
            'totalAppointments',
            'recentPatients',
            'upcomingAppointments'
        ));
    }

    /**
     * Show patient registration form
     */
    public function registerForm()
    
    {
        return view('record.patient.register');
    }

    /**
     * Register new patient
     */
    public function register(Request $request)
    {
       
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'lga' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'required|string|unique:patient_demographics,phone_number',
            'email' => 'nullable|email|unique:patient_demographics,email',
            'is_walkIn' => 'boolean',
            'nok_name' => 'required|string|max:255',
            'nok_relationship' => 'required|string|max:255',
            'nok_contact_address' => 'nullable|string|max:500',
            'nok_telephone' => 'required|string|max:20',
        ]);
       
        
       
            // Create patient record
            $patient = Patient::create([
                'file_type_id'=>$request->file_type,
                'hospital_number' => Patient::generateHospitalNumber(),
                'registration_date' => now(),
                'is_walkIn' => $validated['is_walkIn'] ?? false,
            ]);

            // Calculate age
            $age = Carbon::parse($validated['date_of_birth'])->age;

            // Create demographic record
            PatientDemographic::create([
                'patient_id' => $patient->id,
                'first_name' => strtoupper($validated['first_name']),
                'last_name' => strtoupper($validated['last_name']),
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'age' => $age,
                'lga' => $validated['lga'],
                'occupation' => $validated['occupation'],
                'marital_status' => $validated['marital_status'],
                'address' => $validated['address'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
            ]);

            // Create next of kin record
            NextOfKin::create([
                'patient_id' => $patient->id,
                'name' => $validated['nok_name'],
                'relationship' => $validated['nok_relationship'],
                'contact_address' => $validated['nok_contact_address'],
                'telephone' => $validated['nok_telephone'],
            ]);
            
            
            return redirect()->route('record.patients.show', $patient->id)
                ->with('success', "Patient {$patient->demographic->full_name} registered successfully with Hospital Number: {$patient->hospital_number}");
       
    }

    /**
     * List all patients
     */
    public function listPatients()
    {
        $patients = Patient::with('demographic')
            ->latest('registration_date')
            ->paginate(15);

        return view('record.patient.list', compact('patients'));
    }

    /**
     * Show patient details
     */
    public function showPatient(Patient $patient)
    {
        $patient->load([
            'demographic',
            'nextOfKin',
            'visits',
            'appointments',
        ]);

        return view('record.patient.show', compact('patient'));
    }

    /**
     * Show edit patient form
     */
    public function editForm(Patient $patient)
    {
        $patient->load('demographic', 'nextOfKin');
        return view('record.patient.edit', compact('patient'));
    }

    /**
     * Update patient information
     */
    public function update(Request $request, Patient $patient)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date',
            'lga' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'address' => 'nullable|string|max:500',
            'phone_number' => 'required|string|unique:patient_demographics,phone_number,' . $patient->demographic->id,
            'email' => 'nullable|email|unique:patient_demographics,email,' . $patient->demographic->id,
            'nok_name' => 'required|string|max:255',
            'nok_relationship' => 'required|string|max:255',
            'nok_contact_address' => 'nullable|string|max:500',
            'nok_telephone' => 'required|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Calculate age
            $age = Carbon::parse($validated['date_of_birth'])->age;

            // Update demographic
            $patient->demographic->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'age' => $age,
                'lga' => $validated['lga'],
                'occupation' => $validated['occupation'],
                'marital_status' => $validated['marital_status'],
                'address' => $validated['address'],
                'phone_number' => $validated['phone_number'],
                'email' => $validated['email'],
            ]);

            // Update next of kin
            $patient->nextOfKin->update([
                'name' => $validated['nok_name'],
                'relationship' => $validated['nok_relationship'],
                'contact_address' => $validated['nok_contact_address'],
                'telephone' => $validated['nok_telephone'],
            ]);

            DB::commit();

            return redirect()->route('record.patients.show', $id)
                ->with('success', 'Patient information updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to update patient. ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Search patients
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return view('patient.search', ['patients' => []]);
        }

        $patients = Patient::with('demographic')
            ->where('hospital_number', 'like', "%{$query}%")
            ->orWhereHas('demographic', function ($q) use ($query) {
                $q->where('phone_number', 'like', "%{$query}%")
                  ->orWhere('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->get();

        return view('patient.search', compact('patients', 'query'));
    }

    
    /**
     * Record patient visit
     */
    public function visitForm(Patient $patient)
    {
        $patient->load('demographic');
        return view('record.visit.create', compact('patient'));
    }

    /**
     * Store patient visit
     */
    public function storeVisit(Request $request, Patient $patient)
    {

        $validated = $request->validate([
            'visit_date' => 'required',
            'visit_type' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $visit = PatientVisit::create([
                'patient_id' => $patient->id,
                'visit_date' => $validated['visit_date'],
                'visit_type' => $validated['visit_type'],
                'status' => 'Active',
                'created_by' => auth()->id(),
            ]);

            $service = Service::find($validated['visit_type']);

            $visit->generateServiceBillOf($service);

            DB::commit();
            // redirect to patient visit bill creation
            return redirect()->route('patient.show', $patient)
                ->with('success', 'Visit record created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to record visit. ' . $e->getMessage()])->withInput();
        }
    }

    
    
   
    /**
     * Wrapper: Create bill (for dual-role users)
     * Redirects to accountant bill creation with patient pre-selected
     */
    public function createBill(Patient $patient)
    {
        // Check if user has accountant role
        if (!auth()->user()->hasRole('accountant') && !auth()->user()->hasRole('record')) {
            return redirect()->back()
                ->with('error', 'You need accountant and record officer roles to generate bills.');
        }

        return redirect()->route('accountant.bills.create', ['patient_id' => $patient->id])
            ->with('info', 'Creating bill for ' . $patient->demographic->full_name);
    }

    /**
     * Wrapper: Create payment (for dual-role users)
     * Redirects to accountant payment creation with patient pre-selected
     */
    public function createPayment(Patient $patient)
    {
        // Check if user has accountant role
        if (!Auth::user()->hasRole('accountant')) {
            return redirect()->back()
                ->with('error', 'You need accountant role to record payments.');
        }

        return redirect()->route('accountant.payments.create', $patient)
            ->with('info', 'Recording payment for ' . $patient->demographic->full_name);
    }

    /**
     * Generate PDF export
     */
    private function generatePDF($patient)
    {
        // Placeholder for PDF generation
        // You can use libraries like DomPDF or TCPDF
        $content = "Patient Record Export\n";
        $content .= "Hospital Number: " . $patient->hospital_number . "\n";
        $content .= "Name: " . $patient->demographic->full_name . "\n";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, 'patient_' . $patient->hospital_number . '.txt');
    }
}
