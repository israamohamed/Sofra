<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Product;


class OrderController extends Controller
{
    public function new_order(Request $request)
    {
        $rules = [
            'restaurant_id'        => 'required|exists:restaurants,id' , 
            'address'              => 'required' , 
            'payment_method_id'    => 'required|exists:payment_methods,id' ,
            'products'             => 'required|array' , 
            'products.*.product_id'=> 'required|exists:products,id' , 
            'products.*.quantity'  => 'required'
        ];

        $messages = [
            'restaurant_id.required'     => 'يجب إدخال المطعم' , 
            'restaurant_id.exists'       => 'المطعم غير موجود' , 
            'address.required'           => 'يجب إدخال العنوان' , 
            'payment_method_id.required' => 'يجب إدخال طريقة الدفع' , 
            'payment_method_id.exists'   => 'طريقة الدفع غير موجودة' , 
            'products.*id.required'       => 'يجب إدخال المنتجات' , 
            'products.*id.exists'         => 'المنتجات غير صحيحة' , 
            'products.*quantity.required' => 'يجب إدخال الكمية المطلوبة '
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        //Get the restaurant
        $restaurant = Restaurant::find($request->restaurant_id);

        //Make Sure that the restaurant is open and is active from the admin
        if($restaurant->availability == 0 || $restaurant->is_active == 0)
        {
            return responceJson(0 , 'المطعم غير متاح الآن ');
        }

        //insert into the orders table
        $client = $request->user();
        $order  = $client->orders()->create([
            'restaurant_id'    => $request->restaurant_id , 
            'address'          => $request->address , 
            'notes'            => isset($request->notes) ? $request->notes : '' , 
            'payment_method_id'=> $request->payment_method_id , 
            'state'            => 'pending'
        ]);

        $cost = 0;
        //1 - Get every product 
        //2 - make sure that it belongs to that restaurant
        //3 - prepare the pivot attributes between the order and product -- quantity , price , special_order
        //4 - attach into the opder-product table
        //5 - calculate the cost
        foreach($request->products as $p)
        {
            $product = Product::where([
                                        ['id' , $p['product_id']] , 
                                        ['restaurant_id' , $request->restaurant_id]
                                    ])->first();

            if(!$product)
            {
                $order->products()->delete();
                $order->delete();
                return responceJson(0 , 'المنتج لا يتبع للمطعم ! ');         
            }

            $readyItem = [
                $product->id => [
                    'quantity'      =>  $p['quantity'] , 
                    'price'         =>  $product->price, 
                    'special_order' =>  isset($p['special_order']) ? $p['special_order'] : ''
                ]
            ];

            $order->products()->attach($readyItem);
            $cost +=  ($product->price * $p['quantity']);         
        }
        //Make sure that the cost < min charge of the reesturant
        if($cost >= $restaurant->minimum_charge)
        {
            //Calculate cost , delivery_fees , ...
            $delivery_cost = $restaurant->delivery_fees ;
            $total_cost    = $cost + $delivery_cost ;
            $commission    = settings()->commission * $cost ;
            $net           = $total_cost - $commission ;
            //Update the order
            $order->cost = $cost ;
            $order->delivery_cost = $delivery_cost ;
            $order->total_cost = $total_cost ;
            $order->commission = $commission ;
            $order->net = $net;

            $order->save();

            //create a notification 
            $notification = $restaurant->notifications()->create([
                                'title'     => 'لديك طلب جديد ' , 
                                'title_en'  => 'You have a new order' ,
                                'body'      => 'لديك طلب جديد من العميل ' . $client->name ,
                                'body_en'   => 'You have a new order from : ' .$client->name ,
                                'order_id'  => $order->id
                        ]);

            $tokens = $restaurant->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
            $data   = [
                    'order' => $order->fresh()->load('products')
            ]; 
            $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data);
            return responceJson(1 , 'تم إرسال الطلب للمطعم' , $data);
        }
        else 
        {
            $order->products()->delete();
            $order->delete();
            return responceJson(0 , 'الطلب لا يمكن أن يكون أقل من : ' . $restaurant->minimum_charge);
        }
    }

