<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PatientReferral;
use App\Models\Appointment;
use App\Models\NextOfKin;
use App\Models\PatientVisit;
use App\Models\ServiceRequest;
use Auth;

class PatientController extends Controller
{
    public function index() {
        $requests = auth()->user()->pendingServiceRequests();

        return view('nurse.patient.index', compact('requests'));
    }

    public function show($patient) {
        return view('nurse.patient.show', compact('patient'));
    }

    public function complete(ServiceRequest $serviceRequest) {
        $serviceRequest->status = 'completed';
        $serviceRequest->performed_by = auth()->user()->id;
        $serviceRequest->completed_at = now();
        $serviceRequest->save();

        // log activity
        $serviceRequest->patientVisit->visitActivities()->create([
            'recorded_by' => auth()->user()->id,
            'activity' => 'Service "' . $serviceRequest->service->name . '" marked as completed by ' . auth()->user()->name,
        ]);
        return redirect()->back()->with('success', 'Service request marked as completed and bill generated.');
    }

    public function closeVisit(PatientVisit $patientVisit) {
        $patientVisit->status = 'Closed';
        $patientVisit->updated_at = now();
        $patientVisit->save();

        // log activity
        $patientVisit->visitActivities()->create([
            'recorded_by' => auth()->user()->id,
            'activity' => 'Patient visit marked as closed by ' . auth()->user()->name,
        ]);
        return redirect()->back()->with('success', 'Patient visit marked as closed.');
    }
}
