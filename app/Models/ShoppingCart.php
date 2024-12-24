<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shopping_carts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'CartID';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'UserID',
        'CreatedDate',
        'Status',
    ];

    /**
     * Relationships with other models.
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'UserID', 'UserID');
    }

    
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'CartID', 'CartID');
    }
}
