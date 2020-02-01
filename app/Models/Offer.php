<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('restaurant_id', 'image', 'description', 'from', 'to', 'title');
    protected $appends = ['image_url'];

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function getImageUrlAttribute()
    {
        return asset('offers_images/' . $this->image);
    }

}