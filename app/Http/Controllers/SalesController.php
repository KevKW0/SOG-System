<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Setting;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use PDF;

class SalesController extends Controller
{
    public function index()
    {
        $product = Products::orderBy('product_name')->get();
        $customer = Customers::orderBy('name')->get();

        return view('sales.index', [
            'customers' => $customer,
            'products' => $product
        ]);
    }

    public function data(Datatables $dataTables)
    {
        $sales = Sales::orderBy('id', 'desc')->get();

        return $dataTables
            ->of($sales)
            ->addIndexColumn()
            ->addColumn('total_item', function ($sales) {
                return money_format($sales->total_item);
            })
            ->addColumn('pay', function ($sales) {
                return 'Rp. ' . money_format($sales->pay);
            })
            ->addColumn('date', function ($sales) {
                return indonesia_date($sales->created_at, false);
            })
            ->addColumn('customer_name', function ($sales) {
                return $sales->customer->name;
            })
            ->editColumn('discount', function ($sales) {
                return $sales->discount . '%';
            })
            ->addColumn('operator', function ($sales) {
                return $sales->user->name ?? '';
            })
            ->addColumn('action', function ($sales) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="showDetail(`' . route('sales.show', $sales->id) . '`)"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('sales.destroy', $sales->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create($id)
    {
        $sales = new Sales();
        $sales->id_customer = $id;
        $sales->total_item = 0;
        $sales->total_price = 0;
        $sales->discount = 0;
        $sales->pay = 0;
        $sales->id_user = auth()->id();
        $sales->save();

        session(['id_sales' => $sales->id]);
        session(['id_customer' => $sales->id_customer]);
        return redirect()->route('sales_detail.index');
    }

    public function store(Request $request)
    {
        $sales = Sales::findOrFail($request->id_sales);
        $sales->total_item = $request->total_item;
        $sales->total_price = $request->total_price;
        $sales->discount = $request->discount;
        $sales->pay = $request->pay;
        $sales->update();

        $detail = SalesDetail::where('id_sales', $sales->id)->get();

        foreach ($detail as $item) {
            $product = Products::find($item->id_product);
            $product->stock -= $item->qty;
            $product->update();
        }

        return redirect()->route('sales.save');
    }

    public function show(Datatables $dataTables, $id)
    {
        $detail = SalesDetail::with('product')->where('id_sales', $id)->get();

        return $dataTables
            ->of($detail)
            ->addIndexColumn()
            ->addColumn('product_code', function ($detail) {
                return '<span class="badge badge-success">' . $detail->product->product_code . '</span>';
            })
            ->addColumn('product_name', function ($detail) {
                return $detail->product->product_name;
            })
            ->addColumn('qty', function ($detail) {
                return $detail->qty;
            })
            ->addColumn('selling_price', function ($detail) {
                return 'Rp. ' . money_format($detail->selling_price);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . money_format($detail->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    public function destroy($id)
    {
        $sales = Sales::find($id);
        $detail = SalesDetail::where('id_sales', $sales->id)->get();

        foreach ($detail as $item) {
            $item->delete();
        }
        $sales->delete();

        return response(null, 204);
    }

    public function save()
    {
        $setting = Setting::first();

        return view('sales.save', [
            'setting' => $setting
        ]);
    }

    public function note()
    {
        $setting = Setting::first();
        $sales = Sales::with('customer')->find(session('id_sales'));
        if (!$sales) {
            abort(404);
        }
        $customers = Customers::where('id', $sales->id_customer)->get();
        $detail = SalesDetail::with('product')
            ->where('id_sales', session('id_sales'))
            ->get();

        $pdf = PDF::loadView('sales.note', [
            'setting' => $setting,
            'sales' => $sales,
            'customers' => $customers,
            'detail' => $detail
        ]);
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Transaction-' . date('Y-m-d-his') . '.pdf');
    }
}
