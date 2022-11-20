<?php

namespace App\Http\Controllers;

use App\Models\Retur;
use App\Models\ReturDetail;
use App\Models\Products;
use App\Models\Customers;
use App\Models\Setting;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;
use PDF;

class ReturController extends Controller
{
    public function index()
    {
        $product = Products::orderBy('product_name')->get();
        $customer = Customers::orderBy('name')->get();

        return view('retur.index', [
            'customers' => $customer,
            'products' => $product
        ]);
    }

    public function data(Datatables $dataTables)
    {
        $retur = Retur::orderBy('id', 'desc')->get();

        return $dataTables
            ->of($retur)
            ->addIndexColumn()
            ->addColumn('total_item', function ($retur) {
                return money_format($retur->total_item);
            })
            ->addColumn('pay', function ($retur) {
                return 'Rp. ' . money_format($retur->pay);
            })
            ->addColumn('date', function ($retur) {
                return indonesia_date($retur->created_at, false);
            })
            ->addColumn('customer_name', function ($retur) {
                return $retur->customer->name;
            })
            ->editColumn('discount', function ($retur) {
                return $retur->discount . '%';
            })
            ->addColumn('operator', function ($retur) {
                return $retur->user->name ?? '';
            })
            ->addColumn('action', function ($retur) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="showDetail(`' . route('retur.show', $retur->id) . '`)"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('retur.destroy', $retur->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create($id)
    {
        $retur = new Retur();
        $retur->id_customer = $id;
        $retur->total_item = 0;
        $retur->total_price = 0;
        $retur->discount = 0;
        $retur->pay = 0;
        $retur->id_user = auth()->id();
        $retur->save();

        session(['id_retur' => $retur->id]);
        session(['id_customer' => $retur->id_customer]);
        return redirect()->route('retur_detail.index');
    }

    public function store(Request $request)
    {
        $retur = Retur::findOrFail($request->id_retur);
        $retur->total_item = $request->total_item;
        $retur->total_price = $request->total_price;
        $retur->discount = $request->discount;
        $retur->pay = $request->pay;
        $retur->update();

        return redirect()->route('retur.save');
    }

    public function show(Datatables $dataTables, $id)
    {
        $detail = ReturDetail::with('product')->where('id_retur', $id)->get();

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
        $retur = Retur::find($id);
        $detail = ReturDetail::where('id_retur', $retur->id)->get();

        foreach ($detail as $item) {
            $item->delete();
        }
        $retur->delete();

        return response(null, 204);
    }

    public function save()
    {
        $setting = Setting::first();

        return view('retur.save', [
            'setting' => $setting
        ]);
    }

    public function note()
    {
        $setting = Setting::first();
        $retur = Retur::with('customer')->find(session('id_retur'));
        if (!$retur) {
            abort(404);
        }
        $customers = Customers::where('id', $retur->id_customer)->get();
        $detail = ReturDetail::with('product')
            ->where('id_retur', session('id_retur'))
            ->get();

        $pdf = PDF::loadView('retur.note', [
            'setting' => $setting,
            'retur' => $retur,
            'customers' => $customers,
            'detail' => $detail
        ]);
        $pdf->setPaper(0, 0, 609, 440, 'potrait');
        return $pdf->stream('Return-' . date('Y-m-d-his') . '.pdf');
    }
}
