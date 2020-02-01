<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::group(['middleware' => ['auth','auto-check-permission'] , 'namespace' => 'Admin'] , function(){
    
    Route::get('/home'  , 'MainController@home')->name('home');
    Route::resource('admin/category'     , 'CategoryController')->except(['show']);
    Route::resource('admin/city'         , 'CityController')->except(['show']);
    Route::resource('admin/region'       , 'RegionController')->except(['show']);
    Route::resource('admin/paymentMethod', 'PaymentMethodController')->except(['show']);
    Route::resource('admin/role'         , 'RoleController')->except(['show']);
    Route::resource('admin/payment'         , 'PaymentController')->except(['show']);
    //Restaurants
    Route::get('admin/restaurant' , 'RestaurantController@index')->name('restaurant.index');
    Route::get('admin/restaurant/{id}' , 'RestaurantController@show')->name('restaurant.show');
    Route::get('admin/restaurant/categories' , 'RestaurantController@categories')->name('restaurant.categories');
    Route::get('admin/show_restaurant_details' , 'RestaurantController@show_details')->name('restaurant_details.show');
    Route::delete('admin/restaurant/{id}' , 'RestaurantController@destroy')->name('restaurant.destroy');
    Route::post('admin/restaurant/toggle_activation' , 'RestaurantController@toggle_activation' )->name('restaurant.toggle_activation');
    Route::get('admin/restaurant/search' , 'RestaurantController@search' )->name('restaurant.search');
    //Orders
    Route::get('admin/orders' , 'OrderController@index')->name('order.index');
    Route::get('admin/order_products/{id}' , 'OrderController@products')->name('order_products.show');
    Route::delete('admin/order/{id}' , 'OrderController@destroy')->name('order.destroy');
    //Reviews
    Route::get('admin/reviews' , 'ReviewController@index')->name('review.index');
    Route::delete('admin/review/{id}' , 'ReviewController@destroy')->name('review.destroy');
    //Products
    Route::get('admin/products' , 'ProductController@index')->name('product.index');
    Route::delete('admin/product/{id}' , 'ProductController@destroy')->name('product.destroy');
    //Offers
    Route::get('admin/offers' , 'OfferController@index')->name('offer.index');
    Route::delete('admin/offer/{id}' , 'OfferController@destroy')->name('offer.destroy');
    //Contacts
    Route::get('admin/contacts' , 'ContactController@index')->name('contact.index');
    Route::delete('admin/contact/{id}' , 'ContactController@destroy')->name('contact.destroy');
     //Settings
     Route::get('admin/settings' , 'SettingController@edit')->name('setting.edit');
     Route::put('admin/settings' , 'SettingController@update')->name('setting.update');
     //Client
     Route::get('admin/client' , 'ClientController@index')->name('client.index');
     Route::delete('admin/client/{id}' , 'ClientController@destroy')->name('client.destroy');
     Route::post('admin/client/toggle_activation' , 'ClientController@toggle_activation' )->name('client.toggle_activation');
     Route::get('admin/client/{id}' , 'ClientController@show')->name('client.show');
     //Users
     Route::resource('admin/user'     , 'UserController')->except(['show']);

});



Auth::routes(['register' => false]);

//Route::get('/home', 'HomeController@index')->name('home');
