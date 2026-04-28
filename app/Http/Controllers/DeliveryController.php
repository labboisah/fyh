<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Labour;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $patients = Patient::whereHas('demographic', function ($query) {
            $query->where('gender', 'Female')
                  ->whereRaw('YEAR(CURDATE()) - YEAR(date_of_birth) - (DATE_FORMAT(CURDATE(), "%m%d") < DATE_FORMAT(date_of_birth, "%m%d")) BETWEEN 13 AND 55');
        })->with(['demographic', 'deliveries'])->get();

        return view('midwife.delivery.index', compact('patients'));
    }

    public function create(Patient $patient)
    {
        $age = now()->diffInYears($patient->demographic->date_of_birth);

        if ($patient->demographic->gender !== 'Female' || $age < 13 || $age > 55) {
            return redirect()->route('midwife.delivery.index')
                ->with('error', 'Deliveries can only be created for female patients aged 13-55 years.');
        }

        return view('midwife.delivery.create', compact('patient'));
    }

    public function store(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'delivery_date_time' => 'required|date',
            'delivery_type' => 'required|in:vaginal,assisted,caesarean',
            'reason_for_delivery_type' => 'nullable|string|max:500',
            'assisted_with' => 'nullable|string|max:255',
            'indication_for_assistance' => 'nullable|string|max:500',
            'caesarean_type' => 'nullable|in:elective,emergency',
            'indication_for_caesarean' => 'nullable|string|max:500',
            'perineal_trauma' => 'nullable|string|max:255',
            'episiotomy' => 'nullable|boolean',
            'perineal_repair' => 'nullable|string|max:500',
            'placenta_delivery_method' => 'nullable|in:spontaneous,manual',
            'placenta_delivered_at' => 'nullable|date',
            'placental_examination' => 'nullable|string|max:1000',
            'estimated_blood_loss' => 'nullable|numeric|min:0|max:5000',
            'blood_loss_assessment' => 'nullable|string|max:500',
            'uterine_tone' => 'nullable|string|max:255',
            'per_vaginal_bleeding' => 'nullable|string|max:255',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|integer|min:40|max:200',
            'general_condition' => 'nullable|string|max:500',
            'complications' => 'nullable|string|max:2000',
            'management_of_complications' => 'nullable|string|max:2000',
            'number_of_babies' => 'required|integer|min:1|max:8',
            'delivery_summary' => 'nullable|string|max:2000',
            'delivery_status' => 'required|in:successful,complicated,failed',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['delivered_by'] = Auth::id();

        $delivery = Delivery::create($validated);

        activity()
            ->performedOn($delivery)
            ->withProperties(['action' => 'create'])
            ->log('Delivery record created');

        return redirect()->route('midwife.delivery.show', $delivery)
            ->with('success', 'Delivery record created successfully.');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load('patient.demographic', 'labour', 'deliveredBy', 'assistedBy', 'newborns');

        return view('midwife.delivery.show', compact('delivery'));
    }

    public function edit(Delivery $delivery)
    {
        $delivery->load('patient.demographic', 'labour');

        return view('midwife.delivery.edit', compact('delivery'));
    }

    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'delivery_date_time' => 'required|date',
            'delivery_type' => 'required|in:vaginal,assisted,caesarean',
            'reason_for_delivery_type' => 'nullable|string|max:500',
            'assisted_with' => 'nullable|string|max:255',
            'indication_for_assistance' => 'nullable|string|max:500',
            'caesarean_type' => 'nullable|in:elective,emergency',
            'indication_for_caesarean' => 'nullable|string|max:500',
            'perineal_trauma' => 'nullable|string|max:255',
            'episiotomy' => 'nullable|boolean',
            'perineal_repair' => 'nullable|string|max:500',
            'placenta_delivery_method' => 'nullable|in:spontaneous,manual',
            'placenta_delivered_at' => 'nullable|date',
            'placental_examination' => 'nullable|string|max:1000',
            'estimated_blood_loss' => 'nullable|numeric|min:0|max:5000',
            'blood_loss_assessment' => 'nullable|string|max:500',
            'uterine_tone' => 'nullable|string|max:255',
            'per_vaginal_bleeding' => 'nullable|string|max:255',
            'blood_pressure' => 'nullable|string|max:20',
            'pulse_rate' => 'nullable|integer|min:40|max:200',
            'general_condition' => 'nullable|string|max:500',
            'complications' => 'nullable|string|max:2000',
            'management_of_complications' => 'nullable|string|max:2000',
            'number_of_babies' => 'required|integer|min:1|max:8',
            'delivery_summary' => 'nullable|string|max:2000',
            'delivery_status' => 'required|in:successful,complicated,failed',
        ]);

        $delivery->update($validated);

        activity()
            ->performedOn($delivery)
            ->withProperties(['action' => 'update'])
            ->log('Delivery record updated');

        return redirect()->route('midwife.delivery.show', $delivery)
            ->with('success', 'Delivery record updated successfully.');
    }

    public function destroy(Delivery $delivery)
    {
        $delivery->delete();

        activity()
            ->performedOn($delivery)
            ->withProperties(['action' => 'delete'])
            ->log('Delivery record deleted');

        return redirect()->route('midwife.delivery.index')
            ->with('success', 'Delivery record deleted successfully.');
    }

    public function patientRecords(Patient $patient)
    {
        $patient->load('demographic', 'deliveries.labour');

        $deliveries = $patient->deliveries()->orderByDesc('delivery_date_time')->get();

        return view('midwife.delivery.patient-records', compact('patient', 'deliveries'));
    }
}
