<?php

namespace App\Http\Controllers\Api\Restaurant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class MainController extends Controller
{
    public function categories()
    {
        $categories = Category::all();
        return count($categories) ? responceJson(1 , 'success' , $categories)
                                  : responceJson(0 , 'no data');
    }

    public function notifications(Request $request)
    {
        $restaurant    = $request->user();
        $notifications = $restaurant->notifications;

        return count($notifications) ? responceJson(1 , 'success' , $notifications->load('order'))
                                     : responceJson(1 , 'لا يوجد إشعارات  ');
    }

    public function count_notifications(Request $request)
    {
        $restaurant    = $request->user();
       
        $notifications = $restaurant->notifications()->where('is_read', 0)->get();
          
        return responceJson(1 , 'success' , count($notifications));
    }
}
