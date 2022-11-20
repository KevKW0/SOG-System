<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Categories;
use Psy\Command\HistoryCommand;
use \Yajra\Datatables\Datatables;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $categories = Categories::all()->pluck('category_name', 'id');
        return view('product.index', [
            'categories' => Categories::all()
        ]);
    }

    public function data(Datatables $dataTables)
    {
        $product = Products::leftJoin('category', 'category.id', 'product.id_category')
            ->select('product.*', 'category_name')
            ->orderBy('id', 'desc')
            ->get();

        return $dataTables
            ->of($product)
            ->addIndexColumn()
            ->addColumn('select_all', function ($product) {
                return '
                <input type="checkbox" name="id_product[]" value="' . $product->id . '">
                ';
            })
            ->addColumn('product_code', function ($product) {
                return '<span class = "badge badge-success">' . $product->product_code . '</span>';
            })
            ->addColumn('purchase_price', function ($product) {
                return money_format($product->purchase_price);
            })
            ->addColumn('selling_price', function ($product) {
                return money_format($product->selling_price);
            })
            ->addColumn('stock', function ($product) {
                return money_format($product->stock);
            })
            ->addColumn('action', function ($product) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="editForm(`' . route('product.update', $product->id) . '`)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('product.destroy', $product->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'product_code', 'select_all'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request['product_code'] = date('Ymd') . date('his');
        Products::create($request->all());

        return response()->json('Data Saved Successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Products::find($id);

        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Products::find($id);
        $product->update($request->all());

        return response()->json('Data Saved Successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Products::find($id);
        $product->delete();

        return response(null, 204);
    }

    public function deleteSelected(Request $request)
    {
        foreach ($request->id_product as $id) {
            $product = Products::find($id);
            $product->delete();
        }
        return response(null, 204);
    }

    public function printBarcode(Request $request)
    {
        $products = [];
        foreach ($request->id_product as $id) {
            $product = Products::find($id);
            $products[] = $product;
        }

        $no = 1;
        $pdf = PDF::loadView('product.barcode', [
            'products' => $products,
            'no' => $no
        ]);
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('Product.pdf');
    }
}
 