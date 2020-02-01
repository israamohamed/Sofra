<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        $products = Product::where('restaurant_id' , $request->user()->id)->get();
        return responceJson(1 , 'success' , $products);
    }

   
    public function add_product(Request $request)
    {
        $rules = [
            'name'             => 'required' ,
            'description'      => 'required',
            'price'            => 'required|alpha_num' ,
            'offer_price'      => 'alpha_num' , 
            'preparation_time' => 'required|alpha_num' , 
            'image'            => 'required|image|mimes:jpeg,png,jpg,gif,svg'
        ];
        $messages = [
            'name.required'              => 'يجب إدخال إسم المنتج' , 
            'description.required'       => 'يجب إدخال وصف مختصر عن المنتج' , 
            'price.required'             => 'يجب إدخال سعر المنتج' , 
            'price.alpha_num'            => 'يجب أن يكون السعر بالأرقام' , 
            'offer_price.alpha_num'      => 'يجب أن يكون السعر في الخصم بالأرقام' , 
            'preparation_time.required'  => 'يجب إدخال مدة التحضير' , 
            'preparation_time.alpha_num' => 'يجب أن تكون مدة التحضير بالأرقام' , 
            'image.required'             => 'يجب إدخال صورة المنتج',
            'image.image'                => 'يجب إدخال الصورة بشكل صحيح' ,
            'image.mimes'                => 'امتداد الصورة غير مناسب' 
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
            $request->image->move(public_path('products_images'), $imageName);

            $restaurant = $request->user();
            $product    = $restaurant->products()->create($request->all());
            $product->update(['image' => $imageName]);
            return responceJson(1 , 'تم إضافة المنتج' , $product);
        }
    }

   
    public function product(Request $request)
    {
        $rules = ['product_id' => 'required|exists:products,id'];
        
        $messages = [
            'product_id.required' => 'يجب إدخال المنتج المطلوب' , 
            'product_id.exists'   => 'المنتج غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $product = Product::where([
                                    ['id' , $request->product_id] , 
                                    ['restaurant_id' , $request->user()->id]
                                ])->first();
        if(!$product)
        {
            return responceJson(0 , 'المنتج خطأ');
        }

        if($request->isMethod('GET'))
        {
            return responcejson(1 , 'success' , $product);
        }
        else if($request->isMethod('PUT'))
        {
            $rules = [
                'name'             => Rule::requiredIf($request->has('name')) ,
                'description'      => Rule::requiredIf($request->has('description')),
                'price'            => Rule::requiredIf($request->has('price')) .'|alpha_num' ,
                'offer_price'      => 'alpha_num' , 
                'preparation_time' => Rule::requiredIf($request->has('preparation_time')) .'|alpha_num' , 
                'image'            => Rule::requiredIf($request->has('image'))
            ];
            $messages = [
                'name.required'              => 'يجب إدخال إسم المنتج' , 
                'description.required'       => 'يجب إدخال وصف مختصر عن المنتج' , 
                'price.required'             => 'يجب إدخال سعر المنتج' , 
                'price.alpha_num'            => 'يجب أن يكون السعر بالأرقام' , 
                'offer_price.alpha_num'      => 'يجب أن يكون السعر في الخصم بالأرقام' , 
                'preparation_time.required'  => 'يجب إدخال مدة التحضير' , 
                'preparation_time.alpha_num' => 'يجب أن تكون مدة التحضير بالأرقام' , 
                'image.required'             => 'يجب إدخال صورة المنتج'
            ];

            $validator = validator()->make($request->all() , $rules , $messages);

            if($validator->fails())
            {
                return responceJson(0 , $validator->errors()->first() , $validator->errors());
            }

            else 
            {
                $product->update($request->all());
                return responceJson(1 , 'تم التعديل بنجاح' , $product);
            }
        }
    }

   
    public function delete_product(Request $request)
    {
        $rules = ['product_id' => 'required|exists:products,id'];
        
        $messages = [
            'product_id.required' => 'يجب إدخال المنتج المطلوب' , 
            'product_id.exists'   => 'المنتج غير موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        $product = Product::where([
                                    ['id' , $request->product_id] , 
                                    ['restaurant_id' , $request->user()->id]
                                ])->first();
        if(!$product)
        {
            return responceJson(0 , 'المنتج خطأ');
        }

        else 
        {
            $product->delete();
            return responceJson(1 , 'تم حذف المنتج');
        }
    }
}
