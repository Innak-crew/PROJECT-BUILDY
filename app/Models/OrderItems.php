<?php

namespace App\Models;

use Carbon\Carbon;
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

    

    public function Order()
    {
        return $this->belongsTo(Orders::class);
    }

    public function catagories(){
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function product(){
        return $this->belongsTo(Products::class, 'product_id');
    }



}
 