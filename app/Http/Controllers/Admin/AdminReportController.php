<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;


class AdminReportController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function report(Request $request)
    {

        $amountsByStatusAndDate = Transaction::selectRaw('
        MONTH(due_on) as month,
        YEAR(due_on) as year,
        status,
        SUM(amount) as total_amount
    ')
            ->whereBetween('due_on', [$request->startDate, $request->endDate])
            ->whereIn('status', ['paid', 'overdue', 'outstanding']) // Add more statuses if needed
            ->groupBy('month', 'year', 'status')
            ->get();

        $resultFormatted = [];

        foreach ($amountsByStatusAndDate as $entry) {
            $resultFormatted[] = [
                'month' => $entry->month,
                'year' => $entry->year,
                'status' => $entry->status,
                'total_amount' => $entry->total_amount,
            ];
        }

        return $this->apiResponseData($resultFormatted, 'Success', 200);

    }
}
