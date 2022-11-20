<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Customers;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Inbound;
use App\Models\Sales;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $category = Categories::count();
        $product = Products::count();
        $supplier = Suppliers::count();
        $customer = Customers::count();

        $startDate = date('Y-m-01');
        $endDate = date('Y-m-d');

        $dateData = [];
        $incomeData = [];
        $salesData = [];

        while (strtotime($startDate) <= strtotime($endDate)) {
            $dateData[] = (int) substr($startDate, 8, 2);

            $totalInbound = Inbound::where('created_at', 'LIKE', "%$startDate%")->sum('pay');
            $totalSales = Sales::where('created_at', 'LIKE', "%$startDate%")->sum('pay');


            $sales = $totalSales;
            $salesData[] += $sales;

            $income = $totalSales - $totalInbound;
            $incomeData[] += $income;

            $startDate = date('Y-m-d', strtotime("+1 day", strtotime($startDate)));
        }

        $startDate = date('Y-m-01');

        if (auth()->user()->role == "Administrator") {
            return view('admin.dashboard', [
                'category' => $category,
                'product' => $product,
                'supplier' => $supplier,
                'customer' => $customer,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dateData' => $dateData,
                'incomeData' => $incomeData
            ]);
        } else if (auth()->user()->role == "Supervisor") {
            return view('supervisor.dashboard', [
                'category' => $category,
                'product' => $product,
                'supplier' => $supplier,
                'customer' => $customer,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'dateData' => $dateData,
                'salesData' => $salesData
            ]);
        } else if (auth()->user()->role == "Operator") {
            return view('operator.dashboard');
        }
    }
}
