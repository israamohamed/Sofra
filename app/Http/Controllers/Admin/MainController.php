<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Client;
use App\Models\Order;
use App\Models\Review;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Contact;

class MainController extends Controller
{
    public function home()
    {
        $no_of_restaurants = Restaurant::count();
        $no_of_clients     = Client::count();
        $no_of_orders      = Order::count();
        $no_of_reviews     = Review::count();
        $no_of_offers      = Offer::count();
        $no_of_products    = Product::count();
        $no_of_contacts    = Contact::count();
        return view('admin.home' , compact('no_of_restaurants' , 'no_of_clients' , 'no_of_orders' , 'no_of_reviews' , 'no_of_offers' , 'no_of_products' , 'no_of_contacts'));
    }
}
