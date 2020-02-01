<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('restaurant_id'))
        {
            $orders = Order::where(function($query) use($request){

                $query->where('restaurant_id' , $request->restaurant_id);
                
                if($request->has('status'))
                {
                    $query->where('state' , $request->status);
                }
            })->with(['paymentMethod' , 'products'])->paginate(2);

            $res_id = $request->restaurant_id;
            return view('admin.orders.index' , compact('orders' , 'res_id'));
        }

        else if($request->has('client_id'))
        {
            $orders = Order::where(function($query) use($request){

                $query->where('client_id' , $request->client_id);
                
                if($request->has('status'))
                {
                    $query->where('state' , $request->status);
                }
            })->with(['paymentMethod' , 'products'])->paginate(2);

            $client_id = $request->client_id;
            return view('admin.orders.index' , compact('orders' , 'client_id'));
        }
        
    }

    public function products($order_id)
    {
        $order = Order::find($order_id);
        return responceJson(1 , "success" , $order->products);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        $order->products()->detach();
        $order->notifications()->delete();
        $order->delete();
        return redirect()->route('order.index', ['restaurant_id' => $order->restaurant])->with('success' , 'order is deleted successfully');
    }
}
