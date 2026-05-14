<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\PrescriptionItem;

class DrugChartController extends Controller
{
    public function record(Patient $patient) {
        return view('patient.drugchart.record', compact('patient'));
    }

    public function register(Request $request, Patient $patient) {
        $prescriptionItem = PrescriptionItem::find($request->prescription_item_id);

        $prescriptionItem->drugCharts()->create([
            'dosage' => $request->dosage,
            'medicine_id'=>$prescriptionItem->medicine->id,
            'mode_of_administration' => $request->mode_of_administration,
            'comment' => $request->comment,
            'time' => date("h:i:s A"),
            'dispensed_by' => auth()->user()->id,
        ]);
        return redirect()->route('patient.show', $patient)->with('success','Drug Chart Recorded Successfully');
    }
}
