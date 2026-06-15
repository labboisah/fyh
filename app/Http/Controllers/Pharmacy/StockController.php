<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineBatch;

class StockController extends Controller
{
    public function index() {
       $batches = MedicineBatch::with('medicine')->latest()->get();
       return view('pharmacy.stock.index', compact('batches')); 
    }

    public function create() {
        $medicines = Medicine::orderBy('name')->get();
        return view('pharmacy.stock.create', compact('medicines')); 
    }

    public function store(Request $request) {
        $data = $request->validate([
            "medicine_id" => "required|exists:medicines,id",
            "batch_number" => "required|string|max:255",
            "quantity_received" => "required|integer|min:1",
            "purchase_price" => "required|numeric|min:0",
            "selling_price" => "required|numeric|min:0",
            "manufacture_date" => "nullable|date",
            "expiry_date" => "required|date|after_or_equal:today"
        ]);
        $medicine = Medicine::findOrFail($data['medicine_id']);
        $medicine->batches()->create([
            "batch_number" => $data['batch_number'],
            "quantity_received" => $data['quantity_received'],
            "purchase_price" => $data['purchase_price'],
            "selling_price" => $data['selling_price'],
            "manufacture_date" => $data['manufacture_date'],
            "expiry_date" => $data['expiry_date'],
            'quantity_remaining' => $data['quantity_received']
        ]);
        return redirect()->route('pharmacy.stocks.index')->with('success', 'Stock Updated');
    }
}
