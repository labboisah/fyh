<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Medicine;

class StockController extends Controller
{
    public function index() {
       return view('pharmacy.stock.index'); 
    }

    public function create() {
        $medicines = Medicine::orderBy('name')->get();
        return view('pharmacy.stock.create', compact('medicines')); 
    }

    public function store(Request $request) {
        $data = $request->validate([
            "medicine_id" => "required",
            "batch_number" => "required",
            "quantity_received" => "required",
            "purchase_price" => "required",
            "selling_price" => "required",
            "manufacture_date" => "required",
            "expiry_date" => "required"
        ]);
        $medicine = Medicine::find($request->medicine_id);
        $medicine->batches()->create([
            "batch_number" => $request->batch_number,
            "quantity_received" => $request->quantity_received,
            "purchase_price" => $request->purchase_price,
            "selling_price" => $request->selling_price,
            "manufacture_date" => $request->manufacture_date,
            "expiry_date" => $request->expiry_date,
            'quantity_remaining' => $request->quantity_received
        ]);
        return redirect()->route('pharmacy.stocks.index')->with('success', 'Stock Updated');
    }
}
