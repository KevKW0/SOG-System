<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;

class InboundController extends Controller
{
    public function index()
    {
        $supplier = Suppliers::orderBy('name')->get();

        return view('inbound.index', [
            'suppliers' => $supplier
        ]);
    }

    public function data(Datatables $dataTables)
    {
        $inbound = Inbound::orderBy('id', 'desc')->get();

        return $dataTables
            ->of($inbound)
            ->addIndexColumn()
            ->addColumn('total_item', function ($inbound) {
                return money_format($inbound->total_item);
            })
            ->addColumn('total_price', function ($inbound) {
                return 'Rp. ' . money_format($inbound->total_price);
            })
            ->addColumn('pay', function ($inbound) {
                return 'Rp. ' . money_format($inbound->pay);
            })
            ->addColumn('date', function ($inbound) {
                return indonesia_date($inbound->created_at, false);
            })
            ->addColumn('supplier', function ($inbound) {
                return $inbound->supplier->name;
            })
            ->editColumn('discount', function ($inbound) {
                return $inbound->discount . '%';
            })
            ->addColumn('action', function ($inbound) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="showDetail(`' . route('inbound.show', $inbound->id) . '`)"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('inbound.destroy', $inbound->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create($id)
    {
        $inbound = new Inbound();
        $inbound->id_supplier = $id;
        $inbound->total_item = 0;
        $inbound->total_price = 0;
        $inbound->discount = 0;
        $inbound->pay = 0;
        $inbound->save();

        session(['id_inbound' => $inbound->id]);
        session(['id_supplier' => $inbound->id_supplier]);

        return redirect()->route('inbound_detail.index');
    }

    public function store(Request $request)
    {
        $inbound = Inbound::findOrFail($request->id_inbound);
        $inbound->total_item = $request->total_item;
        $inbound->total_price = $request->total_price;
        $inbound->discount = $request->discount;
        $inbound->pay = $request->pay;
        $inbound->update();

        $detail = InboundDetail::where('id_inbound', $inbound->id)->get();

        foreach ($detail as $item) {
            $product = Products::find($item->id_product);
            $product->stock += $item->qty;
            $product->update();
        }

        return redirect()->route('inbound.index');
    }

    public function show(Datatables $dataTables, $id)
    {
        $detail = InboundDetail::with('product')->where('id_inbound', $id)->get();

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
            ->addColumn('purchase_price', function ($detail) {
                return 'Rp. ' . money_format($detail->purchase_price);
            })
            ->addColumn('subtotal', function ($detail) {
                return 'Rp. ' . money_format($detail->subtotal);
            })
            ->rawColumns(['product_code'])
            ->make(true);
    }

    public function destroy($id)
    {
        $inbound = Inbound::find($id);
        $detail = InboundDetail::where('id_inbound', $inbound->id)->get();

        foreach ($detail as $item) {
            $item->delete();
        }
        $inbound->delete();

        return response(null, 204);
    }
}
