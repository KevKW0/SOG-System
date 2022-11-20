<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index');
    }

    public function show()
    {
        return Setting::first();
    }

    public function update(Request $request)
    {
        $setting = Setting::first();
        $setting->company_name = $request->company_name;
        $setting->phone = $request->phone;
        $setting->address = $request->address;

        if ($request->hasFile('path_logo')) {
            $file = $request->file('path_logo');
            $name = 'logo-' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('/img'), $name);

            $setting->path_logo = "/img/$name";
        }

        $setting->update();

        return response()->json('Data Saved Successfully', 200);
    }
}
