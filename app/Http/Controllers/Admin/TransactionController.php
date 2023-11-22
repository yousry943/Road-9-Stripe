<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Payment;
use App\Services\TransactionService;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = Transaction::all();
        return response()->json([
            'transactions' => $transactions,
        ], 200);
    }

    public function create(Request $request)
    {
        try {
        $transactionData = $request->validate([
            'company_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payer_id' => 'required|integer',
            'due_on' => 'required|date',
            'vat' => 'required|integer',
            'is_vat_inclusive' => 'required|integer',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors(),
        ], 422);
    }

        $transaction = $this->transactionService->create($transactionData);
        return response()->json([
            'message' => 'Transaction created successfully',
            'transaction' => $transaction,
        ], 201);
    }

    public function recordPayment(Request $request)
    {
        try {
        $paymentData = $request->validate([

            'transaction_id' => 'required|integer',
            'amount' => 'required|integer',
            'paid_on' => 'required|date',
            'details' => 'nullable|string',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors(),
        ], 422);
    }
        $payment = $this->transactionService->recordPayment($paymentData);
        return response()->json([
            'message' => 'Payment recorded successfully',
            'payment' => $payment,
        ], 201);
    }

    public function show($transactionId)
    {
        $transaction = Transaction::findOrFail($transactionId);
        $payments = $transaction->payments;

        return response()->json([
            'transaction' => $transaction,
            'payments' => $payments,
        ], 200);
    }

    public function update($transactionId, Request $request)
    {
        try {
        $transactionData = $request->validate([
            'company_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payer_id' => 'required|integer',
            'due_on' => 'required|date',
            'vat_percentage' => 'required|numeric',
            'is_vat_inclusive' => 'required|boolean',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->errors(),
        ], 422);
    }

        $transaction = Transaction::findOrFail($transactionId);
        $transaction->update($transactionData);

        return response()->json([
            'message' => 'Transaction updated successfully',
            'transaction' => $transaction,
        ], 200);
    }

    public function destroy($transactionId)
    {
        Transaction::findOrFail($transactionId)->delete();
        return response()->json([
            'message' => 'Transaction deleted successfully',
        ], 200);
    }
}
