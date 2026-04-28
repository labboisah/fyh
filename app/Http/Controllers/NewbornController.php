<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Newborn;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewbornController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Delivery $delivery)
    {
        $delivery->load('patient.demographic', 'newborns.recordedBy');

        return view('midwife.newborn.index', compact('delivery'));
    }

    public function create(Delivery $delivery)
    {
        if ($delivery->patient->demographic->gender !== 'Female') {
            return redirect()->route('midwife.delivery.index')
                ->with('error', 'Newborns can only be registered for female patients.');
        }

        return view('midwife.newborn.create', compact('delivery'));
    }

    public function store(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'sex' => 'required|in:male,female',
            'birth_order' => 'required|integer|min:1|max:8',
            'birth_date_time' => 'required|date',
            'birth_weight' => 'required|numeric|min:500|max:6000',
            'birth_length' => 'required|numeric|min:25|max:70',
            'head_circumference' => 'required|numeric|min:20|max:50',
            'presentation' => 'required|in:cephalic,breech,transverse,oblique',
            'apgar_score_1_minute' => 'required|integer|min:0|max:10',
            'apgar_score_5_minutes' => 'required|integer|min:0|max:10',
            'apgar_score_10_minutes' => 'nullable|integer|min:0|max:10',
            'general_condition' => 'nullable|string|max:500',
            'birth_defects_noted' => 'nullable|string|max:1000',
            'meconium_aspiration' => 'nullable|boolean',
            'breastfeeding_initiated' => 'nullable|boolean',
            'first_breastfeed_time' => 'nullable|date',
            'vitamin_k_given' => 'nullable|boolean',
            'eye_prophylaxis_given' => 'nullable|boolean',
            'immunizations_given' => 'nullable|boolean',
            'status' => 'required|in:alive,stillborn',
            'delivery_notes' => 'nullable|string|max:2000',
            'newborn_registration_number' => 'nullable|string|max:255',
        ]);

        $validated['delivery_id'] = $delivery->id;
        $validated['patient_id'] = $delivery->patient_id;
        $validated['recorded_by'] = Auth::id();
        $validated['newborn_registration_number'] = $validated['newborn_registration_number'] ?? 'NB-'.now()->format('YmdHis');

        $newborn = Newborn::create($validated);

        activity()
            ->performedOn($newborn)
            ->withProperties(['action' => 'create'])
            ->log('Newborn record created');

        return redirect()->route('midwife.newborn.show', $newborn)
            ->with('success', 'Newborn record created successfully.');
    }

    public function show(Newborn $newborn)
    {
        $newborn->load('delivery.patient.demographic', 'recordedBy', 'examinations');

        return view('midwife.newborn.show', compact('newborn'));
    }

    public function edit(Newborn $newborn)
    {
        return view('midwife.newborn.edit', compact('newborn'));
    }

    public function update(Request $request, Newborn $newborn)
    {
        $validated = $request->validate([
            'sex' => 'required|in:male,female',
            'birth_order' => 'required|integer|min:1|max:8',
            'birth_date_time' => 'required|date',
            'birth_weight' => 'required|numeric|min:500|max:6000',
            'birth_length' => 'required|numeric|min:25|max:70',
            'head_circumference' => 'required|numeric|min:20|max:50',
            'presentation' => 'required|in:cephalic,breech,transverse,oblique',
            'apgar_score_1_minute' => 'required|integer|min:0|max:10',
            'apgar_score_5_minutes' => 'required|integer|min:0|max:10',
            'apgar_score_10_minutes' => 'nullable|integer|min:0|max:10',
            'general_condition' => 'nullable|string|max:500',
            'birth_defects_noted' => 'nullable|string|max:1000',
            'meconium_aspiration' => 'nullable|boolean',
            'breastfeeding_initiated' => 'nullable|boolean',
            'first_breastfeed_time' => 'nullable|date',
            'vitamin_k_given' => 'nullable|boolean',
            'eye_prophylaxis_given' => 'nullable|boolean',
            'immunizations_given' => 'nullable|boolean',
            'status' => 'required|in:alive,stillborn',
            'delivery_notes' => 'nullable|string|max:2000',
        ]);

        $newborn->update($validated);

        activity()
            ->performedOn($newborn)
            ->withProperties(['action' => 'update'])
            ->log('Newborn record updated');

        return redirect()->route('midwife.newborn.show', $newborn)
            ->with('success', 'Newborn record updated successfully.');
    }

    public function destroy(Newborn $newborn)
    {
        $newborn->delete();

        activity()
            ->performedOn($newborn)
            ->withProperties(['action' => 'delete'])
            ->log('Newborn record deleted');

        return redirect()->route('midwife.delivery.show', $newborn->delivery)
            ->with('success', 'Newborn record deleted successfully.');
    }

    public function patientRecords(Patient $patient)
    {
        $patient->load('newborns.delivery');

        $newborns = $patient->newborns()->orderByDesc('birth_date_time')->get();

        return view('midwife.newborn.patient-records', compact('patient', 'newborns'));
    }
}
