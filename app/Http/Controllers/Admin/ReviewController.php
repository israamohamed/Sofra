<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('restaurant_id'))
        {
            $reviews = Review::where('restaurant_id' , $request->restaurant_id)->paginate(9);
            $res_id = $request->restaurant_id;
            return view('admin.reviews.index' , compact('reviews' , 'res_id'));
        }
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        $review->delete();
        return redirect()->route('review.index', ['restaurant_id' => $review->restaurant])->with('success' , 'review is deleted successfully');
    }
}
