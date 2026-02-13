<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PatientReferral;
use App\Models\Appointment;
use App\Models\NextOfKin;

class PatientController extends Controller
{
    public function index() {
        return view('nurse.patient.index');
    }

    public function show(Patient $patient) {
        
        return view('nurse.patient.show', compact('patient'));
    }   

    public function history(Patient $patient) {

        $visits = $patient->visits()->paginate(10);

        return view('nurse.patient.history', compact('patient', 'visits'));
    }
}
