<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helpers
    public function getSubtotal(): float
    {
        return $this->product->price * $this->quantity;
    }

    public function canAddQuantity(int $quantity = 1): bool
    {
        if (!$this->product->track_inventory) {
            return true;
        }
        
        return ($this->quantity + $quantity) <= $this->product->quantity;
    }
}
