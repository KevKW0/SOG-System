<?php

namespace App\Http\Controllers;

use App\Models\InboundDetail;
use App\Models\Products;
use App\Models\Suppliers;
use App\Models\Inbound;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;

class InboundDetailController extends Controller
{
    public function index()
    {
        $id_inbound = session('id_inbound');
        $product = Products::orderBy('product_name')->get();
        $supplier = Suppliers::find(session('id_supplier'));
        $discount = Inbound::find($id_inbound)->discount ?? 0;

        if (!$supplier) {
            abort(404);
        }

        return view('inbound_detail.index', [
            'id_inbound' => $id_inbound,
            'products' => $product,
            'suppliers' => $supplier,
            'discount' => $discount
        ]);
    }

    public function data(Datatables $dataTables, $id)
    {
        $detail = InboundDetail::with('product')
            ->where('id_inbound', $id)
            ->get();

        $data = [];
        $total_price = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = [];
            $row['product_code']   = '
                                        <span class="badge badge-success">' . $item->product['product_code'] . '</span>';
            $row['product_name']   = $item->product['product_name'];
            $row['purchase_price'] = 'Rp. ' . money_format($item->purchase_price);
            $row['qty']            = '
                                        <input type="number" class="form-control input-sm qty" data-id="' . $item->id . '" value="' . $item->qty . '" >';
            $row['subtotal']       = 'Rp. ' . money_format($item->subtotal);
            $row['action']         =  '
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('inbound_detail.destroy', $item->id) . '`)"><i class="fas fa-trash"></i></button>
                                        </div>
                                        ';
            $data[] = $row;

            $total_price += $item->purchase_price * $item->qty;
            $total_item += $item->qty;
        }

        $data[] = [
            'product_code' => '
                        <div class="total_price hide">' . $total_price . '</div> 
                        <div class="total_item hide">' . $total_item . '</div>',
            'product_name'   => '',
            'purchase_price' => '',
            'qty'            => '',
            'subtotal'       => '',
            'action'         => ''
        ];

        return $dataTables
            ->of($data)
            ->addIndexColumn()
            ->rawColumns(['action', 'product_code', 'qty'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $product = Products::where('id', $request->id_product)->firstOrFail();
        if (!$product) {
            return response()->json('Data Failed to Save', 400);
        }

        $detail = new InboundDetail();
        $detail->id_inbound = $request->id_inbound;
        $detail->id_product = $product->id;
        $detail->purchase_price = $product->purchase_price;
        $detail->qty = 1;
        $detail->subtotal = $product->purchase_price;
        $detail->save();

        return response()->json('Data Saved Successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = InboundDetail::find($id);
        $detail->qty = $request->qty;
        $detail->subtotal = $detail->purchase_price * $request->qty;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = InboundDetail::find($id);
        $detail->delete();

        return response(null, 204);
    }

    public function loadForm($discount, $total_price)
    {
        $pay = $total_price - (($discount / 100) * $total_price);
        $data = [
            'totalrp' => money_format($total_price),
            'pay' => $pay,
            'payrp' => money_format($pay),
            'counted' => ucwords(counted($pay) . 'Rupiah')
        ];

        return response()->json($data);
    }
}
