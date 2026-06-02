<?php

namespace App\Http\Controllers\Radiology;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\InvestigationRequest;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $requests = auth()->user()->department->investigationRequests()
                ->with(['bill', 'patientVisit.patient.demographic', 'requestedBy', 'performedBy', 'investigation'])
                ->orderByDesc('created_at')
                ->get()
                ->filter(fn ($investigationRequest) => $investigationRequest->bill !== null);

            $recordsTotal = $requests->count();
            $search = trim(strtolower($request->input('search.value', '')));

            if ($search !== '') {
                $requests = $requests->filter(function ($investigationRequest) use ($search) {
                    $patientName = $investigationRequest->bill->patientName() ?? 'N/A';
                    $hospitalNumber = $investigationRequest->patientVisit?->patient?->hospital_number ?? 'Walk in Patient';
                    $investigationName = $investigationRequest->investigation->name ?? '';
                    $requestedBy = $investigationRequest->requestedBy->name ?? '';
                    $status = $investigationRequest->bill->status ?? '';

                    return Str::contains(strtolower($patientName.' '.$hospitalNumber.' '.$investigationName.' '.$requestedBy.' '.$status), $search);
                });
            }

            $recordsFiltered = $requests->count();
            $orderCol = (int) $request->input('order.0.column', 4);
            $orderDir = $request->input('order.0.dir', 'desc');

            $requests = $requests->sortBy(function ($investigationRequest) use ($orderCol) {
                return match ($orderCol) {
                    1 => strtolower($investigationRequest->bill->patientName() ?? ''),
                    2 => strtolower($investigationRequest->patientVisit?->patient?->hospital_number ?? ''),
                    3 => strtolower($investigationRequest->investigation->name ?? ''),
                    4 => $investigationRequest->requested_at ?? now(),
                    5 => strtolower($investigationRequest->requestedBy->name ?? ''),
                    6 => strtolower($investigationRequest->bill->status ?? ''),
                    7 => $investigationRequest->completed_at ?? now(),
                    8 => strtolower($investigationRequest->performedBy->name ?? ''),
                    default => $investigationRequest->requested_at ?? now(),
                };
            });

            if ($orderDir !== 'asc') {
                $requests = $requests->reverse()->values();
            }

            $start = (int) $request->input('start', 0);
            $length = (int) $request->input('length', 10);
            $pageItems = $requests->slice($start, $length)->values();

            $data = $pageItems->map(function ($investigationRequest, $index) use ($start) {
                $patientName = $investigationRequest->bill->patientName() ?? 'N/A';
                $hospitalNumber = $investigationRequest->patientVisit?->patient?->hospital_number ?? 'Walk in Patient';
                $paymentStatus = $investigationRequest->bill->status === 'paid'
                    ? 'Paid'
                    : 'No Payment Recorded';
                $actions = '';

                if ($investigationRequest->bill->status === 'paid') {
                    $actions .= '<a href="'.route('radiology.requests.createResult', $investigationRequest).'" class="btn btn-outline-primary me-1"><i class="bi bi-save"></i> Save</a>';

                    if ($investigationRequest->completed_at) {
                        $actions .= '<a href="'.route('radiology.requests.show', $investigationRequest).'" class="btn btn-outline-success me-1"><i class="bi bi-printer"></i> Print</a>';
                        $actions .= '<a href="'.route('radiology.requests.editResult', $investigationRequest).'" class="btn btn-outline-warning"><i class="bi bi-pencil"></i> Edit</a>';
                    }
                } else {
                    $actions = '<span class="text-muted">No Payment Recorded</span>';
                }

                return [
                    $start + $index + 1,
                    $patientName,
                    $hospitalNumber,
                    $investigationRequest->investigation->name,
                    $investigationRequest->requested_at,
                    $investigationRequest->requestedBy->name ?? 'N/A',
                    $paymentStatus,
                    $investigationRequest->completed_at,
                    $investigationRequest->performedBy->name ?? 'N/A',
                    $actions,
                ];
            })->toArray();

            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
        }

        return view('radiology.request.index');
    }

    public function createResult(InvestigationRequest $investigationRequest)
    {
        return view('radiology.request.create-result', compact('investigationRequest'));
    }

    public function storeResult(Request $request, InvestigationRequest $investigationRequest)
    {
        $validated = $request->validate([
            'parameters' => 'sometimes|array',
            'parameters.*' => 'nullable|string',
            'result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $hasResult = false;

        foreach ($validated['parameters'] ?? [] as $parameterId => $value) {
            if (trim((string) $value) === '') {
                continue;
            }

            $investigationRequest->investigationResults()->updateOrCreate(
                ['parameter_id' => $parameterId],
                ['value' => $value]
            );

            $hasResult = true;
        }

        if ($request->hasFile('result_image')) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
            }

            $investigationRequest->result_image = $request->file('result_image')->store('radiology-results', 'public');
            $hasResult = true;
        }

        if (! $hasResult) {
            return back()->withInput()->with('error', 'Please provide at least one radiology result or upload an image.');
        }

        $investigationRequest->update([
            'performed_by' => auth()->id(),
            'completed_at' => now(),
            'status' => 'Completed',
            'result_image' => $investigationRequest->result_image,
        ]);

        return redirect()->route('radiology.requests.index')->with('success', 'Investigation result recorded successfully.');
    }

    public function show(InvestigationRequest $investigationRequest)
    {
        $investigationRequest->load([
            'investigation.parameters',
            'investigationResults.parameter',
            'patientVisit.patient.demographic',
            'walkinPatient',
            'requestedBy',
            'performedBy',
        ]);

        if ($investigationRequest->patientVisit) {
            $patientName = $investigationRequest->patientVisit->patient->demographic->full_name;
            $hospitalNumber = $investigationRequest->patientVisit->patient->hospital_number ?? null;
        } else {
            $patientName = $investigationRequest->walkinPatient->name ?? 'Walkin Patient';
            $hospitalNumber = null;
        }

        return view('radiology.request.result', compact('investigationRequest', 'patientName', 'hospitalNumber'));
    }

    public function editResult(InvestigationRequest $investigationRequest)
    {
        $investigationRequest->load([
            'investigation.parameters',
            'investigationResults.parameter',
            'patientVisit.patient.demographic',
            'walkinPatient',
        ]);

        return view('radiology.request.edit-result', compact('investigationRequest'));
    }

    public function updateResult(Request $request, InvestigationRequest $investigationRequest)
    {
        $validated = $request->validate([
            'parameters' => 'sometimes|array',
            'parameters.*' => 'nullable|string',
            'result_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'remove_image' => 'nullable|boolean',
        ]);

        $hasChange = false;

        foreach ($validated['parameters'] ?? [] as $parameterId => $value) {
            if (trim((string) $value) === '') {
                // delete existing result if present? keep as is — skip
                continue;
            }

            $investigationRequest->investigationResults()->updateOrCreate(
                ['parameter_id' => $parameterId],
                ['value' => $value]
            );

            $hasChange = true;
        }

        if (!empty($validated['remove_image']) && $validated['remove_image']) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
                $investigationRequest->result_image = null;
                $hasChange = true;
            }
        }

        if ($request->hasFile('result_image')) {
            if ($investigationRequest->result_image) {
                Storage::disk('public')->delete($investigationRequest->result_image);
            }

            $investigationRequest->result_image = $request->file('result_image')->store('radiology-results', 'public');
            $hasChange = true;
        }

        if (! $hasChange) {
            return back()->with('warning', 'No changes detected.')->withInput();
        }

        $investigationRequest->update([
            'performed_by' => auth()->id(),
            'completed_at' => now(),
            'status' => 'Completed',
            'result_image' => $investigationRequest->result_image,
        ]);

        return redirect()->route('radiology.requests.show', $investigationRequest)->with('success', 'Investigation result updated successfully.');
    }
}
