<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_methods = PaymentMethod::all();
        return view('admin.payment_methods.index' , compact('payment_methods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|unique:payment_methods'
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        PaymentMethod::create($request->all());
        return redirect()->route('paymentMethod.index')->with('success' , 'PaymentMethod is created successfully');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);

        return view('admin.payment_methods.edit' , compact('payment_method'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name'  => 'required|unique:payment_methods,name,'.$id
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $payment_method = PaymentMethod::findOrFail($id);

        $payment_method->update($request->all());

        return redirect()->route('paymentMethod.index')->with('success' , 'PaymentMethod is edited successfully');
    }

    
    public function destroy($id)
    {
        $payment_method = PaymentMethod::find($id);
        $payment_method->delete();
        return redirect()->route('paymentMethod.index')->with('success' , 'PaymentMethod is deleted successfully');
    }
}
