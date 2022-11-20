<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('supplier.index');
    }

    public function data(Datatables $dataTables)
    {
        $supplier = Suppliers::orderBy('id', 'desc')->get();

        return $dataTables
            ->of($supplier)
            ->addIndexColumn()
            ->addColumn('select_all', function ($supplier) {
                return '
                <input type="checkbox" id = "select" name="id_supplier[' . $supplier->id . ']">
                ';
            })
            ->addColumn('action', function ($supplier) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="editForm(`' . route('supplier.update', $supplier->id) . '`)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('supplier.destroy', $supplier->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'select_all'])
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
        Suppliers::create($request->all());

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
        $supplier = Suppliers::find($id);

        return response()->json($supplier);
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
        $supplier = Suppliers::find($id);
        $supplier->update($request->all());

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
        $supplier = Suppliers::find($id);
        $supplier->delete();

        return response(null, 204);
    }
}
