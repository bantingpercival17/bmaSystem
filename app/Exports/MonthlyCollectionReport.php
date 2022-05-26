<?php

namespace App\Exports;

use App\Models\PaymentTransaction;
use App\StudentPaymentAssessment;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonthlyCollectionReport implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($_month)
    {
        $this->month  = $_month;
    }
    public function sheets(): array
    {
        $sheets = [];
        $_days = PaymentTransaction::select('transaction_date')->where('transaction_date', 'like', '%' . $this->month . '%')
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date,'%d')"))->get();
        foreach ($_days as $key => $_day) {
            $_date = date_format(date_create($_day->transaction_date), "Y-m-d");
            $sheets[] = new CollectionReport($_date);
        }
        return $sheets;
    }
}
