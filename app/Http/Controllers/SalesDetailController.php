<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Products;
use App\Models\Sales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;

class SalesDetailController extends Controller
{
    public function index()
    {
        $id_sales = session('id_sales');
        $product = Products::orderBy('product_name')->get();
        $customer = Customers::find(session('id_customer'));
        $discount = Sales::find($id_sales)->discount ?? 0;

        if (!$customer) {
            abort(404);
        }

        return view('sales_detail.index', [
            'id_sales' => $id_sales,
            'products' => $product,
            'customers' => $customer,
            'discount' => $discount
        ]);
    }

    public function data(Datatables $dataTables, $id)
    {
        $detail = SalesDetail::with('product')
            ->where('id_sales', $id)
            ->get();

        $data = [];
        $total_price = 0;
        $total_item = 0;

        foreach ($detail as $item) {
            $row = [];
            $row['product_code']   = '
                                        <span class="badge badge-success">' . $item->product['product_code'] . '</span>';
            $row['product_name']   = $item->product['product_name'];
            $row['selling_price'] = 'Rp. ' . money_format($item->selling_price);
            $row['qty']            = '
                                        <input type="number" class="form-control input-sm qty" data-id="' . $item->id . '" value="' . $item->qty . '" >';
            $row['subtotal']       = 'Rp. ' . money_format($item->subtotal);
            $row['action']         =  '
                                        <div class="btn-group">
                                            <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('sales_detail.destroy', $item->id) . '`)"><i class="fas fa-trash"></i></button>
                                        </div>
                                        ';
            $data[] = $row;

            $total_price += $item->selling_price * $item->qty;
            $total_item += $item->qty;
        }

        $data[] = [
            'product_code' => '
                        <div class="total_price hide">' . $total_price . '</div> 
                        <div class="total_item hide">' . $total_item . '</div>',
            'product_name'   => '',
            'selling_price'  => '',
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

        $detail = new SalesDetail();
        $detail->id_sales = $request->id_sales;
        $detail->id_product = $product->id;
        $detail->selling_price = $product->selling_price;
        $detail->discount = 0;
        $detail->qty = 1;
        $detail->subtotal = $product->selling_price;
        $detail->save();

        return response()->json('Data Saved Successfully', 200);
    }

    public function update(Request $request, $id)
    {
        $detail = SalesDetail::find($id);
        $detail->qty = $request->qty;
        $detail->subtotal = $detail->selling_price * $request->qty;
        $detail->update();
    }

    public function destroy($id)
    {
        $detail = SalesDetail::find($id);
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
