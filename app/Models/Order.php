<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'OrderID';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'UserID',
        'OrderDate',
        'TotalAmount',
        'OrderStatus',
        'PaymentMethod',
        'ShippingAddress',
    ];

  
    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'OrderID', 'OrderID');
    }
}
