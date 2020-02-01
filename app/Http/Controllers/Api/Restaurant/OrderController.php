<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function accept_order(Request $request)
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

        //Get the Order and make sure that it belngs to that restaurant
        $order = Order::where([
                    ['id' , $request->order_id] , 
                    ['restaurant_id' , $request->user()->id]
                ])->first();

        if(!$order)
        {
            return responceJson(0 , 'هذا الطلب لا يتبع للمطعم');
        }
        //Make sure that the state of order was pending
        if($order->state != 'pending')
        {
            return responceJson(0 , 'لا يمكن قبول هذا الطلب' );
        }

        //Accept the order
        $order->state = 'accepted';
        $order->save();
        //Make a notification 
        $notification = $order->client->notifications()->create([
                            'title' => 'تم قبول طلبك' , 
                            'body'  => ' أهلاً ' . $order->client->name . '  تم قبول طلبك من مطعم  ' . $order->restaurant->name ,
                            'order_id' => $order->id
                        ]);
        //Send a notification
        $tokens = $order->client->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
        $data = [
            'user_type' => 'client' , 
            'action'    => 'accetp_order' , 
            'order'     => $order
        ];
        $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data );
        return responceJson(1 , 'تم قبول الطلب' , $data);

    }

    public function reject_order(Request $request)
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

        //Get the Order and make sure that it belongs to that restaurant
        $order = Order::where([
                    ['id' , $request->order_id] , 
                    ['restaurant_id' , $request->user()->id]
                ])->first();

        if(!$order)
        {
            return responceJson(0 , 'هذا الطلب لا يتبع للمطعم');
        }
        //Make sure that the state of order was pending
        if($order->state != 'pending')
        {
            return responceJson(0 , 'لا يمكن رفض هذا الطلب' );
        }

        //Reject the order
        $order->state = 'rejected';
        $order->save();
        //Make a notification 
        $notification = $order->client->notifications()->create([
                            'title' => 'تم رفض طلبك' , 
                            'body'  => ' أهلاً ' . $order->client->name . '  عفواً تم رفض طلبك من مطعم   ' . $order->restaurant->name ,
                            'order_id' => $order->id
                        ]);
        //Send a notification
        $tokens = $order->client->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
        $data = [
            'user_type' => 'client' , 
            'action'    => 'reject_order' , 
            'order'     => $order
        ];
        $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data );
        return responceJson(1 , 'تم رفض الطلب' , $data);

    }

    public function confirm_order(Request $request)
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

        //Get the Order and make sure that it belongs to that restaurant
        $order = Order::where([
                    ['id' , $request->order_id] , 
                    ['restaurant_id' , $request->user()->id]
                ])->first();

        if(!$order)
        {
            return responceJson(0 , 'هذا الطلب لا يتبع للمطعم');
        }
        //Make sure that the state of order was delivered
        if($order->state != 'delivered')
        {
            return responceJson(0 , 'لا يمكن تأكيد هذا الطلب' );
        }

        //Confirm the order
        $order->state = 'confirmed';
        $order->save();
        //Make a notification 
        $notification = $order->client->notifications()->create([
                            'title' => 'تم تأكيد وصول الطلب ' , 
                            'body'  => ' أهلاً ' . $order->client->name . '  تم تأكيد وصول طلبك من مطعم    ' . $order->restaurant->name ,
                            'order_id' => $order->id
                        ]);
        //Send a notification
        $tokens = $order->client->tokens()->where('token' , '!=' , '')->pluck('token')->toArray();
        $data = [
            'user_type' => 'client' , 
            'action'    => 'confirm_order' , 
            'order'     =>  $order
        ];
        $send = notifyByFirebase($notification->title , $notification->body , $tokens , $data );
        return responceJson(1 , 'تم  تأكيد وصول الطلب' , $data);
    }

    /*public function new_orders(Request $request)
    {
        $orders = Order::where([
            ['restaurant_id' , $request->user()->id] , 
            ['state'         , 'pending']
        ])->get();

        return count($orders) ? responceJson(1 , 'success' , $orders)
                             : responceJson(1 , 'لا يوجد طلبات جديدة ');
                      
    }

    public function current_orders(Request $request)
    {
        $orders = Order::where([
            ['restaurant_id' , $request->user()->id] , 
            ['state'         , 'accepted']  
        ])->get();
        return count($orders) ? responceJson(1 , 'success' , $orders)
                              : responceJson(1 , 'لا يوجد طلبات حالية ');
                      
    }

    public function previous_orders(Request $request)
    {
        $orders = Order::where('restaurant_id' , $request->user()->id)
                        ->whereNotIn('state' , ['pending' , 'accepted'])
        ->get();

        return count($orders) ? responceJson(1 , 'success' , $orders)
                             : responceJson(1 , 'لا يوجد طلبات سابقة ');
                      
    }*/

    public function orders(Request $request)
    {
        $rules = [
            'status'    => 'required|in:new,current,previous'
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

        if($request->status == 'new')
        {
            $orders = Order::where([
                ['restaurant_id' , $request->user()->id] , 
                ['state'         , 'pending']
            ])->get();

            $msg = 'لا يوجد طلبات جديدة' ;
        }

        else if($request->status == 'current')
        {
            $orders = Order::where([
                ['restaurant_id' , $request->user()->id] , 
                ['state'         , 'accepted']  
            ])->get();

            $msg = 'لا يوجد طلبات حالية ' ;
        }

        else if($request->status == 'previous')
        {
            $orders = Order::where('restaurant_id' , $request->user()->id)
                        ->whereNotIn('state' , ['pending' , 'accepted'])
                    ->get();

            $msg = 'لا يوجد طلبات سابقة';

        }

        return count($orders) ? responceJson(1 , 'success' , $orders)
                              : responceJson(1 , $msg);


    }

}
