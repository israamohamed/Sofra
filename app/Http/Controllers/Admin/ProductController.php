<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {  
        if($request->has('restaurant_id'))
        {
            $products = Product::where('restaurant_id' , $request->restaurant_id)->paginate(9);
            $res_id = $request->restaurant_id;
            return view('admin.products.index' , compact('products' , 'res_id'));
        }
    }

    public function destroy($id)
    {
        
        $product = Product::find($id);
        if($product->orders()->count())
        {
            return back()->with('error' , 'Cannot delete this product , product has many orders!!');
        }
      
        $product->delete();
        return redirect()->route('product.index' , ['restaurant_id' => $product->restaurant])->with('success' , 'product is deleted successfully');
    }
}
