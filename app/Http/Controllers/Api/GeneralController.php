<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Region;
use App\Models\PaymentMethod;
use App\Models\Setting;


class GeneralController extends Controller
{
    public function cities()
    {
        $cities = City::all();
        return (count($cities)) ? responceJson(1 , 'success' , $cities )
                                : responceJson(0 , 'no data');
    }

    public function regions(Request $request)
    {
        $regions = Region::where(function($query) use($request){
            if($request->has('city_id'))
            {
                $query->where('city_id' , $request->city_id);
            }
        })->get();
    
        return (count($regions)) ?  responceJson(1 , 'success' , $regions)
                                 :  responceJson(0 , 'No data') ;
    }

    public function payment_methods()
    {
        $payment_methods = PaymentMethod::all();
        return responceJson(1 , 'success' , $payment_methods);
    }

    public function settings()
    {
        $settings = Setting::all();
        return responceJson(1 , 'success' , $settings);
    }
}
