<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Token;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\resetPassword;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'name'           => 'required' , 
            'email'          => 'required|email|unique:restaurants' ,
            'phone'          => 'required' ,
            'region_id'      => 'required|exists:regions,id' ,
            'password'       => 'required|confirmed' , 
            'minimum_charge' => 'required' ,
            'delivery_fees'  => 'required' , 
            'image'          => 'required|image|mimes:jpeg,png,jpg,gif,svg' ,
            'categories'     => 'required|array|exists:categories,id'
        ];
        $messages = [
            'name.required'           => 'يجب إدخال إسم المطعم' , 
            'email.required'          => 'يجب إدخال البريد الالكتروني' , 
            'email.email'             => 'يجب إدخال البريد بطريقة صحيحة' , 
            'phone.required'          => 'يجب إدخال الجوال' , 
            'region_id.required'      => 'يجب إدخال المنطقة' , 
            'password.required'       => 'يجب إدخال كلمة المرور' , 
            'password.confirmed'      => 'تأكيد كلمة المرور غير متطابقة' , 
            'region_id.exists'        => 'المنطقة غير صحيحة' , 
            'email.unique'            => 'البريد موجود بالفعل ! ' , 
            'minimum_charge.required' => 'يجب إدخال الحد الأدنى للطلب' , 
            'delivery_fees.required'  => 'يجب إدخال رسوم التوصيل' ,
            'image.required'          => 'يجب إدخال صورة المتجر' , 
            'categories.required'     => 'يجب إدخال تصنيفات المطعم' , 
            'categories.array'        => 'طريقة إدخال التصنيفات غير صحيحة' ,
            'categories.exists'       => 'التصنيفات غير موجودة' 
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
            $request->image->move(public_path('restaurants_images'), $imageName);

            $restaurant = Restaurant::create($request->all());
            $restaurant->update(['image' => $imageName]);
            $restaurant->api_token = str_random(60);
            $restaurant->save();
            $restaurant->categories()->attach($request->categories);
            return responceJson(1 , 'تم الاضافة بنجاح' , [
                'restaurant' => $restaurant , 
                'api_token' => $restaurant->api_token
            ]);
        }
    }

    public function login(Request $request)
    {
        $rules = [ 
            'email'     => 'required' ,
            'password'  => 'required'
        ];

        $messages = [
            'email.required'      => 'يجب إدخال البريد الاكتروني' , 
            'password.required'  => 'يجب إدخال كلمة المرور' 
        ];

        $validator = validator()->make($request->all() , $rules , $messages);
        
        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        else 
        {
            $restaurant = Restaurant::where('email' , $request->email)->first();
            
            if($restaurant)
            {
                if(Hash::check($request->password , $restaurant->password))
                {
                    return responceJson(1 , 'success' , [
                        'restaurant' => $restaurant , 
                        'api_token'  => $restaurant->api_token
                    ]);
                }
                return responceJson(0 , 'بيانات الدخول غير صحيحة');
            }
            return responceJson(0 , 'بيانات الدخول غير صحيحة');
        }
    }

    public function profile(Request $request)
    {
        $restaurant = $request->user();
        if($request->isMethod('GET'))
        {
            return responceJson(1 , 'success' , [
                'restaurant' => $restaurant ,
                'categories' => $restaurant->categories()->pluck('categories.name' , 'categories.id')->toArray()
            ]);
        }
        else if($request->isMethod('PUT'))
        {
            $rules = [
                'name'          => Rule::requiredIf($request->has('name')),
                'email'         => Rule::requiredIf($request->has('email'))     . '|email|unique:restaurants,email,'.$request->user()->id ,
                'phone'         => Rule::requiredIf($request->has('phone')) ,
                'region_id'     => Rule::requiredIf($request->has('region_id')) . '|exists:regions,id' ,
                'password'      => Rule::requiredIf($request->has('password'))  . '|confirmed' , 
                'minimum_charge'=> Rule::requiredIf($request->has('minimum_charge')) ,
                'delivery_fees' => Rule::requiredIf($request->has('delivery_fees')) , 
                'image'         => Rule::requiredIf($request->has('image')) , 
                'categories'    => Rule::requiredIf($request->has('categories')) . '|array|exists:categories,id' ,
                'availability'  => Rule::requiredIf($request->has('availability')) . '|in:0,1'
            ];
    
            $messages = [
                'name.required'           => 'يجب إدخال الإسم' , 
                'email.required'          => 'يجب إدخال البريد الالكتروني' , 
                'email.email'             => 'يجب إدخال البريد بطريقة صحيحة' , 
                'phone.required'          => 'يجب إدخال الجوال' , 
                'region_id.required'      => 'يجب إدخال المنطقة' , 
                'password.required'       => 'يجب إدخال كلمة المرور' , 
                'password.confirmed'      => 'تأكيد كلمة المرور غير متطابقة' , 
                'region_id.exists'        => 'المنطقة غير صحيحة' , 
                'email.unique'            => 'البريد موجود بالفعل ! ' , 
                'minimum_charge.required' => 'يجب إدخال الحد الأدنى للطلب' , 
                'delivery_fees.required'  => 'يجب إدخال رسوم التوصيل' ,
                'image.required'          => 'يجب إدخال صورة المتجر' , 
                'categories.required'     => 'يجب إدخال تصنيفات المطعم' , 
                'categories.array'        => 'طريقة إدخال التصنيفات غير صحيحة' ,
                'categories.exists'       => 'التصنيفات غير موجودة' ,
                'availability.required'   => 'يجب إدخال الحالة' , 
                'availability.in'         => 'يجب إدخال الحالة بشكل صحيح'
            ];
            $validator = validator()->make($request->all() , $rules , $messages);
            
            if($validator->fails())
            {
                return responceJson(0 , $validator->errors()->first() , $validator->errors());
            }
            else 
            {
                $restaurant = $request->user();
                $restaurant->update($request->all());
                if($request->has('categories'))
                {
                    $restaurant->categories()->sync($request->categories);
                }
                return responceJson(1 , 'تم التعديل بنجاح' , $restaurant);
            }
        }
    }

    public function forget_password(Request $request)
    {
        $rules = ['email' => 'required|exists:restaurants'];

        $messages = [
            'email.required' => 'يجب إدخال البريد الالكتروني' , 
            'email.exists'   => 'هذا البريد ليس موجود'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        else 
        {
            $code = rand(1111 , 9999);
            $restaurant = Restaurant::where('email' , $request->email)->first();
            $restaurant->pin_code = $code;
            $restaurant->save();

            Mail::to($restaurant->email)
                ->send(new ResetPassword($code));

            if (count(Mail::failures()) > 0) 
            {
                return responceJson(0 , 'حدث خطأ');
            }
            else 
            {
                return responceJson(1 , 'برجاء فحص بريدك' , $restaurant);
            }
        }
    }

    public function reset_password(Request $request)
    {
        $rules = [
            'email'    => 'required|exists:restaurants' ,
            'pin_code' => 'required' , 
            'password' => 'required|confirmed'
        ];

        $messages = [
            'email.required'     => 'لم يتم إرسال البريد' ,
            'email.exists'       => 'البريد غير موجود' ,
            'pin_code.required'  => 'يجب إدخال الكود التأكيدي' , 
            'password.required'  => 'يجب إدخال كلمة المرور الجديدة' , 
            'password.confirmed' => 'كلمة المرور غير متطابقة'
        ];

        $validator = validator()->make($request->all() , $rules , $messages);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        else 
        {
            $restaurant = Restaurant::where('email' , $request->email)->first();

            if($request->pin_code == $restaurant->pin_code)
            {
                $restaurant->update(['password' => $request->password]);
                $restaurant->pin_code = null;
                $restaurant->save();  
                
                return responceJson(1 , 'تم تغيير كلمة المرور ' , $restaurant);
            }
            else 
            {
                return responceJson(0 , 'الكود التأكيدي غير صحيح');
            }
        }
    }
    public function registerToken(Request $request) 
    {
        $validator = validator()->make($request->all() , [
            'token'     => 'required' ,
            'platform'  => 'required|in:android,ios'
        ]);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        Token::where('token' , $request->token)->delete();
        $request->user()->tokens()->create($request->all());
        return responceJson(1 , 'تم التسجيل بنجاح');
    }

    public function remove_token(Request $request) 
    {
        $validator = validator()->make($request->all() , [
            'token' => 'required' ,
        ]);

        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }

        Token::where('token' , $request->token)->delete();
        return responceJson(1 , 'تم الحذف بنجاح');
    }
}
