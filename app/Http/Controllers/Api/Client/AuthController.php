<?php

namespace App\Http\Controllers\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
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
            'name'      => 'required' , 
            'email'     => 'required|email|unique:clients' ,
            'phone'     => 'required' ,
            'region_id' => 'required|exists:regions,id' ,
            'password'  => 'required|confirmed'
        ];

        $messages = [
            'name.required'      => 'يجب إدخال الإسم' , 
            'email.required'     => 'يجب إدخال البريد الالكتروني' , 
            'email.email'        => 'يجب إدخال البريد بطريقة صحيحة' , 
            'phone.required'     => 'يجب إدخال الجوال' , 
            'region_id.required' => 'يجب إدخال المنطقة' , 
            'password.required'  => 'يجب إدخال كلمة المرور' , 
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابقة' , 
            'region_id.exists'   => 'المنطقة غير صحيحة' , 
            'email.unique'       => 'البريد موجود بالفعل ! '
        ];
        $validator = validator()->make($request->all() , $rules , $messages);
        
        if($validator->fails())
        {
            return responceJson(0 , $validator->errors()->first() , $validator->errors());
        }
        else 
        {
            $client = Client::create($request->all());
            $client->api_token = str_random(60);
            $client->save();
            return responceJson(1 , 'تم الاضافة بنجاح' , [
                'client' => $client , 
                'api_token' => $client->api_token
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
            $client = Client::where('email' , $request->email)->first();
            
            if($client)
            {
                if(Hash::check($request->password , $client->password))
                {
                    return responceJson(1 , 'success' , [
                        'client' => $client , 
                        'api_token' => $client->api_token
                    ]);
                }
                return responceJson(0 , 'بيانات الدخول غير صحيحة');
            }
            return responceJson(0 , 'بيانات الدخول غير صحيحة');
        }
    }

    public function profile(Request $request)
    {
        if($request->isMethod('GET'))
        {
            return responceJson(1 , 'success' , $request->user());
        }
        else if($request->isMethod('PUT'))
        {
            $rules = [
                'name'      => Rule::requiredIf($request->has('name')),
                'email'     => Rule::requiredIf($request->has('email'))     . '|email|unique:clients,email,'.$request->user()->id ,
                'phone'     => Rule::requiredIf($request->has('phone')) ,
                'region_id' => Rule::requiredIf($request->has('region_id')) . '|exists:regions,id' ,
                'password'  => Rule::requiredIf($request->has('password'))  . '|confirmed'
            ];
    
            $messages = [
                'name.required'      => 'يجب إدخال الإسم' , 
                'email.required'     => 'يجب إدخال البريد الالكتروني' , 
                'email.email'        => 'يجب إدخال البريد بطريقة صحيحة' , 
                'phone.required'     => 'يجب إدخال الجوال' , 
                'region_id.required' => 'يجب إدخال المنطقة' , 
                'password.required'  => 'يجب إدخال كلمة المرور' , 
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابقة' , 
                'region_id.exists'   => 'المنطقة غير صحيحة' , 
                'email.unique'       => 'البريد موجود بالفعل ! '
            ];
            $validator = validator()->make($request->all() , $rules , $messages);
            
            if($validator->fails())
            {
                return responceJson(0 , $validator->errors()->first() , $validator->errors());
            }
            else 
            {
                $client = $request->user();
                $client->update($request->all());
                return responceJson(1 , 'تم التعديل بنجاح' , $client);
            }
        }
    }

    public function forget_password(Request $request)
    {
        $rules = ['email' => 'required|exists:clients'];

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
            $client = Client::where('email' , $request->email)->first();
            $client->pin_code = $code;
            $client->save();

            Mail::to($client->email)
                ->send(new ResetPassword($code));

            if (count(Mail::failures()) > 0) 
            {
                return responceJson(0 , 'حدث خطأ');
            }
            else 
            {
                return responceJson(1 , 'برجاء فحص بريدك' , $client);
            }
        }
    }

    public function reset_password(Request $request)
    {
        $rules = [
            'email'    => 'required|exists:clients' ,
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
            $client = Client::where('email' , $request->email)->first();

            if($request->pin_code == $client->pin_code)
            {
                $client->update(['password' => $request->password]);
                $client->pin_code = null;
                $client->save();  
                
                return responceJson(1 , 'تم تغيير كلمة المرور ' , $client);
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
