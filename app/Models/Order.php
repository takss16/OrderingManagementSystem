<?php

namespace App\Models;

use App\Models\OrderItem;
use App\Models\RestoTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'table_id',
        'total_amount',
        'status',
    ];

    // A single order has many order items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // An order belongs to a specific table
    public function table()
    {
        return $this->belongsTo(RestoTable::class, 'table_id');
    }
}
