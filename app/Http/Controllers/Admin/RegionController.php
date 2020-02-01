<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Region;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::all();
        return view('admin.regions.index' , compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.regions.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|unique:regions' ,
            'city_id' => 'required|exists:cities,id'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        Region::create($request->all());
        return redirect()->route('region.index')->with('success' , 'Region is created successfully');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $region = Region::findOrFail($id);

        return view('admin.regions.edit' , compact('region'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'    => 'required|unique:regions,name,'.$id , 
            'city_id' => 'required|exists:cities,id'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $region = Region::findOrFail($id);

        $region->update($request->all());

        return redirect()->route('region.index')->with('success' , 'Region is edited successfully');
    }

    
    public function destroy($id)
    {
        $region = Region::find($id);
        $region->delete();
        return redirect()->route('region.index')->with('success' , 'Region is deleted successfully');
    }
}
