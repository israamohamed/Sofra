<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::all();
        return view('admin.payments.index' , compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.payments.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'restaurant_id'  => 'required|exists:restaurants,id' , 
            'paid' => 'required' , 
            'date' => 'required|date' , 
        ];


        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first())->withInput();
        }

        $payment = Payment::create($request->all());
        return redirect()->route('payment.index')->with('success' , 'Payment is added to ' . $payment->restaurant->name . '  successfully');
    }

    public function show($id)
    {
        
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);

        return view('admin.payments.edit' , compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'restaurant_id'  => 'required|exists:restaurants,id' , 
            'paid' => 'required' , 
            'date' => 'required|date' , 
        ];

        $validator = validator()->make($request->all() , $rules);

        if($validator->fails())
        {
            return back()->with('error' , $validator->errors()->first());
        }

        $payment = Payment::findOrFail($id);

        $payment->update($request->all());

        return redirect()->route('payment.index')->with('success' , 'payment is edited in ' . $payment->restaurant->name . ' successfully');
    }

    
    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
        return redirect()->route('payment.index')->with('success' , 'payment is deleted successfully');
    }
}
