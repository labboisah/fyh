<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StockTransaction;

class FinanceController extends Controller
{
    public function bills()
    {
        $transactions = StockTransaction::with(['bill.payments', 'payment.paymentMethod', 'stockTransactionItems.medicineBatch.medicine', 'createdBy'])
            ->whereNotNull('bill_id')
            ->latest()
            ->paginate(25);

        return view('pharmacy.finance.bills', compact('transactions'));
    }

    public function payments()
    {
        $transactions = StockTransaction::with(['bill', 'payment.paymentMethod', 'stockTransactionItems.medicineBatch.medicine', 'createdBy'])
            ->whereNotNull('payment_id')
            ->latest()
            ->paginate(25);

        return view('pharmacy.finance.payments', compact('transactions'));
    }

    public function receipt(Payment $payment)
    {
        $transaction = StockTransaction::with(['stockTransactionItems.medicineBatch.medicine', 'bill', 'payment.paymentMethod', 'createdBy'])
            ->where('payment_id', $payment->id)
            ->firstOrFail();

        $payment->load(['bill', 'paymentMethod', 'recordedBy']);

        return view('pharmacy.finance.receipt', compact('payment', 'transaction'));
    }

    public function report(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $from = $validated['from'] ?? today()->startOfMonth()->toDateString();
        $to = $validated['to'] ?? today()->toDateString();

        $transactions = StockTransaction::with(['bill', 'payment.paymentMethod', 'stockTransactionItems.medicineBatch.medicine', 'createdBy'])
            ->whereNotNull('bill_id')
            ->whereNotNull('payment_id')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to)
            ->latest()
            ->get();

        return view('pharmacy.finance.report', compact('transactions', 'from', 'to'));
    }
}
