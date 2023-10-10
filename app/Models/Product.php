<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    const COLORS = [
        'black' => 'مشکی'
    ];

    const UNITS = [
        'number' => 'عدد'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function printers()
    {
        return $this->belongsToMany(Printer::class);
    }

    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)->withPivot([
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
