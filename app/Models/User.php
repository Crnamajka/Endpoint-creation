<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'users';
    protected $primaryKey = 'UserID';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'Username',        
        'Email',           
        'PasswordHash',    
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'PasswordHash',    
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'CreatedDate' => 'datetime',  
        ];
    }

    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class, 'UserID', 'UserID');
    }

    
    public function orders()
    {
        return $this->hasMany(Order::class, 'UserID', 'UserID');
    }
    
}
