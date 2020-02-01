<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Offer;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offers(Request $request)
    {
        $offers = Offer::where('restaurant_id' , $request->user()->id)->get();
        return responceJson(1 , 'success' , $offers);
    }

   
    public function add_offer(Request $request)
    {
        $rules = [
            'title'            => 'required' ,
            'description'      => 'required',
            'image'            => 'required|image|mimes:jpeg,png,jpg,gif,svg' ,
            'from'             => 'required|date_format:Y-m-d',
            'to'               => 'required|date_format:Y-m-d'
            
        ];
        $messages = [
            'title.required'       => 'يجب إدخال إسم العرض' , 
            'description.required' => 'يجب إدخال وصف مختصر عن العرض' ,  
            'image.required'       => 'يجب إدخال صورة العرض' , 
            'image.image'          => 'يجب إدخال الصورة بشكل صحيح' ,
            'image.mimes'          => 'امتداد الصورة غير مناسب' ,
            'from.required'        => 'يجب إدخال تاريخ بداية العرض' , 
            'to.required'          => 'يجب إدخال تاريخ نهاية العرض' , 
            'from.date_format'     => 'يجب إدخال تاريخ بداية العرض بطريقة صحيحة' , 
            'to.date_format'       => 'يجب إدخال تاريخ نهاية العرض بطريقة صحيحة'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        else 
        {
            //Name of the image
            $imageName = time().'.'.$request->image->getClientOriginalExtension();
            //Move the uploaded file to the directory offers_images
            $request->image->move(public_path('offers_images'), $imageName);

            $restaurant = $request->user();
            $offer    = $restaurant->offers()->create($request->all());
            $offer->update(['image' => $imageName]);
            return responceJson(1 , 'تم إضافة العرض' , $offer);
        }
    }

   
    public function offer(Request $request)
    {
        $rules = ['offer_id' => 'required|exists:offers,id'];
        
        $messages = [
            'offer_id.required' => 'يجب إدخال العرض المطلوب' , 
            'offer_id.exists'   => 'العرض غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $offer = offer::where([
                                    ['id' , $request->offer_id] , 
                                    ['restaurant_id' , $request->user()->id]
                                ])->first();
        if(!$offer)
        {
            return responceJson(0 , 'العرض خطأ');
        }

        if($request->isMethod('GET'))
        {
            return responcejson(1 , 'success' , $offer);
        }
        else if($request->isMethod('PUT'))
        {
            $rules = [
                'name'             => Rule::requiredIf($request->has('name')) ,
                'description'      => Rule::requiredIf($request->has('description')),
                'image'            => Rule::requiredIf($request->has('image')). '|image|mimes:jpeg,png,jpg,gif,svg' , 
                'from'             => Rule::requiredIf($request->has('from')) . '|date' , 
                'to'               => Rule::requiredIf($request->has('to')) . '|date'
            ];
            $messages = [
                'name.required'        => 'يجب إدخال إسم العرض' , 
                'description.required' => 'يجب إدخال وصف مختصر عن العرض' ,  
                'image.required'       => 'يجب إدخال صورة العرض' , 
                'image.image'          => 'يجب إدخال الصورة بشكل صحيح' ,
                'image.mimes'          => 'امتداد الصورة غير مناسب' ,
                'from.required'        => 'يجب إدخال تاريخ بداية العرض' , 
                'to.required'          => 'يجب إدخال تاريخ نهاية العرض' , 
                'from.date'            => 'يجب إدخال تاريخ بداية العرض بطريقة صحيحة' , 
                'to.date'              => 'يجب إدخال تاريخ نهاية العرض بطريقة صحيحة'
            ];

            $validator = validator()->make($request->all() , $rules , $messages);

            if($validator->fails())
            {
                return responceJson(0 , $validator->errors()->first() , $validator->errors());
            }

            else 
            {
                $offer->update($request->all());
                return responceJson(1 , 'تم التعديل بنجاح' , $offer);
            }
        }
    }

   
    public function delete_offer(Request $request)
    {
        $rules = ['offer_id' => 'required|exists:offers,id'];
        
        $messages = [
            'offer_id.required' => 'يجب إدخال العرض المطلوب' , 
            'offer_id.exists'   => 'العرض غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $offer = offer::where([
                                    ['id' , $request->offer_id] , 
                                    ['restaurant_id' , $request->user()->id]
                                ])->first();
        if(!$offer)
        {
            return responceJson(0 , 'العرض خطأ');
        }

        else 
        {
            $offer->delete();
            return responceJson(1 , 'تم حذف العرض');
        }
    }
}
