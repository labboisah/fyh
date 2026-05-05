<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Payment;
use App\Models\Bill;
use App\Models\PatientVisit;
use App\Models\InvestigationRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyReportExport;

class ReportsController extends Controller
{
    public function index()
{
        return view('reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'from_date' => 'nullable|date|before_or_equal:to_date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'format' => 'required|in:pdf,excel,word'
        ]);

        $user = Auth::user();

        if($request->from_date && $request->to_date) {
            $fromDate = Carbon::parse($request->from_date)->startOfDay();
            $toDate = Carbon::parse($request->to_date)->endOfDay();
        }

        return view('reports.show')->with([
            'date' => $request->date,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'format' => $request->format
        ]);
        
    }

    


    private function generateDailyReport(User $user, Carbon $date, array $roles)
    {
        $reportData = [
            'date' => $date->format('Y-m-d'),
            'user_name' => $user->name,
            'user_email' => $user->email,
            'roles' => $roles,
            'user_roles' => $roles,
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'sections' => []
        ];

        // Accountant activities
        if (in_array('accountant', $roles)) {
            $reportData['sections']['accountant'] = $this->getAccountantActivities($user, $date);
        }

        // Record officer activities
        if (in_array('record_officer', $roles)) {
            $reportData['sections']['record_officer'] = $this->getRecordOfficerActivities($user, $date);
        }

        // Doctor activities
        if (in_array('doctor', $roles)) {
            $reportData['sections']['doctor'] = $this->getDoctorActivities($user, $date);
        }

        // Nurse activities
        if (in_array('nurse', $roles)) {
            $reportData['sections']['nurse'] = $this->getNurseActivities($user, $date);
        }

        // Lab technician activities
        if (in_array('lab_technician', $roles)) {
            $reportData['sections']['lab_technician'] = $this->getLabTechnicianActivities($user, $date);
        }

        // Pharmacist activities
        if (in_array('pharmacist', $roles)) {
            $reportData['sections']['pharmacist'] = $this->getPharmacistActivities($user, $date);
        }

        // Radiologist activities
        if (in_array('radiologist', $roles)) {
            $reportData['sections']['radiologist'] = $this->getRadiologistActivities($user, $date);
        }

        return $reportData;
    }

    private function getAccountantActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Payments recorded
        $payments = Payment::where('recorded_by', $user->id)
            ->whereBetween('payment_date', [$startOfDay, $endOfDay])
            ->with(['bill.patientVisit.patient.demographic', 'paymentMethod'])
            ->get();

        // Bills created
        $bills = Bill::where('issued_by', $user->id)
            ->whereBetween('issued_date', [$startOfDay, $endOfDay])
            ->with(['patientVisit.patient.demographic'])
            ->get();

        // Financial summary
        $totalPayments = $payments->sum('amount');
        $totalBills = $bills->sum('total_amount');

        return [
            'payments_recorded' => $payments->count(),
            'total_payment_amount' => $totalPayments,
            'bills_created' => $bills->count(),
            'total_bill_amount' => $totalBills,
            'payments' => $payments,
            'bills' => $bills
        ];
    }

    private function getRecordOfficerActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Patient visits registered
        $patientVisits = PatientVisit::where('created_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['patient.demographic'])
            ->get();

        // Bills created (for dual-role users)
        $bills = Bill::where('issued_by', $user->id)
            ->whereBetween('issued_date', [$startOfDay, $endOfDay])
            ->with(['patientVisit.patient.demographic'])
            ->get();

        // Service requests created
        $serviceRequests = ServiceRequest::where('created_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['service', 'patientVisit.patient.demographic'])
            ->get();

        // Investigation requests created
        $investigationRequests = InvestigationRequest::where('requested_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['investigation', 'patientVisit.patient.demographic'])
            ->get();

        return [
            'patient_visits_registered' => $patientVisits->count(),
            'bills_created' => $bills->count(),
            'service_requests_created' => $serviceRequests->count(),
            'investigation_requests_created' => $investigationRequests->count(),
            'patient_visits' => $patientVisits,
            'bills' => $bills,
            'service_requests' => $serviceRequests,
            'investigation_requests' => $investigationRequests
        ];
    }

    private function getDoctorActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Patients seen (consultations)
        $consultations = PatientVisit::where('doctor_id', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['patient.demographic'])
            ->get();

        // Investigation requests ordered
        $investigationRequests = InvestigationRequest::where('requested_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['investigation', 'patientVisit.patient.demographic'])
            ->get();

        // Service requests ordered
        $serviceRequests = ServiceRequest::where('created_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['service', 'patientVisit.patient.demographic'])
            ->get();

        return [
            'consultations' => $consultations->count(),
            'investigation_requests_ordered' => $investigationRequests->count(),
            'service_requests_ordered' => $serviceRequests->count(),
            'consultations_list' => $consultations,
            'investigation_requests' => $investigationRequests,
            'service_requests' => $serviceRequests
        ];
    }

    private function getNurseActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Vital signs recorded
        $vitalSigns = DB::table('vital_signs')
            ->where('recorded_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Patients attended
        $patientVisits = PatientVisit::where('nurse_id', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->with(['patient.demographic'])
            ->get();

        return [
            'vital_signs_recorded' => $vitalSigns,
            'patients_attended' => $patientVisits->count(),
            'patient_visits' => $patientVisits
        ];
    }

    private function getLabTechnicianActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Investigation requests completed
        $investigationsCompleted = InvestigationRequest::where('performed_by', $user->id)
            ->whereBetween('completed_at', [$startOfDay, $endOfDay])
            ->with(['investigation', 'patientVisit.patient.demographic'])
            ->get();

        // Investigation requests pending
        $investigationsPending = InvestigationRequest::where('performed_by', $user->id)
            ->where('status', 'pending')
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        return [
            'investigations_completed' => $investigationsCompleted->count(),
            'investigations_pending' => $investigationsPending,
            'investigations' => $investigationsCompleted
        ];
    }

    private function getPharmacistActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Medicine prescriptions
        $prescriptions = DB::table('medicine_prescriptions')
            ->where('prescribed_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        // Medicine dispensations
        $dispensations = DB::table('medicine_dispensations')
            ->where('dispensed_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        return [
            'prescriptions_written' => $prescriptions,
            'medicines_dispensed' => $dispensations
        ];
    }

    private function getRadiologistActivities(User $user, Carbon $date)
    {
        $startOfDay = $date->copy()->startOfDay();
        $endOfDay = $date->copy()->endOfDay();

        // Radiograph requests completed
        $radiographsCompleted = DB::table('radiograph_requests')
            ->where('performed_by', $user->id)
            ->whereBetween('completed_at', [$startOfDay, $endOfDay])
            ->count();

        // Radiograph reports written
        $reportsWritten = DB::table('radiograph_reports')
            ->where('reported_by', $user->id)
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->count();

        return [
            'radiographs_completed' => $radiographsCompleted,
            'reports_written' => $reportsWritten
        ];
    }

    private function generatePDF($reportData, Carbon $date)
    {
        $pdf = Pdf::loadView('reports.pdf', compact('reportData', 'date'));

        return $pdf->download('daily-report-' . $date->format('Y-m-d') . '.pdf');
    }

    private function generateExcel($reportData, Carbon $date)
    {
        return Excel::download(new DailyReportExport($reportData), 'daily-report-' . $date->format('Y-m-d') . '.xlsx');
    }
}