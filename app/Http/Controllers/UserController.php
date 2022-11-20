<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use \Yajra\Datatables\Datatables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.index');
    }

    public function data(Datatables $dataTables)
    {
        $user = User::IsNotAdmin()->orderBy('id', 'desc')->get();

        return $dataTables
            ->of($user)
            ->addIndexColumn()
            ->addColumn('name', function ($user) {
                return $user->name;
            })
            ->addColumn('email', function ($user) {
                return $user->email;
            })
            ->addColumn('role', function ($user) {
                return $user->role;
            })
            ->addColumn('action', function ($user) {
                return '
                <div class="btn-group">
                    <button class="btn btn-xs btn-info" onclick="editForm(`' . route('user.update', $user->id) . '`)"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-xs btn-danger" onclick="deleteData(`' . route('user.destroy', $user->id) . '`)"><i class="fas fa-trash"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->photo = '/img/profile.jpg';
        $user->save();

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
        $user = User::find($id);

        return response()->json($user);
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
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->has('password') && $request->password != "") {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->update();;

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
        $user = User::find($id);
        $user->delete();

        return response(null, 204);
    }

    public function profile()
    {
        $profile = auth()->user();
        return view('user.profile', [
            'profile' => $profile
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $user->name = $request->name;

        // Cek apakah password lama sama atau tidak kosong
        if ($request->has('password') && $request->password != "") {
            if (Hash::check($request->old_password, $user->password)) {
                if ($request->password == $request->password_confirmation) {
                    $user->password = bcrypt($request->password);
                } else {
                    return response()->json('Confirmation Password Doesn\'t Match', 422);
                }
            } else {
                return response()->json('Old Password Doesn\'t Match', 422);
            }
        }

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $name);

            $user->photo = "/img/$name";
        }

        $user->update();

        return response()->json($user, 200);
    }
}
