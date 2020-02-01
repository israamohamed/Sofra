<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        Category::create($request->all());
        return redirect()->route('category.index')->with('success' , 'Category is created successfully');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.categories.edit' , compact('category'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $category = Category::findOrFail($id);

        $category->update($request->all());

        return redirect()->route('category.index')->with('success' , 'Category is edited successfully');
    }

    
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->route('category.index')->with('success' , 'category is deleted successfully');
    }
}
