<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';
    

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ProductID';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ProductName',
        'ProductDescription',
        'Price',
        'OtherAttributes',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'OtherAttributes' => 'array', 
        'Price' => 'decimal:2', 
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class, 'ProductID', 'ProductID');
    }

    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, ProductVariant::class, 'ProductID', 'VariantID');
    }

    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductVariant::class, 'ProductID', 'VariantID');
    }
}

