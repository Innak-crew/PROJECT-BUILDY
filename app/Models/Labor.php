<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Labor extends Model
{
    use HasFactory;

    protected $table = 'labors';

    protected $fillable = [
        'order_id',
        'date',
        'labor_category_id',
        'number_of_labors',
        'per_labor_amount',
        'total_amount',
    ];
}
