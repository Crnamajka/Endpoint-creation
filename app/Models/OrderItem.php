<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'OrderItemID';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'OrderID',
        'VariantID',
        'Quantity',
        'UnitPrice',
    ];

   
    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderID', 'OrderID');
    }
    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'VariantID', 'VariantID');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');  // 'ProductID' en lugar de 'VariantID'
    }
    
}
