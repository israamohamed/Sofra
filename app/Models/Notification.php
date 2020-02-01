<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('title', 'body', 'is_read', 'order_id', 'notifiable_id', 'notifiable_type');

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

}