<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index' , compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required' , 
            'email' => 'required|email|unique:users' , 
            'password' => 'required|confirmed' , 
            'roles' =>  'required|array' , 
            'roles.*' => 'exists:roles,id'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->withInput()->with('error' , $validator->errors()->first());
        }

        $user = User::create($request->all());
        $user->attachRoles($request->roles);
        return redirect()->route('user.index')->with('success' , 'User is created successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.edit' , compact('user'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required' , 
            'email' => 'required|email|unique:users,email,' . $id , 
            'password' => 'required|confirmed' , 
            'roles' =>  'required|array' , 
            'roles.*' => 'exists:roles,id'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->withInput()->with('error' , $validator->errors()->first());
        }

        $user = User::findOrFail($id);
        $user->update($request->all());
        $user->syncRoles($request->roles);
        return redirect()->route('user.index')->with('success' , 'User is created successfully');
    }
    
    public function destroy($id)
    {
        $user = User::find($id);
        $user->detachRoles($user->roles);
        $user->delete();
        return redirect()->route('user.index')->with('success' , 'User is deleted successfully');
    }
}
