<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ConsumableStockController extends Controller
{

    public function index()
    {
        $stocks = ConsumableStock::with('consumable')->latest()->get();

        return view('consumable_stock.index',compact('stocks'));
    }

    public function create()
    {
        $consumables = Consumable::all();

        return view('consumable_stock.create',compact('consumables'));
    }

    public function store(Request $request)
    {

        ConsumableStock::create($request->all());

        return redirect()->route('consumable-stocks.index');

    }

    public function edit(ConsumableStock $consumable_stock)
    {
        $consumables = Consumable::all();

        return view('consumable_stock.edit',
            compact('consumable_stock','consumables'));
    }

    public function update(Request $request, ConsumableStock $consumable_stock)
    {
        $consumable_stock->update($request->all());

        return redirect()->route('consumable-stocks.index');
    }

    public function destroy(ConsumableStock $consumable_stock)
    {
        $consumable_stock->delete();

        return back();
    }

}
