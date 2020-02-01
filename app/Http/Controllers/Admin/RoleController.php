<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index' , compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|unique:roles|max:191' , 
            'display_name' => 'required|max:191' , 
            'description' => 'max:191' , 
            'permissions' => 'required|array' , 
            'permissions.*' => 'exists:permissions,id'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $role = Role::create($request->all());
        $role->attachPermissions($request->permissions);

        return redirect()->route('role.index')->with('success' , 'Role is created successfully')->withInput();
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        return view('admin.roles.edit' , compact('role'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required|unique:roles,name,' .$id . '|max:191' , 
            'display_name' => 'required|max:191' , 
            'description' => '|max:191'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $role = Role::findOrFail($id);

        $role->update($request->all());
        $role->syncPermissions($request->permissions);

        return redirect()->route('role.index')->with('success' , 'Role is edited successfully');
    }

    
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->detachPermissions();
        $role->delete();
        return redirect()->route('role.index')->with('success' , 'Role is deleted successfully');
    }
}
