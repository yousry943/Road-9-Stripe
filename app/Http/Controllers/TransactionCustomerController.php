<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class TransactionCustomerController extends Controller
{
    use ApiResponseTrait;
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = Transaction::where('payer_id', auth()->user()->id)->get();

        return $this->apiResponseData($transactions, 'Success', 200);


    }

    public function show($transactionId)
    {
        $transaction = Transaction::where('id', $transactionId)
            ->where('payer_id', auth()->user()->id)
            ->firstOrFail();

        $payments = $transaction->payments;
        $data=[$transaction,$payments];
        return $this->apiResponseData($data, 'Success', 200);


    }
}
