<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Offer;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('restaurant_id'))
        {
            $offers = Offer::where('restaurant_id' , $request->restaurant_id)->paginate(10);
            $res_id = $request->restaurant_id;
            return view('admin.offers.index' , compact('offers' , 'res_id'));
        }
      
    }

    public function destroy($id)
    {
        $offer = Offer::find($id);
        $offer->delete();
        return redirect()->route('offer.index', ['restaurant_id' => $offer->restaurant])->with('success' , 'offer is deleted successfully');
    }
}
