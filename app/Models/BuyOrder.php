<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyOrder extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'orders' => 'ثبت سفارش',
        'bought' => 'خریداری شده',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