    public function decline_order(Request $request)
    {
        $rules = [
            'order_id'  => 'required|exists:orders,id'
        ];

        $messages = [
            'order_id.required' => 'يجب إدخال الطلب' , 
            'order_id.exists'   => 'الطلب غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson( 0 , $validator->errors()->first() , $validator->errors());
        }

        //Get the Order and make sure that it belongs to that client
        $order = Order::where([
                    ['id' , $request->order_id] , 
                    ['client_id' , $request->user()->id]
                ])->first();

        if(!$order)
        {
            return responceJson(0 , 'هذا الطلب لا يتبع للعميل');
        }
        //Make sure that the state of order was pending or accepted
        if(!($order->state == 'pending' || $order->state == 'accepted'))
        {
            return responceJson(0 , 'لا يمكن رفض هذا الطلب' );
        }

        //Decline the order
        $order->state = 'declined';
        $order->save();
        //Make a notification 
        $notification = $order->restaurant->notifications()->create([
                            'title' => 'تم رفض الطلب من العميل' , 
                            'body'  => '  تم رفض الطلب من العميل   ' . $order->client->name ,
                            'order_id' => $order->id
                        ]);
        //Send a notification
        $tokens = $order->restaurant->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
        $data = [
            'user_type' => 'restaurant' , 
            'action'    => 'decline_order' , 
            'order'     =>  $order
        ];
        $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data );
        return responceJson(1 , 'تم رفض الطلب' , $data);

    }

    public function deliver_order(Request $request)
    {
        $rules = [
            'order_id'  => 'required|exists:orders,id'
        ];

        $messages = [
            'order_id.required' => 'يجب إدخال الطلب' , 
            'order_id.exists'   => 'الطلب غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson( 0 , $validator->errors()->first() , $validator->errors());
        }

        //Get the Order and make sure that it belongs to that client
        $order = Order::where([
                    ['id' , $request->order_id] , 
                    ['client_id' , $request->user()->id]
                ])->first();

        if(!$order)
        {
            return responceJson(0 , 'هذا الطلب لا يتبع للعميل');
        }
        //Make sure that the state of order was pending or accepted
        if(!($order->state == 'pending' || $order->state == 'accepted'))
        {
            return responceJson(0 , 'لا يمكن توصيل هذا الطلب' );
        }

        //Delviver the order
        $order->state = 'delivered';
        $order->save();
        //Make a notification 
        $notification = $order->restaurant->notifications()->create([
                            'title' => 'تم إيصال الطلب إلى العميل' , 
                            'body'  => '  تم إيصال الطلب إلى العميل    ' . $order->client->name . ' بنجاح ' ,
                            'order_id' => $order->id
                        ]);
        //Send a notification
        $tokens = $order->restaurant->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
        $data = [
            'user_type' => 'restaurant' , 
            'action'    => 'deliver_order' , 
            'order'     =>  $order
        ];
        $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data );
        return responceJson(1 , 'تم توصيل الطلب' , $data);

    }

    /*public function current_orders(Request $request)
    {
        $orders = Order::where('client_id' , $request->user()->id)
                        ->whereIn('state' , ['pending' , 'accepted'])->get();

        return count($orders) ? responceJson(1 , 'success' , $orders) 
                              : responceJson(1 , 'لا يوجد طلبات حالية');
            
    }

    public function previous_orders(Request $request)
    {
        $orders = Order::where('client_id' , $request->user()->id)
                        ->whereNotIn('state' , ['pending' , 'accepted'])->get();

        return count($orders) ? responceJson(1 , 'success' , $orders) 
                              : responceJson(1 , 'لا يوجد طلبات سابقة');
            
    }*/

    public function orders(Request $request)
    {
        $rules = [
            'status'    => 'required|in:current,previous'
        ];

        $messages = [
            'status.required'   => 'يجب إدخال حالة الطلبات' , 
            'status.in'         => 'يجب إدخال حالة الطلب بشكل صحيح' 
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        if($request->status == 'current')
        {
            $orders = Order::where('client_id' , $request->user()->id)
                            ->whereIn('state' , ['pending' , 'accepted'])->get();

            $msg = 'لا يوجد طلبات حالية';
        }

        else if($request->status == 'previous')
        {
            $orders = Order::where('client_id' , $request->user()->id)
                            ->whereNotIn('state' , ['pending' , 'accepted'])->get();

            $msg = 'لا يوجد طلبات سابقة';
        }

        return count($orders) ? responceJson(1 , 'success' , $orders) 
                              : responceJson(1 , $msg);

    }

    public function order_details(Request $request)
    {
        $rules = ['order_id' => 'required|exists:orders,id'];

        $messages = [
            'order_id.required' => 'يجب إدخال الطلب ' , 
            'order_id.exists'   => 'الطلب غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $order = Order::where([
                                ['client_id' , $request->user()->id] , 
                                ['id'            , $request->order_id]
                            ])->get();

        if(!$order)
        {
            return responceJson(0 , ' الطلب خطأ');
        }

        return responceJson(1 , 'success' , $order);
    }




}
