<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
     protected $fillable = [
        'order_id',
        'name',
        'quantity',
        'price',
    ];

    // Belongs to an order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Belongs to a menu item
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
