<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InvestigationRequest;

class RequestController extends Controller
{
    public function index()
    {
        return view('lab.request.index');
    }

    public function createResult(InvestigationRequest $investigationRequest)
    {
        return view('lab.request.create-result', compact('investigationRequest'));
    }

    public function storeResult(Request $request, InvestigationRequest $investigationRequest)
    {
        foreach($request->parameters as $parameterId => $value) {
            $result = $investigationRequest->investigationResults()->firstOrCreate([
                'parameter_id' => $parameterId,
            ], 
            [
                'value' => $value
            ]);
            
        }

        $investigationRequest->update([
            'performed_by' => auth()->id(),
            'completed_at' => now(),
            'status' => 'Completed',
        ]);

        return redirect()->route('lab.requests.index')->with('success', 'Investigation result recorded successfully.');
    }
}
