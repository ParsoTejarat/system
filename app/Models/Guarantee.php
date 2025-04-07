<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guarantee extends Model
{
    use HasFactory;

    protected $guarded = [];


    const STATUS = [
        'pending' => 'در انتظار فعال سازی',
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
        'expired' => 'منقضی شده',
        'voided' => 'باطل شده',
    ];

    const PERIOD = [
        '18' => '18 ماهه',
        '24' => '24 ماهه',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeStatus($query, $status)
    {
        if ($status) {
            $query->where('status', $status);
        }
    }

    public function scopeSerialNumber($query, $serial)
    {
        if ($serial) {
            $query->where('serial_number', 'LIKE', "%{$serial}%");
        }
    }


}
