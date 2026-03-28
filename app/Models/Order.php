<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'uid',
        'restaurant_uid',
        'user_uid',
        'address_uid',
        'amount',
        'status',
    ];

    protected $hidden = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function order_items()
    {
        return $this->hasMany(OrderItems::class, 'order_uid', 'uid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_uid', 'uid');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'restaurant_uid', 'uid');
    }
}
