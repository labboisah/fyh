<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;

class BillController extends Controller
{
    public function index() {
        return view('admin.bill.index');
    }

    public function show($bill) {
        // Logic to show a specific bill
        $bill = Bill::find($bill);
        return view('admin.bill.show', compact('bill'));
    }

    public function edit($bill) {
        $bill = Bill::find($bill);
        return view('admin.bill.edit', compact('bill'));
    }

    public function update(Request $request, $bill) {
        $bill = Bill::find($bill);
        $bill->update([
            'amount' => $request->input('amount'),
            'due_amount' => $request->input('amount') - ($request->input('amount') * $request->input('discount') / 100),
            'discount' => $request->input('discount'),
            'due_date' => $request->input('due_date'),
            'issued_date' => $request->input('issued_date'),
            'status' => $request->input('status'),
        ]);
        return redirect()->route('admin.bills.show', $bill)->with('success', 'Bill updated successfully.');
    }

    public function destroy($bill) {
        $bill = Bill::find($bill);
        $bill->delete();
        return redirect()->route('admin.bills.index')->with('success', 'Bill deleted successfully.');
    }

    // bill services management
    public function storeService(Request $request, $bill) {
        $bill = Bill::find($bill);
        $service = Service::find($request->input('service_id'));
        $bill->services()->create([
            'service_id' => $request->input('service_id'),
            'unit_price' => $service->price,
            'quantity' => $request->input('quantity'),
            'subtotal' => $service->price * $request->input('quantity'),
        ]);
        return redirect()->route('admin.bills.show', $bill)->with('success', 'Service added successfully.');
    }

    public function destroyService($bill, $service) {
        $bill = Bill::find($bill);
        $bill->services()->detach($service);
        return redirect()->route('admin.bills.show', $bill)->with('success', 'Service removed successfully.');
    }

    // update bill service
    public function updateService(Request $request, $bill, $billService) {
        $bill = Bill::find($bill);
        dd($billService);
        $BIservice = Service::find($request->input('service_id'));
        $bill->services()->updateExistingPivot($service->id, [
            'unit_price' => $service->price,
            'quantity' => $request->input('quantity'),
            'subtotal' => $service->price * $request->input('quantity'),
        ]);
        return redirect()->route('admin.bills.show', $bill)->with('success', 'Service updated successfully.');
    }

}
