<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_variants';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'VariantID';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ProductID',
        'Color',
        'Size',
        'StockQuantity',
    ];

    /**
     * Relationships with other models.
     */

    
     public function product()
    {
        return $this->belongsTo(Product::class, 'ProductID', 'ProductID');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'VariantID', 'VariantID');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'VariantID', 'VariantID');
    }
}
