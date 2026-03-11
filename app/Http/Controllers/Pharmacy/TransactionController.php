<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicineBatch;
use App\Models\StockTransaction;
use App\Models\StockTransactionItem;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index() {
        return view('pharmacy.transaction.index');
    }

    public function create() {
        $batches = MedicineBatch::latest()->get();
        return view('pharmacy.transaction.create', compact('batches'));
    }

    public function store(Request $request)
    {
        

        DB::transaction(function() use ($request){

            $transaction = StockTransaction::create([
                'total_amount' => $request->total_amount,
                'type' => 'purchase',
                'created_by' => auth()->id()
            ]);

            $items = json_decode($request->items,true);

            foreach($items as $item){

                StockTransactionItem::create([

                    'transaction_id' => $transaction->id,
                    'medicine_batch_id' => $item['batchId'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal']

                ]);

                MedicineBatch::where('id',$item['batchId'])
                    ->decrement('quantity_remaining',$item['quantity']);
            }  

        });
        return redirect()->route('pharmacy.transactions.index')->with('success', 'Transaction Registered');

    }

}
