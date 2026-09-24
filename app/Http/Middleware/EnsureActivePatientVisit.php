<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActivePatientVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        $patient = $request->route('patient');

        if ($patient instanceof Patient && ! $patient->patientVisits()->where('status', 'Active')->exists()) {
            return redirect()
                ->route('patient.show', $patient)
                ->with('warning', 'This patient has no active visit. Please go to Records to create a visit and complete the visit charges and payment before accessing medical services.');
        }

        return $next($request);
    }
}
