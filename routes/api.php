<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1' , 'namespace' => 'Api'] , function(){

    Route::group(['prefix' => 'client' , 'namespace' => 'Client'] , function(){
        Route::get('restaurants'     , 'MainController@restaurants');
        // 3 serverices when click on a restaurant
        Route::get('restaurant'      , 'MainController@restaurant');
        Route::get('products'        , 'MainController@products');
        Route::get('reviews'         , 'MainController@reviews');
        //------------------------------------------//
        Route::get('product'         , 'MainController@product');
        Route::get('offers'          , 'MainController@offers');
        Route::post('contact'        , 'MainController@contact');
        Route::post('register'       , 'AuthController@register');
        Route::post('login'          , 'AuthController@login');
        Route::post('forget_password', 'AuthController@forget_password');
        Route::post('reset_password' , 'AuthController@reset_password');
        

        Route::group(['middleware' => 'auth:api-client'] , function(){

            Route::post(                   'add_review' , 'MainController@add_review');
            Route::match(['GET' , 'PUT'] , 'profile'    , 'AuthController@profile');
            //Orders
            Route::post(                   'new_order'      , 'OrderController@new_order');
            Route::post(                   'decline_order'  , 'OrderController@decline_order');
            Route::post(                   'deliver_order'  , 'OrderController@deliver_order');
            Route::get (                   'orders'         , 'OrderController@orders');
            Route::get (                   'order_details'  , 'OrderController@order_details');
            //Tokens
            Route::post('registerToken' , 'AuthController@registerToken');
            Route::post('remove_token'   , 'AuthController@remove_token');
            //notifications
            Route::get('notifications'       , 'MainController@notifications');
            Route::get('count_notifications' , 'MainController@count_notifications');
        });

    });

    Route::group(['prefix' => 'restaurant' , 'namespace' => 'Restaurant'] , function(){
        Route::get('categories'      , 'MainController@categories');
        Route::post('register'       , 'AuthController@register');
        Route::post('login'          , 'AuthController@login');
        Route::post('forget_password', 'AuthController@forget_password');
        Route::post('reset_password' , 'AuthController@reset_password');

        Route::group(['middleware' => ['auth:api','auto-check-commission']] , function(){
            Route::match(['GET' , 'PUT'] , 'profile'     , 'AuthController@profile');
            //Product
            Route::get(                    'products'    , 'ProductController@products');
            Route::post(                   'add_product' , 'ProductController@add_product');
            Route::match(['GET' , 'PUT'] , 'product'     , 'ProductController@product');
            Route::delete(                 'delete_product' , 'ProductController@delete_product');
            //Offer
            Route::get(                    'offers'    , 'OfferController@offers');
            Route::post(                   'add_offer' , 'OfferController@add_offer');
            Route::match(['GET' , 'PUT'] , 'offer'     , 'OfferController@offer');
            Route::delete(                 'delete_offer' , 'OfferController@delete_offer');
            //Tokens
            Route::post('registerToken' , 'AuthController@registerToken');
            Route::post('remove_token'   , 'AuthController@remove_token');
            //Orders
            Route::post('accept_order'   , 'OrderController@accept_order');
            Route::post('reject_order'   , 'OrderController@reject_order');
            Route::post('confirm_order'  , 'OrderController@confirm_order');
            Route::get ('orders'         , 'OrderController@orders');
            //notifications
            Route::get('notifications'       , 'MainController@notifications');
            Route::get('count_notifications' , 'MainController@count_notifications');

        });
    });

    Route::group(['prefix' => 'general' ] , function(){

        Route::get('cities' , 'GeneralController@cities');
        Route::get('regions' , 'GeneralController@regions');
        Route::get('payment_methods' , 'GeneralController@payment_methods');
        Route::get('settings' , 'GeneralController@settings');
        
    });

});
