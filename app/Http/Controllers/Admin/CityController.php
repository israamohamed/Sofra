<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        return view('admin.cities.index' , compact('cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|unique:cities'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        City::create($request->all());
        return redirect()->route('city.index')->with('success' , 'City is created successfully');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $city = City::findOrFail($id);

        return view('admin.cities.edit' , compact('city'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required|unique:cities,name,'.$id
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $city = City::findOrFail($id);

        $city->update($request->all());

        return redirect()->route('city.index')->with('success' , 'City is edited successfully');
    }

    
    public function destroy($id)
    {
        $city = City::find($id);
        $city->delete();
        return redirect()->route('city.index')->with('success' , 'City is deleted successfully');
    }
}
