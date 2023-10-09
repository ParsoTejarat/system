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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function printers()
    {
        return $this->belongsToMany(Printer::class);
    }
}
