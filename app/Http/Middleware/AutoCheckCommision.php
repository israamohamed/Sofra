<?php

namespace App\Http\Middleware;

use Closure;

class AutoCheckCommision
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        $restaurant = $request->user();
    
        if(($restaurant->total_commissions - $restaurant->total_payments) > settings()->max_orders_amount)
        {
            $restaurant->is_active = 0;
            $restaurant->save();
            return responceJson(0,'حدث خطأ ، يرجى الرجوع إلى إدارة التطبيق');
        }
        return $next($request);
    }
}
