<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    
    public $timestamps = false;

    protected $primaryKey = 'CartItemID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'CartID',
        'VariantID',
        'Quantity',
        'UnitPrice',
    ];

    
    public function shoppingcart()
    {
        return $this->belongsTo(ShoppingCart::class, 'CartID', 'CartID');
    }

    
    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'VariantID', 'VariantID');
    }
}
