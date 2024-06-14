<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'name',
        'description',
        'location',
        'type',
        'user_id',
        'customer_id',
        'status',
        'start_date',
        'end_date',
        'estimated_cost',
        'deposit_received',
    ];
}
 