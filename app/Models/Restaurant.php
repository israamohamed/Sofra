<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Restaurant extends Authenticatable 
{
    
    protected $table = 'restaurants';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'phone', 'password', 'minimum_charge', 'delivery_fees', 'availability', 'whatsapp', 'image', 'region_id', 'is_active');
    protected $hidden = array('password', 'api_token');
    protected $guard = 'api';
    protected $appends = ['image_url'];

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function setPasswordAttribute($value)
    {
        return $this->attributes['password'] = bcrypt($value);
    }

    public function getImageUrlAttribute()
    {
        return asset('restaurants_images/' . $this->image);
    }

    public function getTotalCommissionsAttribute($value)
    {
        $commissions = $this->orders()->where('state','delivered')->sum('commission');

        return $commissions;
    }

    public function getTotalPaymentsAttribute($value)
    {
        $payments = $this->payments()->sum('paid');

        return $payments;
    }

}