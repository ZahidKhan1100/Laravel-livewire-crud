<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // protected $table = 'orders';
    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'status',
        'notes',
        'address',
        'items',
        'customer_name',
        'customer_email',
        'customer_address',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    // Define the relationship between the Order and User models
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
