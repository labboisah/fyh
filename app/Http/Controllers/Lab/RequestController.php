<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestigationRequest;

class RequestController extends Controller
{
    public function index()
    {
        $requests = auth()->user()->department->investigationRequests()
            ->orderByDesc('created_at');

        $requestGroups = $requests->groupBy(function ($request) {
            if ($request->patient_visit_id) {
                return 'visit_'.$request->patient_visit_id;
            }

            if ($request->walkin_id) {
                return 'walkin_'.$request->walkin_id;
            }

            return 'request_'.$request->id;
        })->map(function ($group, $groupKey) {
            $first = $group->first();
            $groupType = $first->patient_visit_id ? 'visit' : 'walkin';
            $groupId = $groupType === 'visit' ? $first->patient_visit_id : $first->walkin_id;
            $patientName = $first->patientVisit
                ? $first->patientVisit->patient->demographic->full_name
                : ($first->walkinPatient->name ?? 'Walkin Patient');
            $investigations = $group->pluck('investigation.name')->unique()->implode(', ');
            $completedAt = $group->filter(fn ($req) => $req->completed_at)->sortByDesc('completed_at')->first()?->completed_at;
            $requestedAt = $group->sortBy('created_at')->first()->created_at;
            $performedBy = $group->pluck('performedBy.name')->filter()->unique()->implode(', ');
            $status = $group->contains(fn ($req) => $req->status !== 'Completed') ? 'Pending' : 'Completed';
            $paymentStatus = $group->contains(fn ($req) => $req->payment_status !== 'paid') ? 'Payment not recorded' : 'Paid';
            $hasPendingResults = $group->where('payment_status', 'paid')->where('status', '!=', 'Completed')->isNotEmpty();
            $hasCompletedResults = $group->where('status', 'Completed')->isNotEmpty();

            return (object) [
                'group_type' => $groupType,
                'group_id' => $groupId,
                'lab_no' => $first->getLabNo(),
                'requested_by' => $first->requestedBy->name,
                'patient_name' => $patientName,
                'investigations' => $investigations,
                'completed_at' => $completedAt,
                'performed_by' => $performedBy,
                'status' => $status,
                'clinical_notes' => $group->pluck('clinical_diagnoses')->filter()->unique()->implode(', '),
                'requested_at' => $requestedAt,
                'payment_status' => $paymentStatus,
                'has_pending_results' => $hasPendingResults,
                'has_completed_results' => $hasCompletedResults,
            ];
        });

        $requestGroups = $requestGroups->sortByDesc('requested_at');

        return view('lab.request.index', compact('requestGroups'));
    }

    public function createResult(string $groupType, $groupId)
    {
        $query = InvestigationRequest::with([
            'investigation.parameters',
            'patientVisit.patient.demographic',
            'walkinPatient',
        ]);

        if ($groupType === 'visit') {
            $query->where('patient_visit_id', $groupId);
        } elseif ($groupType === 'walkin') {
            $query->where('walkin_id', $groupId);
        } else {
            abort(404);
        }

        $investigationRequests = $query
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'Completed')
            ->get();

        if ($investigationRequests->isEmpty()) {
            return redirect()->route('lab.requests.index')->with('error', 'No paid pending investigation requests found for this patient.');
        }

        $patientName = $investigationRequests->first()->patientVisit
            ? $investigationRequests->first()->patientVisit->patient->demographic->full_name
            : ($investigationRequests->first()->walkinPatient->name ?? 'Walkin Patient');

        $hospitalNumber = $investigationRequests->first()->patientVisit
            ? $investigationRequests->first()->patientVisit->patient->hospital_number
            : null;

        return view('lab.request.create-result', compact(
            'investigationRequests',
            'groupType',
            'groupId',
            'patientName',
            'hospitalNumber'
        ));
    }

    public function storeResult(Request $request, string $groupType, $groupId)
    {
        $validated = $request->validate([
            'parameters' => 'required|array',
            'parameters.*' => 'array',
            'parameters.*.*' => 'nullable|string',
        ]);

        $query = InvestigationRequest::with('patientVisit', 'investigation.parameters');

        if ($groupType === 'visit') {
            $query->where('patient_visit_id', $groupId);
        } elseif ($groupType === 'walkin') {
            $query->where('walkin_id', $groupId);
        } else {
            abort(404);
        }

        $investigationRequests = $query
            ->where('payment_status', 'paid')
            ->where('status', '!=', 'Completed')
            ->get();

        foreach ($investigationRequests as $investigationRequest) {
            $requestParameters = $validated['parameters'][$investigationRequest->id] ?? [];
            $hasResult = false;

            foreach ($requestParameters as $parameterId => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $investigationRequest->investigationResults()->updateOrCreate(
                    ['parameter_id' => $parameterId],
                    ['value' => $value]
                );

                $hasResult = true;
            }

            if ($hasResult) {
                $investigationRequest->update([
                    'performed_by' => auth()->id(),
                    'completed_at' => now(),
                    'status' => 'Completed',
                ]);

                if ($investigationRequest->patientVisit) {
                    $investigationRequest->patientVisit->visitActivities()->create([
                        'activity' => "Lab results recorded for {$investigationRequest->investigation->name}",
                        'recorded_by' => auth()->id(),
                    ]);
                }
            }
        }

        return redirect()->route('lab.requests.index')->with('success', 'Investigation results recorded successfully.');
    }

    public function showResult(string $groupType, $groupId)
    {
        $query = InvestigationRequest::with([
            'investigation.parameters',
            'investigationResults.parameter',
            'patientVisit.patient.demographic',
            'walkinPatient',
        ]);

        if ($groupType === 'visit') {
            $query->where('patient_visit_id', $groupId);
        } elseif ($groupType === 'walkin') {
            $query->where('walkin_id', $groupId);
        } else {
            abort(404);
        }

        $investigationRequests = $query->where('status', 'Completed')->get();

        if ($investigationRequests->isEmpty()) {
            return redirect()->route('lab.requests.index')->with('error', 'No completed investigation results found for this patient.');
        }

        $patientName = $investigationRequests->first()->patientVisit
            ? $investigationRequests->first()->patientVisit->patient->demographic->full_name
            : ($investigationRequests->first()->walkinPatient->name ?? 'Walkin Patient');

        $hospitalNumber = $investigationRequests->first()->patientVisit
            ? $investigationRequests->first()->patientVisit->patient->hospital_number
            : null;

        return view('lab.request.result', compact(
            'investigationRequests',
            'groupType',
            'groupId',
            'patientName',
            'hospitalNumber'
        ));
    }
}

