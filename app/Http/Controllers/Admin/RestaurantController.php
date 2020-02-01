<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::where(function($q) use($request){

                  if($request->has('table_search'))
                  {

                    $search = $request->table_search;

                    if($search != '')
                    {
                        //dd(strpos('hello i am israa' , 'klnjkn')  );
                        //dd($search);
                        $activation = strpos($search , "active") !== false ? 1 :  null;
                        
                        $activation = strpos($search , "not")  !== false   ? 0 :  $activation;
                        
                        $q->where('name' , 'like' , '%'. $search . '%')
                            ->orWhere('email' , 'like' , '%' . $search . '%')
                            ->orWhere('phone' , 'like' , '%' . $search . '%' )
                            ->orWhere('is_active'  , $activation );                     
                    }
                  }
             })->paginate(5);

        return view('admin.restaurants.index' , compact('restaurants'));
    }

    public function show(Request $request , $id)
    {
        //$restaurant = Restaurant::find($id);
        $restaurant = Restaurant::where('id' , $id)->withCount(['products' , 'orders' , 'offers' , 'reviews' ])->with('categories')->first();
        return view('admin.restaurants.show' , compact('restaurant'));
    }

    public function show_details(Request $request)
    {
        if($request->has('id'))
        {
            $restaurant = Restaurant::find($request->id);

            return responceJson(1 , 'success' , $restaurant);
        }
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::find($id);

        if($restaurant->orders()->count())
        {
            return redirect()->route('restaurant.index')->with('error' , 'Can\'t delete this restaurant , restaurant has orders !');
        }

        if($restaurant->products()->count())
        {
            return redirect()->route('restaurant.index')->with('error' , 'Can\'t delete this restaurant , restaurant has products !');
        }

        if($restaurant->reviews()->count())
        {
            return redirect()->route('restaurant.index')->with('error' , 'Can\'t delete this restaurant , restaurant has reviews !');
        }

        if($restaurant->offers()->count())
        {
            return redirect()->route('restaurant.index')->with('error' , 'Can\'t delete this restaurant , restaurant has offers !');
        }

        $restaurant->categories()->detach();

        $restaurant->delete();

        return redirect()->route('restaurant.index')->with('success' , 'Restaurant is deleted successfully');
    }

    public function toggle_activation(Request $request)
    {
        if($request->has('id'))
        {
            $restaurant = Restaurant::find($request->id);
            $restaurant->is_active = !$restaurant->is_active;
            $restaurant->save();

            return responceJson(1 , 'success' , $restaurant->is_active);
        }
    }

    public function search(Request $request)
    {
       // return '';
        $seacrh     = $request->table_search;

        if($search != '')
        {

            $activation = strpos($seacrh , 'active') ? 1 :  null;

            $activation = strpos($seacrh , 'not') ?    0 :  null;

            $restaurants = Restaurant::where('name' , 'like' , '%'. $seacrh . '%')
                                    ->orWhere('email' , 'like' , '%' . $seacrh . '%')
                                    ->orWhere('phone' , 'like' , '%' . $seacrh . '%' )
                                    ->orWhere('is_active'  , $activation )
                                    ->get();

            if(count($restaurants))
            {
                return back();
            }
            else 
            {
                return back()->with('error' , 'Search has no results');
            }
            
        }

        else 
        {
            return back();
        }
    }
}
