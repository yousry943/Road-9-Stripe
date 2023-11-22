<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\Payment;

class TransactionService
{
    public function create($transactionData)
    {
        $transaction = new Transaction();
        $transaction->company_id = $transactionData['company_id'];
        $transaction->amount = $transactionData['amount'];
        $transaction->payer_id = $transactionData['payer_id'];
        $transaction->due_on = $transactionData['due_on'];
        $transaction->vat = $transactionData['vat'];
        $transaction->is_vat_inclusive = $transactionData['is_vat_inclusive'];

        $transaction->status = $this->calculateTransactionStatus($transactionData['due_on']);

        $transaction->save();

        return $transaction;
    }

    private function calculateTransactionStatus($dueOnDate)
    {
        $currentDate = now();

        if ($dueOnDate < $currentDate) {
            return 'overdue';
        } elseif ($dueOnDate === $currentDate) {
            return 'outstanding';
        }elseif ($dueOnDate > $currentDate) {
            return 'outstanding';
        }
    }

    public function recordPayment($paymentData)
    {

          $transaction = Transaction::findOrFail($paymentData['transaction_id']);

           $totalPaidAmount = $transaction->payments->sum('amount') ;
        if ($totalPaidAmount >= $paymentData['amount']) {
            $transaction->status = 'paid';
        } else {
            $transaction->status = $this->updateTransactionStatus($transaction, $paymentData['amount']);
        }

        $transaction->save();

        $payment = new Payment();
        $payment->transaction_id = $transaction->id;
        $payment->amount = $paymentData['amount'];
        $payment->paid_on = $paymentData['paid_on'];
        $payment->details = $paymentData['details'];

        $payment->save();

        return $payment;
    }

    private function updateTransactionStatus($transaction, $paidAmount)
    {
        $currentDate = now();

        if ($currentDate < $transaction->due_on) {
            return 'outstanding';
        } else {
            return 'overdue';
        }
    }
}
