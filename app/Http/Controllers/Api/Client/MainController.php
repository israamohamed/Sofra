<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\Contact;
use App\Models\Offer;
use App\Models\Review;

class MainController extends Controller
{
    public function restaurants()
    {
        $restaurants = Restaurant::paginate(10);
        return count($restaurants) ? responceJson(1 , 'success' , $restaurants )
                                   : responceJson(0 , 'no data');
    }

    public function products(Request $request)
    {
        $rules = [
            'restaurant_id' => 'required|exists:restaurants,id'
        ];

        $messages = [
            'restaurant_id.required' => 'يجب إدخال المطعم المطلوب' , 
            'restaurant_id.exists'   =>  'المطعم غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);
        
        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        $products = Product::where('restaurant_id' , $request->restaurant_id)->paginate(5);

        return count($products) ? responceJson(1 , 'success' , $products )
                                : responceJson(0 , 'no data');
    }

    public function reviews(Request $request)
    {
        $rules = [
            'restaurant_id' => 'required|exists:restaurants,id'
        ];

        $messages = [
            'restaurant_id.required' => 'يجب إدخال المطعم المطلوب' , 
            'restaurant_id.exists'   =>  'المطعم غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);
        
        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        $reviews = Review::where('restaurant_id' , $request->restaurant_id)->paginate(10);

        return count($reviews) ? responceJson(1 , 'success' , $reviews )
                               : responceJson(0 , 'no data');
    }

    public function restaurant(Request $request)
    {
        $rules = [
            'restaurant_id' => 'required|exists:restaurants,id'
        ];

        $messages = [
            'restaurant_id.required' => 'يجب إدخال المطعم المطلوب' , 
            'restaurant_id.exists'   =>  'المطعم غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);
        
        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        $restaurant = Restaurant::find($request->restaurant_id);

        return responceJson(1 , 'success' , $restaurant );
    }



    public function product(Request $request)
    {
        if($request->has('id'))
        {
            $product = Product::where('id' , $request->id)->get();
            if(count($product))
            {
                return responceJson(1 , 'success' , $product);
            }

            return responceJson(0 , 'invalid data');
        }
        return responceJson(0 , 'missing data');
        
    }

    public function contact(Request $request)
    {
        $rules = [
            'name'    => 'required' , 
            'email'   => 'required|email' , 
            'phone'   => 'required',
            'subject' => 'required',
            'message' => 'required' ,
            'type'    => 'required|in:1,2,3'
        ];
        $messages = [
            'name.required'    => 'يجب إدخال الإسم' , 
            'email.required'   => 'يجب إدخال البريد الإلكتروني' , 
            'phone.required'   => 'يجب إدخال رقم الجوال' , 
            'subject.required' => 'يجب إدخال عنوان الرسالة' , 
            'message.required' => 'يجب إدخال الرسالة' , 
            'type.required'    => 'يجب إدخال نوع الرسالة' ,  
            'type.in'          => 'يوجد مشكلة في نوع الرسالة'
        ];
        $validator = validator()->make($request->all() ,$rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $contact = Contact::create($request->all());
        return responceJson(1 , 'success' , $contact);
    }

    public function offers()
    {
        $offers = Offer::paginate(10);
        return count($offers) ? responceJson(1 , 'success' , $offers)
                              : responceJson(0 , 'no data');
    }

    public function add_review(Request $request)
    {
        $rules = [
            'restaurant_id' => 'required|exists:restaurants,id' , 
            'body'          => 'required' , 
            'rate'          => 'required|in:1,2,3,4,5'
        ];

        $messages = [
            'restaurant_id.required' => 'يجب إدخال المطعم' , 
            'restaurant_id.exists'   => 'المطعم غير موجود' ,
            'body.required'          => 'يجب إدخال التعليق' , 
            'rate.required'          => 'يجب إدخال التقييم' ,
            'rate.in'                => 'التقييم غير صحيح' 
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        else
        {
            $review = $request->user()->reviews()->create($request->all());
            return responceJson(1 , 'تم إضافة تعليقك بنجاح' , $review);
        }
    }

    public function notifications(Request $request)
    {
        $client = $request->user();
        $notifications = $client->notifications
        ;

        return count($notifications) ? responceJson(1 , 'success' , $notifications->load('order'))
                                     : responceJson(1 , 'لا يوجد إشعارات  ');
    }

    public function count_notifications(Request $request)
    {
        $client        = $request->user();

        $notifications = $client->notifications()->where('is_read', 0)->get();
        return responceJson(1 , 'success' , count($notifications));
    }
}
