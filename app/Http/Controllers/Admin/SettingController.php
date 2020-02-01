<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::first();
        return view('admin.settings.edit' , compact('settings'));

    }

    public function update(Request $request)
    {
        $rules = [
            'commission' => 'required'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $setting = Setting::first();
        $setting->update($request->all());
        return back()->with('success' , 'Settings are updated successfully');
    }
}
