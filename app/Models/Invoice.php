<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'در دست اقدام',
        'paid' => 'تسویه شده',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot([
            'count',
            'unit',
            'price',
            'total_price',
            'discount_amount',
            'extra_amount',
            'tax',
            'invoice_net',
        ]);
    }
}
