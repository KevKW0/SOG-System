<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use \Yajra\Datatables\Datatables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('customer.index');
    }

    public function data(Datatables $dataTables)
    {
        $customer = Customers::orderBy('id', 'desc')->get();

        return $dataTables
            ->of($customer)
            ->addIndexColumn()
            ->addColumn('customer_code', function ($customer) {
                return '<span class = "badge badge-success">' . $customer->customer_code . '</span>';
            })
            ->addColumn('action', function ($customer) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="editForm(`' . route('customer.update', $customer->id) . '`)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('customer.destroy', $customer->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'customer_code', 'select_all'])
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
        $request['customer_code'] = date('Ymd') . date('his');
        Customers::create($request->all());

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
        $customer = Customers::find($id);

        return response()->json($customer);
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
        $customer = Customers::find($id);
        $customer->update($request->all());

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
        $customer = Customers::find($id);
        $customer->delete();

        return response(null, 204);
    }
}
