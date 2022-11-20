<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inbound;
use App\Models\Sales;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $endDate = date('Y-m-d');

        if ($request->has('startDate') && $request->startDate != "" && $request->has('endDate') && $request->endDate) {
            if ($request->startDate > $request->endDate) {
                abort(404);
            } else {
                $startDate = $request->startDate;
                $endDate = $request->endDate;
            }
        }

        return view('report.index', [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function getData($startDate, $endDate)
    {
        $no = 1;
        $data = [];
        $income = 0;
        $totalIncome = 0;

        while (strtotime($startDate) <= strtotime($endDate)) {
            $date = $startDate;
            $startDate = date('Y-m-d', strtotime("+1 day", strtotime($startDate)));

            $totalInbound = Inbound::where('created_at', 'LIKE', "%$date%")->sum('pay');
            $totalSales = Sales::where('created_at', 'LIKE', "%$date%")->sum('pay');

            $income = $totalSales - $totalInbound;
            $totalIncome += $income;

            $row = [];
            $row['DT_RowIndex'] = $no++;
            $row['date']    = indonesia_date($date, false);
            $row['inbound'] = money_format($totalInbound);
            $row['sales']   = money_format($totalSales);
            $row['income']  = money_format($income);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex'   => '',
            'date'          => '',
            'inbound'       => '',
            'sales'         => 'Total Income',
            'income'        => money_format($totalIncome)
        ];

        return $data;
    }

    public function data($startDate, $endDate)
    {
        $data = $this->getData($startDate, $endDate);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($startDate, $endDate)
    {
        $data = $this->getData($startDate, $endDate);
        $pdf = PDF::loadview('report.pdf', [
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'data'  => $data
        ]);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('IncomeReport-' . date('Y-m-d-his') . '.pdf');
    }
}
