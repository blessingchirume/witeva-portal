<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approval_status',
        'delivery_date',
        'admin_delivery_status',
        'customer_delivery_status',
        'payment_status',
        'order_ref_number',
        'order_number'
    ];

    public function items () {
        return $this->belongsToMany(Item::class, 'order_items', 'item_id', 'order_id');
    }

    public function user () {
        return $this->belongsTo(User::class);
    }
}
