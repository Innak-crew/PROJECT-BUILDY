<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaborCategory extends Model
{
    use HasFactory;

    protected $table = 'labor_categories';

    protected $fillable = [
        'name',
        'description',
    ];
}
