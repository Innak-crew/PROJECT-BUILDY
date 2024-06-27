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
        'creator_id',
        'customer_id',
        'status',
        'start_date',
        'end_date',
        'is_set_approved',
        'is_approved',
    ];
    

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'order_id', 'id');
    }
    
    public function followup()
    {
        return $this->hasMany(Schedule::class, 'order_id', 'id');
    }
    
    public function followupReminders()
    {
        return $this->hasMany(Reminders::class, 'order_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function creator(){
        return $this->belongsTo(User::class,"creator_id");
    }

    public function Customer(){
        return $this->belongsTo(Customers::class,"customer_id");
    }

    public function invoice()
    {
        return $this->belongsTo(Invoices::class, 'id', 'order_id');
    }

    public function paymentHistory(){
        return $this->hasMany(PaymentHistory::class,"order_id", 'id');
    }

    public function labours(){
        return $this->hasMany(Labour::class,"order_id");
    }

}
 