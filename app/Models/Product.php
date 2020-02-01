<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{

    protected $table = 'products';
    public $timestamps = true;
    protected $fillable = array('restaurant_id', 'name', 'description', 'price', 'offer_price', 'preparation_time', 'image');
    protected $appends = ['image_url'];


    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }
    
    public function getImageUrlAttribute()
    {
        return asset('products_images/' . $this->image);
    }

    

}