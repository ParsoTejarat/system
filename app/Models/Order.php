<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    const STATUS = [
        'orders' => 'ثبت سفارش',
        'pending' => 'پیش فاکتور شده',
        'invoiced' => 'فاکتور شده',
    ];

    const REQ_FOR = [
        'pre-invoice' => 'پیش فاکتور',
        'invoice' => 'فاکتور',
        'amani-invoice' => 'فاکتور امانی',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
