<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicineBatch;
use App\Models\PharmacyDispense;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TransactionController extends Controller
{
    public function index() {
        $transactions = StockTransaction::with(['stockTransactionItems.medicineBatch.medicine', 'createdBy'])
            ->latest()
            ->get();

        return view('pharmacy.transaction.index', compact('transactions'));
    }

    public function create() {
        $batches = MedicineBatch::with('medicine')
            ->where('quantity_remaining', '>', 0)
            ->whereDate('expiry_date', '>=', today())
            ->latest()
            ->get();

        return view('pharmacy.transaction.create', compact('batches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'total_amount' => ['required', 'numeric', 'min:0.01'],
            'items' => ['required', 'json'],
        ]);

        $items = collect(json_decode($request->items, true))
            ->filter(fn ($item) => isset($item['batchId'], $item['quantity'], $item['price'], $item['subtotal']));

        if ($items->isEmpty()) {
            throw ValidationException::withMessages(['items' => 'Add at least one medicine to the transaction.']);
        }

        DB::transaction(function() use ($request, $items){

            $transaction = StockTransaction::create([
                'total_amount' => $request->total_amount,
                'type' => 'dispense',
                'created_by' => auth()->id()
            ]);

            foreach($items as $item){
                $batch = MedicineBatch::whereKey($item['batchId'])->lockForUpdate()->firstOrFail();
                $quantity = (int) $item['quantity'];

                if ($quantity < 1 || $batch->quantity_remaining < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$batch->medicine?->name}.",
                    ]);
                }

                StockTransactionItem::create([

                    'transaction_id' => $transaction->id,
                    'medicine_batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']

                ]);

                PharmacyDispense::create([
                    'medicine_batch_id' => $batch->id,
                    'type' => 'dispense',
                    'quantity' => $quantity,
                    'reference' => $transaction->id,
                    'created_by' => auth()->id(),
                ]);

                $batch->decrement('quantity_remaining', $quantity);
            }  

        });
        return redirect()->route('pharmacy.transactions.index')->with('success', 'Transaction Registered');

    }

}
