<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'unit',
        'quantity',
        'discount_amount',
        'discount_percentage',
        'total',
    ];
}
 